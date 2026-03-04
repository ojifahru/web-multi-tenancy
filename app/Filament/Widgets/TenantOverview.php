<?php

namespace App\Filament\Widgets;

use App\Models\StudyProgram;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TenantOverview extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => StudyProgram::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Program Studi')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
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
