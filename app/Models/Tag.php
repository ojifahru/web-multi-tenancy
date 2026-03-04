<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations, LogsActivity, ResolvesLocalizedTranslations, SoftDeletes;

    protected $fillable = ['study_program_id', 'name', 'slug'];

    public array $translatable = ['name', 'slug'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'slug' => 'array',
        ];
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function news()
    {
        return $this->belongsToMany(News::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('tag')
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->name ?? $this->title ?? $this->id;

                return match ($eventName) {
                    'created' => "Tag {$name} dibuat",
                    'updated' => "Tag {$name} diperbarui",
                    'deleted' => "Tag {$name} dihapus",
                    default => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
