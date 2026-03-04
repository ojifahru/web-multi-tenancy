<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentActivity;
use App\Filament\Widgets\SuperadminStats;
use App\Filament\Widgets\TenantOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            SuperadminStats::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TenantOverview::class,
            RecentActivity::class,
        ];
    }
}
