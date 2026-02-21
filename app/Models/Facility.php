<?php

namespace App\Models;

use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Facility extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'study_program_id',
        'name',
        'slug',
        'description',
    ];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('facility_image')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Thumbnail kecil untuk admin table
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 100, 100)
            ->nonQueued();

        // Preview untuk halaman index (card)
        $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 400, 225) // 16:9
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
                    'created' => "Fasilitas {$name} dibuat",
                    'updated' => "Fasilitas {$name} diperbarui",
                    'deleted' => "Fasilitas {$name} dihapus",
                    default   => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
