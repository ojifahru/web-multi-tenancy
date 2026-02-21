<?php

namespace App\Filament\Widgets;

use App\Models\StudyProgram;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class SuperadminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pengguna', User::count())
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Program Studi', StudyProgram::count())
                ->icon('heroicon-o-building-library')
                ->color('info'),

            Stat::make('Peran', Role::count())
                ->icon('heroicon-o-shield-check')
                ->color('gray'),

            Stat::make(
                'Aktivitas Hari Ini',
                Activity::whereDate('created_at', today())->count()
            )
                ->icon('heroicon-o-clock')
                ->color('gray'),
        ];
    }
}
