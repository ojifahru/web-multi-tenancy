<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Log')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('log_name')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('event')
                            ->badge()
                            ->colors([
                                'primary' => 'created',
                                'info' => 'updated',
                                'danger'  => 'deleted',
                            ]),

                        TextEntry::make('description')
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->dateTime('d M Y H:i:s'),
                    ]),

                Section::make('Subjek & Pelaku')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('subject_type')
                            ->formatStateUsing(fn($state) =>
                            $state ? class_basename($state) : '-'),

                        TextEntry::make('subject_id'),

                        TextEntry::make('causer.name')
                            ->label('Pelaku')
                            ->default('System'),

                        TextEntry::make('studyProgram.name')
                            ->label('Program Studi'),
                    ]),

                Section::make('Perubahan Data')
                    ->schema([
                        RepeatableEntry::make('changes')
                            ->state(function ($record) {

                                $old = $record->properties['old'] ?? [];
                                $new = $record->properties['attributes'] ?? [];

                                $fields = collect($new)
                                    ->keys()
                                    ->merge(collect($old)->keys())
                                    ->unique();

                                return $fields->map(function ($field) use ($old, $new) {
                                    return [
                                        'field' => $field,
                                        'old'   => $old[$field] ?? '-',
                                        'new'   => $new[$field] ?? '-',
                                    ];
                                })->values()->toArray();
                            })
                            ->schema([
                                Grid::make(3)->schema([
                                    TextEntry::make('field')->label('Field'),
                                    TextEntry::make('old')->label('Old'),
                                    TextEntry::make('new')->label('New'),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
