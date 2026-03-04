<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, LogsActivity, ResolvesLocalizedTranslations, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'author_id',
        'category_id',
        'published_at',
        'status',
        'is_featured',
    ];

    public array $translatable = ['title', 'slug', 'excerpt', 'content'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'slug' => 'array',
            'excerpt' => 'array',
            'content' => 'array',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('news_image')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 100, 100)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 400, 225)
            ->nonQueued();
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('news')
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->title ?? $this->name ?? $this->id;

                return match ($eventName) {
                    'created' => "Berita {$name} dibuat",
                    'updated' => "Berita {$name} diperbarui",
                    'deleted' => "Berita {$name} dihapus",
                    default => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
