<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Resources\Facilities\FacilityResource;
use App\Filament\Admin\Resources\News\NewsResource;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createNews')
                ->label('Tambah Berita')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(NewsResource::getUrl('create')),

            Action::make('createFacility')
                ->label('Tambah Fasilitas')
                ->icon('heroicon-o-building-office')
                ->color('success')
                ->url(FacilityResource::getUrl('create')),
        ];
    }
}
