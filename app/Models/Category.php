<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Category extends Model
{
    use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'study_program_id',
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the study program that owns the category.
     */
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /**
     * Get the news that belong to the category.
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->name ?? $this->title ?? $this->id;

                return match ($eventName) {
                    'created' => "Kategori {$name} dibuat",
                    'updated' => "Kategori {$name} diperbarui",
                    'deleted' => "Kategori {$name} dihapus",
                    default   => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
