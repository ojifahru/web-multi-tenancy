<?php

namespace App\Filament\Admin\Resources\News\Schemas;

use App\Models\Tag;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'xl' => 3,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Konten Berita')
                            ->description('Tulis judul, ringkasan, media utama, dan isi berita.')
                            ->icon('heroicon-o-newspaper')
                            ->iconColor('primary')
                            ->columnSpan([
                                'xl' => 2,
                            ])
                            ->columns(2)
                            ->components([
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->alphaDash()
                                    ->dehydrateStateUsing(fn(?string $state): ?string => filled($state) ? Str::slug($state) : null)
                                    ->columnSpanFull()
                                    ->helperText('Dibuat otomatis dari judul, tetap bisa diubah manual.'),

                                Textarea::make('excerpt')
                                    ->label('Ringkasan')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                RichEditor::make('content')
                                    ->label('Konten')
                                    ->required()
                                    ->toolbarButtons([
                                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                        ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                        ['table', 'attachFiles'],
                                        ['undo', 'redo'],
                                    ])
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('news/attachments')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg'])
                                    ->fileAttachmentsMaxSize(2048) // 2MB
                                    ->resizableImages(),
                            ]),

                        Grid::make(1)
                            ->schema([
                                Section::make('Publikasi')
                                    ->description('Atur status tayang dan prioritas berita.')
                                    ->icon('heroicon-o-check-badge')
                                    ->iconColor('success')
                                    ->compact()
                                    ->secondary()
                                    ->columns(1)
                                    ->components([
                                        Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                'archived' => 'Archived',
                                            ])
                                            ->default('draft')
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                                if ($state === 'draft') {
                                                    if (filled($get('published_at'))) {
                                                        $set('published_at', null);
                                                    }

                                                    return;
                                                }

                                                if ($state === 'published' && blank($get('published_at'))) {
                                                    $set('published_at', now());
                                                }
                                            })
                                            ->required(),

                                        DateTimePicker::make('published_at')
                                            ->label('Tanggal Publikasi')
                                            ->seconds(false)
                                            ->disabled(fn(Get $get): bool => $get('status') !== 'published')
                                            ->required(fn(Get $get): bool => $get('status') === 'published')
                                            ->helperText('Jika kosong, berita dianggap siap dipublikasikan sekarang.'),

                                        Toggle::make('is_featured')
                                            ->label('Featured')
                                            ->default(false)
                                            ->inline(false),
                                    ]),

                                Section::make('Kategori & Tag')
                                    ->description('Kelompokkan berita agar lebih mudah ditemukan.')
                                    ->icon('heroicon-o-tag')
                                    ->iconColor('info')
                                    ->compact()
                                    ->secondary()
                                    ->columns(1)
                                    ->components([
                                        Select::make('category_id')
                                            ->label('Kategori')
                                            ->required()
                                            ->relationship(
                                                'category',
                                                'name',
                                                modifyQueryUsing: fn(Builder $query): Builder => $query->when(
                                                    Filament::getTenant()?->getKey(),
                                                    fn(Builder $tenantQuery, int|string $tenantId): Builder => $tenantQuery->where('study_program_id', $tenantId)
                                                )
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Select::make('tags')
                                            ->relationship(
                                                'tags',
                                                'name',
                                                modifyQueryUsing: fn(Builder $query): Builder => $query->when(
                                                    Filament::getTenant()?->getKey(),
                                                    fn(Builder $tenantQuery, int|string $tenantId): Builder => $tenantQuery->where('study_program_id', $tenantId)
                                                )
                                            )
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                                TextInput::make('slug')
                                                    ->required()
                                                    ->alphaDash()
                                                    ->dehydrateStateUsing(fn(?string $state): ?string => filled($state) ? Str::slug($state) : null),
                                            ])
                                            ->createOptionUsing(function (array $data): int {
                                                $tenantId = Filament::getTenant()?->getKey();

                                                $tag = Tag::query()->create([
                                                    'study_program_id' => $tenantId,
                                                    'name' => $data['name'],
                                                    'slug' => $data['slug'],
                                                ]);

                                                return (int) $tag->getKey();
                                            }),
                                    ]),

                                Section::make('Media Utama')
                                    ->description('Unggah gambar sampul berita.')
                                    ->icon('heroicon-o-photo')
                                    ->iconColor('warning')
                                    ->compact()
                                    ->secondary()
                                    ->components([
                                        SpatieMediaLibraryFileUpload::make('image')
                                            ->label('Gambar')
                                            ->disk('public')
                                            ->collection('news_image')
                                            ->image()
                                            ->required(fn(?string $operation): bool => $operation === 'create')
                                            ->maxFiles(1)
                                            ->maxSize(2048) // 2MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['16:9'])
                                            ->helperText('Rasio disarankan 16:9 dengan ukuran maksimal 2 MB.'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
