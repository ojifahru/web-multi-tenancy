<?php

namespace App\Filament\Admin\Resources\News\Pages;

use App\Filament\Admin\Resources\News\NewsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\News;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'published' => Tab::make()
                ->icon('heroicon-o-check-badge')
                ->badge(News::query()->where('status', 'published')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'published')),
            'draft' => Tab::make()
                ->icon('heroicon-o-pencil-square')
                ->badge(News::query()->where('status', 'draft')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'draft')),
            'archived' => Tab::make()
                ->icon('heroicon-o-archive-box-x-mark')
                ->badge(News::query()->where('status', 'archived')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'archived')),
        ];
    }
}
