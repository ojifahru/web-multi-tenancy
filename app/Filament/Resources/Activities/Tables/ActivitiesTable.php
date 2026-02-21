<?php

namespace App\Filament\Resources\Activities\Tables;

use App\Models\Activity;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->colors([
                        'primary' => 'created',
                        'info' => 'updated',
                        'danger' => 'deleted',
                    ])
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(80)
                    ->wrap(),
                TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('causer.name')
                    ->label('Pelaku')
                    ->placeholder('-'),
                TextColumn::make('subject_type')
                    ->label('Subjek')
                    ->formatStateUsing(fn(?string $state): string => $state ? class_basename($state) : '-')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Log Name')
                    ->options(fn(): array => Activity::query()
                        ->whereNotNull('log_name')
                        ->distinct('log_name')
                        ->orderBy('log_name')
                        ->pluck('log_name', 'log_name')
                        ->all()),
                SelectFilter::make('event')
                    ->options(fn(): array => Activity::query()
                        ->whereNotNull('event')
                        ->distinct('event')
                        ->orderBy('event')
                        ->pluck('event', 'event')
                        ->all()),
                SelectFilter::make('study_program_id')
                    ->relationship('studyProgram', 'name')
                    ->label('Program Studi'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
