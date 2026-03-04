<?php

namespace App\Filament\Pages\Tenancy;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class EditStudyProgramProfile extends EditTenantProfile
{
    use HasPageShield, Translatable;

    public array $translatable = ['name', 'description', 'faculty', 'degree_level', 'accreditation', 'vision', 'mission', 'about', 'objectives', 'meta_title', 'meta_description', 'meta_keywords'];

    public ?string $activeLocale = null;

    public array $otherLocaleData = [];

    protected ?string $oldActiveLocale = null;

    protected static ?string $navigationLabel = 'Profil Program Studi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 100;

    public static function getLabel(): string
    {
        return 'Profil Program Studi';
    }

    public function mount(): void
    {
        $this->activeLocale = $this->getStoredActiveLocale() ?? $this->getFallbackActiveLocale();

        parent::mount();
    }

    public function getTranslatableLocales(): array
    {
        return filament('spatie-translatable')->getDefaultLocales() ?? [];
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    public function form(Schema $schema): Schema
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
                                ->Readonly()
                                ->label('Nama Program Studi')
                                ->required(),

                            TextInput::make('code')
                                ->Readonly()
                                ->label('Kode Program Studi')
                                ->required(),

                            TextInput::make('domain')
                                ->Readonly()
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
                            RichEditor::make('description')
                                ->label('Deskripsi Singkat')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->tenant;

        if ($record && method_exists($record, 'getTranslatableAttributes')) {
            $locale = $this->getActiveLocale();
            $useFallbackLocale = filament('spatie-translatable')->getUseFallbackLocale();

            foreach ($record->getTranslatableAttributes() as $attribute) {
                $data[$attribute] = $record->getTranslation($attribute, $locale, useFallbackLocale: $useFallbackLocale);
            }
        }

        foreach ($this->getRichEditorAttributes() as $attribute) {
            if (! array_key_exists($attribute, $data)) {
                continue;
            }

            $data[$attribute] = $this->normalizeRichEditorState($data[$attribute]);
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->getActiveLocale());
        }

        $record->update($data);

        return $record;
    }

    protected function getActiveLocale(): string
    {
        if ($this->activeLocale && in_array($this->activeLocale, $this->getTranslatableLocales(), true)) {
            return $this->activeLocale;
        }

        return $this->getFallbackActiveLocale();
    }

    protected function getFallbackActiveLocale(): string
    {
        $locales = $this->getTranslatableLocales();

        return $locales[0] ?? app()->getLocale();
    }

    protected function getStoredActiveLocale(): ?string
    {
        if (! filament('spatie-translatable')->getPersistLocale()) {
            return null;
        }

        $locale = session('spatie_translatable_active_locale');

        if ($locale && in_array($locale, $this->getTranslatableLocales(), true)) {
            return $locale;
        }

        return null;
    }

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(): void
    {
        if (filament('spatie-translatable')->getPersistLocale()) {
            session()->put('spatie_translatable_active_locale', $this->activeLocale);
        }

        $translatableAttributes = $this->getTranslatableAttributes();

        if (blank($translatableAttributes)) {
            $this->fillForm();

            return;
        }

        if (filled($this->oldActiveLocale)) {
            $this->otherLocaleData[$this->oldActiveLocale] = Arr::only(
                $this->form->getState(),
                $translatableAttributes
            );
        }

        $baseState = Arr::except($this->form->getState(), $translatableAttributes);
        $nextTranslatableState = $this->otherLocaleData[$this->activeLocale] ?? $this->getTenantTranslatableState($this->activeLocale);

        $this->resetValidation();
        $this->form->fill([
            ...$baseState,
            ...$nextTranslatableState,
        ]);

        unset($this->otherLocaleData[$this->activeLocale]);
    }

    protected function getTranslatableAttributes(): array
    {
        if ($this->tenant && method_exists($this->tenant, 'getTranslatableAttributes')) {
            return $this->tenant->getTranslatableAttributes();
        }

        return [];
    }

    protected function getTenantTranslatableState(string $locale): array
    {
        if (! $this->tenant || ! method_exists($this->tenant, 'getTranslation')) {
            return [];
        }

        $useFallbackLocale = filament('spatie-translatable')->getUseFallbackLocale();
        $state = [];

        foreach ($this->getTranslatableAttributes() as $attribute) {
            $state[$attribute] = $this->tenant->getTranslation($attribute, $locale, useFallbackLocale: $useFallbackLocale);
        }

        return $state;
    }

    protected function getRichEditorAttributes(): array
    {
        return [
            'description',
            'vision',
            'mission',
            'objectives',
            'about',
        ];
    }

    protected function normalizeRichEditorState(mixed $state): mixed
    {
        if ($state === null || $state === '' || $state === []) {
            return null;
        }

        if (is_array($state) && ! array_key_exists('content', $state)) {
            return null;
        }

        return $state;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }
}
