<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Categories\CategoryResource;
use App\Filament\Admin\Resources\Facilities\FacilityResource;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Facility;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;
use App\Models\News;
use App\Filament\Admin\Resources\News\NewsResource;
use App\Filament\Admin\Resources\Tags\TagResource;



class AdminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $tenant = Filament::getTenant();
        return [
            Stat::make('Total Berita', News::where('study_program_id', $tenant->id)->count())
                ->description('Jumlah berita yang telah dipublikasikan.')
                ->color('primary')
                ->icon('heroicon-o-document-text')
                ->url(NewsResource::getUrl()),
            Stat::make('Kategori Berita', Category::where('study_program_id', $tenant->id)->count())
                ->description('Jumlah kategori berita yang tersedia.')
                ->color('primary')
                ->icon('heroicon-o-folder')
                ->url(CategoryResource::getUrl()),
            Stat::make('Tag Berita', Tag::where('study_program_id', $tenant->id)->count())
                ->description('Jumlah tag berita yang tersedia.')
                ->color('primary')
                ->icon('heroicon-o-tag')
                ->url(TagResource::getUrl()),
            Stat::make('Fasilitas', Facility::where('study_program_id', $tenant->id)->count())
                ->description('Jumlah fasilitas yang tersedia.')
                ->color('primary')
                ->icon('heroicon-o-building-office')
                ->url(FacilityResource::getUrl()),
        ];
    }
}
