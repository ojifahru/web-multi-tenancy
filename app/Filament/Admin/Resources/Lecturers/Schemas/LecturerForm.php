<?php

namespace App\Filament\Admin\Resources\Lecturers\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LecturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Dosen')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Dosen')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('nidn')
                            ->label('NIDN')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->inline(false)
                            ->default(true),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Foto Dosen')
                            ->disk('public')
                            ->collection('lecturer_image')
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->columnSpanFull(),
                        RichEditor::make('biography')
                            ->label('Biografi Dosen')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
