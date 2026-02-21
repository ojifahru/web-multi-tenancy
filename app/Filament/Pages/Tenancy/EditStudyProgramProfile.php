<?php

namespace App\Filament\Pages\Tenancy;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use UnitEnum;

class EditStudyProgramProfile extends EditTenantProfile
{
    use HasPageShield;

    protected static ?string $navigationLabel = 'Profil Program Studi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 100;

    public static function getLabel(): string
    {
        return 'Profil Program Studi';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                Step::make('Akademik')
                    ->icon('heroicon-o-academic-cap')
                    ->columns(2)
                    ->schema([
                        RichEditor::make('description')
                            ->label('Deskripsi Singkat')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ])
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
                Step::make('Kontak & Branding')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->schema([
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
                            ->image()
                            ->maxFiles(1),
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
                Step::make('Visi & Misi')
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
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
                Step::make('Profil')
                    ->icon('heroicon-o-document-text')
                    ->schema([
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
                Step::make('Media Sosial')
                    ->icon('heroicon-o-share')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_link')->label('Facebook')->url(),
                        TextInput::make('instagram_link')->label('Instagram')->url(),
                        TextInput::make('twitter_link')->label('Twitter')->url(),
                        TextInput::make('linkedin_link')->label('LinkedIn')->url(),
                        TextInput::make('youtube_link')->label('YouTube')->url(),
                    ]),
                Step::make('SEO')
                    ->icon('heroicon-o-magnifying-glass')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3),
                        TextInput::make('meta_keywords')
                            ->label('Meta Keywords'),
                    ]),
            ])->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                <x-filament::button
                    type="submit"
                    size="sm"
                >
                    Simpan Profil Program Studi
                </x-filament::button>
            BLADE))),
        ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
