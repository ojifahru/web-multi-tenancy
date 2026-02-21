<?php

namespace App\Filament\Resources\StudyPrograms\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;

class StudyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Profil Program Studi')
                ->columnSpanFull()
                ->tabs([
                    /* =========================
                     * IDENTITAS PROGRAM STUDI
                     * ========================= */
                    Tab::make('Identitas')
                        ->icon('heroicon-o-identification')
                        ->columns(2)
                        ->components([
                            TextInput::make('name')
                                ->label('Nama Program Studi')
                                ->required(),

                            TextInput::make('code')
                                ->label('Kode Program Studi')
                                ->required(),

                            TextInput::make('domain')
                                ->label('Domain Program Studi')
                                ->required()
                                ->helperText('Contoh: informatika.univbatam.ac.id')
                                ->columnSpanFull(),
                        ]),
                    /* =========================
                     * AKADEMIK & AKREDITASI
                     * ========================= */
                    Tab::make('Akademik')
                        ->icon('heroicon-o-academic-cap')
                        ->columns(2)
                        ->components([
                            Textarea::make('description')
                                ->label('Deskripsi Singkat')
                                ->rows(3)
                                ->columnSpanFull(),
                            TextInput::make('faculty')
                                ->label('Fakultas'),

                            Select::make('degree_level')
                                ->label('Jenjang')
                                ->options([
                                    'D3' => 'D3',
                                    'S1' => 'S1',
                                    'S2' => 'S2',
                                    'S3' => 'S3',
                                ]),

                            TextInput::make('accreditation')
                                ->label('Status Akreditasi')
                                ->placeholder('Unggul / A / Baik Sekali'),

                            TextInput::make('established_year')
                                ->label('Tahun Berdiri')
                                ->numeric()
                                ->minValue(1900)
                                ->maxValue(now()->year),
                            TextInput::make('student')
                                ->label('Jumlah Mahasiswa')
                                ->numeric()
                                ->minValue(0),

                            SpatieMediaLibraryFileUpload::make('accreditation_file')
                                ->label('File Akreditasi (PDF)')
                                ->collection('study_program_accreditation_file')
                                ->maxFiles(1)
                                ->downloadable()
                                ->openable()
                                ->acceptedFileTypes(['application/pdf']),
                        ]),

                    /* =========================
                     * KONTAK & BRANDING
                     * ========================= */
                    Tab::make('Kontak & Branding')
                        ->icon('heroicon-o-phone')
                        ->columns(2)
                        ->components([
                            SpatieMediaLibraryFileUpload::make('logo')
                                ->label('Logo')
                                ->collection('study_program_logo')
                                ->image()
                                ->maxFiles(1)
                                ->imageEditor()
                                ->columnSpanFull(),
                            SpatieMediaLibraryFileUpload::make('favicon')
                                ->label('Favicon')
                                ->collection('study_program_favicon')
                                ->image(),
                            SpatieMediaLibraryFileUpload::make('banner')
                                ->label('Banner')
                                ->collection('study_program_banner')
                                ->image()
                                ->maxFiles(1)
                                ->imageEditor()
                                ->imageEditorAspectRatios(['16:9']),

                            TextInput::make('email')
                                ->email(),

                            TextInput::make('phone'),

                            Textarea::make('address')
                                ->columnSpanFull(),
                        ]),

                    /* =========================
                     * VISI, MISI & TUJUAN
                     * ========================= */
                    Tab::make('Visi & Misi')
                        ->icon('heroicon-o-light-bulb')
                        ->components([
                            RichEditor::make('vision')
                                ->label('Visi')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                    ['undo', 'redo'],
                                ])
                                ->columnSpanFull(),
                            RichEditor::make('mission')
                                ->label('Misi')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                    ['undo', 'redo'],
                                ])
                                ->columnSpanFull(),

                            RichEditor::make('objectives')
                                ->label('Tujuan Program Studi')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                    ['undo', 'redo'],
                                ])
                                ->columnSpanFull(),
                        ]),

                    /* =========================
                     * PROFIL LENGKAP
                     * ========================= */
                    Tab::make('Profil')
                        ->icon('heroicon-o-document-text')
                        ->components([
                            RichEditor::make('about')
                                ->label('Tentang Program Studi')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                    ['undo', 'redo'],
                                ])
                                ->columnSpanFull(),
                        ]),

                    /* =========================
                     * MEDIA SOSIAL
                     * ========================= */
                    Tab::make('Media Sosial')
                        ->icon('heroicon-o-share')
                        ->columns(2)
                        ->components([
                            TextInput::make('facebook_link')->label('Facebook'),
                            TextInput::make('instagram_link')->label('Instagram'),
                            TextInput::make('twitter_link')->label('Twitter'),
                            TextInput::make('linkedin_link')->label('LinkedIn'),
                            TextInput::make('youtube_link')->label('YouTube'),
                        ]),

                    /* =========================
                     * SEO
                     * ========================= */
                    Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->components([
                            TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(60),

                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3),

                            TextInput::make('meta_keywords')
                                ->label('Meta Keywords'),
                        ]),
                ]),
        ]);
    }
}
