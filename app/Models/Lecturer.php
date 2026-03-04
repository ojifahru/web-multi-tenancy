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

class Lecturer extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, LogsActivity, ResolvesLocalizedTranslations, SoftDeletes;

    protected $fillable = [
        'study_program_id',
        'nidn',
        'name',
        'slug',
        'email',
        'phone',
        'biography',
        'is_active',
    ];

    public array $translatable = ['biography'];

    protected function casts(): array
    {
        return [
            'biography' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lecturer_image')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 100, 100)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 400, 400)
            ->nonQueued();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->name ?? $this->title ?? $this->id;

                return match ($eventName) {
                    'created' => "Dosen {$name} dibuat",
                    'updated' => "Dosen {$name} diperbarui",
                    'deleted' => "Dosen {$name} dihapus",
                    default => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
