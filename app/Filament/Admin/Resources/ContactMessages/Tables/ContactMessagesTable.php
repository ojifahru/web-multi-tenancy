<?php

namespace App\Filament\Admin\Resources\ContactMessages\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->searchable(['name', 'email', 'phone'])
                    ->sortable()
                    ->weight('semibold')
                    ->description(function ($record): string {
                        if (filled($record->phone)) {
                            return "{$record->email} • {$record->phone}";
                        }

                        return "{$record->email} • Tanpa nomor telepon";
                    })
                    ->limit(40),
                TextColumn::make('subject')
                    ->label('Subjek & Pesan')
                    ->searchable(['subject', 'message'])
                    ->sortable()
                    ->wrap()
                    ->lineClamp(2)
                    ->description(fn ($record): string => Str::limit($record->message, 90))
                    ->limit(70),
                TextColumn::make('is_read')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-envelope')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sudah Dibaca' : 'Belum Dibaca'),
                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip('d M Y, H:i'),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->trueLabel('Sudah Dibaca')
                    ->falseLabel('Belum Dibaca')
                    ->placeholder('Semua status'),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat Detail'),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Cari pengirim, subjek, atau isi pesan...')
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading('Belum ada pesan kontak')
            ->emptyStateDescription('Pesan dari formulir publik akan muncul di sini.')
            ->recordClasses(fn ($record): string => $record->is_read ? '' : 'bg-warning-50/30 dark:bg-warning-500/5')
            ->striped();
    }
}
