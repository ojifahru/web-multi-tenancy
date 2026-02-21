<?php

namespace App\Filament\Widgets;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class RecentActivity extends TableWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';
    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Activity::query()->latest()->limit(10))
            ->columns([
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
