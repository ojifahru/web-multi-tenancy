<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan')
                    ->description('Informasi utama pesan yang masuk.')
                    ->icon('heroicon-o-envelope-open')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('is_read')
                            ->label('Status')
                            ->badge()
                            ->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-envelope')
                            ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Sudah Dibaca' : 'Belum Dibaca'),
                        TextEntry::make('created_at')
                            ->label('Diterima')
                            ->dateTime('d M Y, H:i')
                            ->sinceTooltip(),
                        TextEntry::make('studyProgram.name')
                            ->label('Program Studi')
                            ->placeholder('-')
                            ->badge()
                            ->color('primary'),
                    ]),
                Section::make('Data Pengirim')
                    ->description('Kontak pengirim pesan.')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama')
                            ->weight('semibold'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->icon('heroicon-o-envelope')
                            ->color('primary'),
                        TextEntry::make('phone')
                            ->label('Telepon')
                            ->placeholder('-')
                            ->copyable()
                            ->icon('heroicon-o-phone'),
                    ]),
                Section::make('Isi Pesan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Subjek')
                            ->weight('semibold')
                            ->color('primary'),
                        TextEntry::make('message')
                            ->label('Pesan')
                            ->columnSpanFull()
                            ->formatStateUsing(fn (?string $state): string => nl2br(e((string) $state)))
                            ->html(),
                    ]),
                Section::make('Metadata Teknis')
                    ->description('Informasi teknis untuk audit dan troubleshooting.')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('ip_address')
                            ->label('IP Address')
                            ->placeholder('-')
                            ->badge()
                            ->copyable(),
                        TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->placeholder('-')
                            ->columnSpanFull()
                            ->copyable()
                            ->formatStateUsing(fn (?string $state): string => Str::limit((string) $state, 300))
                            ->lineClamp(2),
                    ]),
            ]);
    }
}
