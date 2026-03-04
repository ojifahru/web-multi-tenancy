<?php

namespace App\Filament\Admin\Resources\ContactMessages\Pages;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pesan')
                ->icon('heroicon-o-inbox')
                ->modifyQueryUsing(fn (Builder $query) => $query)
                ->badge(ContactMessage::query()->count())
                ->badgeColor('primary'),
            'unread' => Tab::make('Belum Dibaca')
                ->icon('heroicon-o-envelope')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_read', false))
                ->badge(ContactMessage::query()->where('is_read', false)->count())
                ->badgeColor('danger'),
            'read' => Tab::make('Sudah Dibaca')
                ->icon('heroicon-o-envelope-open')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_read', true))
                ->badge(ContactMessage::query()->where('is_read', true)->count())
                ->badgeColor('success'),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'unread';
    }
}
