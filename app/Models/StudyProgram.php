<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLocalizedTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class StudyProgram extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia, LogsActivity, ResolvesLocalizedTranslations, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'domain',
        'description',
        'is_active',
        'faculty',
        'degree_level',
        'accreditation',
        'accreditation_file_path',
        'established_year',
        'student',
        'logo_path',
        'favicon_path',
        'banner_path',
        'email',
        'phone',
        'address',
        'vision',
        'mission',
        'about',
        'objectives',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'linkedin_link',
    ];

    public array $translatable = ['name', 'description', 'faculty', 'degree_level', 'accreditation', 'vision', 'mission', 'about', 'objectives', 'meta_title', 'meta_description', 'meta_keywords'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'faculty' => 'array',
            'degree_level' => 'array',
            'accreditation' => 'array',
            'vision' => 'array',
            'mission' => 'array',
            'about' => 'array',
            'objectives' => 'array',
            'meta_title' => 'array',
            'meta_description' => 'array',
            'meta_keywords' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The users that belong to the study program.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_study_program');
    }

    /**
     * The categories that belong to the study program.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * The tags that belong to the study program.
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * The facilities that belong to the study program.
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('study_program')
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->name ?? $this->code ?? $this->id;

                return match ($eventName) {
                    'created' => "Program Studi {$name} dibuat",
                    'updated' => "Program Studi {$name} diperbarui",
                    'deleted' => "Program Studi {$name} dihapus",
                    default => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('study_program_logo')->singleFile();
        $this->addMediaCollection('study_program_favicon')->singleFile();
        $this->addMediaCollection('study_program_banner')->singleFile();
        $this->addMediaCollection('study_program_accreditation_file')->singleFile();
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }
}
