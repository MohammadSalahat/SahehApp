<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Import data from mysql.sql file
     */
    public function run(): void
    {
        $sqlFilePath = database_path('mysql.sql');

        if (!File::exists($sqlFilePath)) {
            $this->command->warn("SQL file not found at: {$sqlFilePath}");
            return;
        }

        $this->command->info("Importing data from mysql.sql...");

        try {
            // Read the SQL file
            $sql = File::get($sqlFilePath);

            // Get database name from .env
            $database = env('DB_DATABASE', 'saheh');

            // Replace database name references
            $sql = str_replace('`saheh`.', '`' . $database . '`.', $sql);
            $sql = str_replace('saheh.', $database . '.', $sql);

            DB::beginTransaction();

            $totalRows = 0;
            $errors = 0;

            // Process large INSERT statements by splitting them into batches
            $insertStatements = $this->extractInsertStatements($sql);

            $this->command->info("Found " . count($insertStatements) . " INSERT statement(s) to process");

            foreach ($insertStatements as $index => $insertData) {
                $tableName = $insertData['table'];
                $columns = $insertData['columns'];
                $values = $insertData['values'];

                $this->command->info("Processing table: {$tableName} with " . count($values) . " rows");

                // Insert in batches of 100 rows to avoid max packet size issues
                $batchSize = 100;
                $batches = array_chunk($values, $batchSize);

                foreach ($batches as $batchIndex => $batch) {
                    try {
                        // Build INSERT statement for this batch
                        $valuesStr = implode(",\n", $batch);
                        $insertSql = "INSERT INTO {$tableName} ({$columns}) VALUES\n{$valuesStr};";

                        DB::statement($insertSql);
                        $totalRows += count($batch);

                        $this->command->info("  Batch " . ($batchIndex + 1) . "/" . count($batches) . " - Inserted " . count($batch) . " rows (Total: {$totalRows})");

                    } catch (\Exception $e) {
                        $errorMessage = $e->getMessage();

                        // Only show error if it's not a duplicate entry error
                        if (stripos($errorMessage, 'Duplicate entry') === false) {
                            $this->command->warn("  Error in batch: " . substr($errorMessage, 0, 150));
                            $errors++;
                        } else {
                            // Count duplicates but don't fail
                            $this->command->warn("  Skipping duplicate entries in batch " . ($batchIndex + 1));
                        }

                        // Continue with other batches even if one fails
                        continue;
                    }
                }
            }

            DB::commit();

            $this->command->info("✅ SQL import completed!");
            $this->command->info("   • Total rows inserted: {$totalRows}");
            if ($errors > 0) {
                $this->command->warn("   • Errors: {$errors} batches failed");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Failed to import SQL file: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract INSERT statements from SQL and parse them
     * This handles the large multi-line INSERT format with comma-separated VALUES
     */
    private function extractInsertStatements(string $sql): array
    {
        $statements = [];

        // Find all INSERT INTO ... VALUES (...) patterns
        // The file has format: INSERT INTO table (cols) VALUES (...), (...), ...;
        preg_match_all(
            '/INSERT\s+INTO\s+(.+?)\s+VALUES\s+(.+?);/is',
            $sql,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $fullInsert = $match[1]; // Everything between INSERT INTO and VALUES
            $valuesSection = $match[2]; // Everything between VALUES and ;

            // Parse table name and columns from the INSERT part
            if (preg_match('/`?([^`\s.]+)`?\.`?([^`\s]+)`?\s*\((.*)\)/is', $fullInsert, $tableMatches)) {
                $tableName = '`' . $tableMatches[1] . '`.`' . $tableMatches[2] . '`';
                $columns = preg_replace('/\s+/', ' ', trim($tableMatches[3]));
            } elseif (preg_match('/`?([^`\s]+)`?\s*\((.*)\)/is', $fullInsert, $tableMatches)) {
                $tableName = '`' . $tableMatches[1] . '`';
                $columns = preg_replace('/\s+/', ' ', trim($tableMatches[2]));
            } else {
                continue;
            }

            // Split the VALUES section into individual row values
            // We need to split on ),\s* but be careful with parentheses inside strings
            $values = $this->splitValues($valuesSection);

            if (!empty($values)) {
                $statements[] = [
                    'table' => $tableName,
                    'columns' => $columns,
                    'values' => $values
                ];
            }
        }

        return $statements;
    }

    /**
     * Split VALUES section into individual row values
     * Handles nested parentheses and strings correctly
     */
    private function splitValues(string $valuesSection): array
    {
        $values = [];
        $current = '';
        $depth = 0;
        $inString = false;
        $stringChar = null;
        $escaped = false;

        $len = strlen($valuesSection);
        for ($i = 0; $i < $len; $i++) {
            $char = $valuesSection[$i];

            // Handle escape sequences
            if ($escaped) {
                $current .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $current .= $char;
                $escaped = true;
                continue;
            }

            // Handle strings
            if (($char === "'" || $char === '"') && !$inString) {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
                continue;
            }

            if ($char === $stringChar && $inString) {
                $inString = false;
                $stringChar = null;
                $current .= $char;
                continue;
            }

            // Track parentheses depth (only outside of strings)
            if (!$inString) {
                if ($char === '(') {
                    $depth++;
                    $current .= $char;
                } elseif ($char === ')') {
                    $current .= $char;
                    $depth--;

                    // Complete value found (depth back to 0)
                    if ($depth === 0) {
                        $trimmed = trim($current);
                        if (!empty($trimmed)) {
                            $values[] = $trimmed;
                        }
                        $current = '';

                        // Skip the comma and whitespace after the )
                        while ($i + 1 < $len && ($valuesSection[$i + 1] === ',' || $valuesSection[$i + 1] === ' ' || $valuesSection[$i + 1] === "\n" || $valuesSection[$i + 1] === "\r" || $valuesSection[$i + 1] === "\t")) {
                            $i++;
                        }
                    }
                } else {
                    $current .= $char;
                }
            } else {
                $current .= $char;
            }
        }

        // Add any remaining value
        $trimmed = trim($current);
        if (!empty($trimmed) && $trimmed !== ';') {
            $values[] = $trimmed;
        }

        return $values;
    }

}
