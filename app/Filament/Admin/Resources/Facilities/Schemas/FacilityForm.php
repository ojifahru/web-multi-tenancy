<?php

namespace App\Filament\Admin\Resources\Facilities\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;

class FacilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Fasilitas')
                ->columns(2)
                ->columnSpanFull()
                ->components([
                    TextInput::make('name')
                        ->label('Nama Fasilitas')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255),
                    SpatieMediaLibraryFileUpload::make('image')
                        ->collection('facility_image')
                        ->label('Gambar Fasilitas')
                        ->image()
                        ->required()
                        ->maxFiles(1)
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:9'])
                        ->columnSpanFull(),
                    RichEditor::make('description')
                        ->label('Deskripsi Fasilitas')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
