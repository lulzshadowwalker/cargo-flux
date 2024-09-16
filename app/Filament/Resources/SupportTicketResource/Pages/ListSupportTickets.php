<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Enums\SupportTicketStatus;
use App\Filament\Resources\SupportTicketResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSupportTickets extends ListRecords
{
    protected static string $resource = SupportTicketResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('filament/resources/support-ticket-resource.all-tickets')),
            'open' => Tab::make(__('filament/resources/support-ticket-resource.open-tickets'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', SupportTicketStatus::OPEN);
                }),
            'in-progress' => Tab::make(__('filament/resources/support-ticket-resource.in-progress-tickets'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', SupportTicketStatus::IN_PROGRESS);
                }),
            'resolved' => Tab::make(__('filament/resources/support-ticket-resource.resolved-tickets'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', SupportTicketStatus::RESOLVED);
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
