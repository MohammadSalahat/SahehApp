<?php

namespace App\Filament\Resources\ContactRequests\Pages;

use App\Enums\RequestStatus;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Models\ContactRequest;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListContactRequests extends ListRecords
{
    protected static string $resource = ContactRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Get total count for all records
        $totalCount = ContactRequest::query()->count();

        foreach (RequestStatus::cases() as $case) {
            // Get count for each status
            $statusCount = ContactRequest::query()->where('status', $case->value)->count();

            $tabs[$case->getLabel()] = Tab::make($case->getLabel())
                ->icon($case->getIcon())
                ->badge($statusCount)
                ->badgeColor($case->getColor())
                ->query(fn ($query) => $query->where('status', $case->value));
        }

        // Add "All" tab with total count
        $tabs[] = Tab::make(__('all'))
            ->icon('heroicon-o-square-3-stack-3d')
            ->badge($totalCount)
            ->badgeColor('gray')
            ->excludeAttributeValue();

        return $tabs;
    }
}
