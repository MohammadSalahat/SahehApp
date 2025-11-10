<?php

/**
 * Batch update all Filament resource files with proper translation keys
 */

$updates = [
    // ContactRequests - ContactRequestInfolist
    'app/Filament/Resources/ContactRequests/Schemas/ContactRequestInfolist.php' => [
        "'Contact Information'" => "'filament.contact_requests.contact_information'",
        "'Details about the user who submitted the feedback'" => "'filament.contact_requests.contact_info_description'",
        "'User Name'" => "'filament.contact_requests.full_name'",
        "'User Email'" => "'filament.contact_requests.email_address'",
        "'Request Status'" => "'filament.contact_requests.request_status'",
        "'Client contact details and request information'" => "'filament.contact_requests.contact_info_description'",
        "'Full Name'" => "'filament.contact_requests.full_name'",
        "'Email Address'" => "'filament.contact_requests.email_address'",
        "'The original message from the client'" => "'filament.contact_requests.request_message_description'",
        "'Client Message'" => "'filament.contact_requests.client_message'",
        "'No message provided'" => "'filament.contact_requests.no_message_provided'",
        "'Follow-up Information'" => "'filament.contact_requests.followup_information'",
        "'Internal notes and contact history'" => "'filament.contact_requests.followup_info_description'",
        "'Last Contact Date'" => "'filament.contact_requests.last_contact_date'",
        "'Never contacted'" => "'filament.contact_requests.never_contacted'",
        "'Internal Notes'" => "'filament.contact_requests.internal_notes'",
        "'No notes added'" => "'filament.contact_requests.no_notes'",
        "'Request submission and modification dates'" => "'filament.contact_requests.timestamps_description'",
        "'Submitted At'" => "'filament.contact_requests.submitted_at'",
        "'Name copied!'" => "'filament.contact_requests.name_copied'",
        "'Email copied!'" => "'filament.contact_requests.email_copied'",
    ],
];

foreach ($updates as $file => $replacements) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        foreach ($replacements as $search => $replace) {
            $content = str_replace("__($search)", "__($replace)", $content);
        }
        
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    } else {
        echo "File not found: $file\n";
    }
}

echo "\nBatch update complete!\n";