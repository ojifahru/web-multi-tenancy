<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The study programs that belong to the user.
     */
    public function studyPrograms(): BelongsToMany
    {
        return $this->belongsToMany(StudyProgram::class, 'user_study_program');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('admin') || $this->hasRole('super_admin');
        }

        if ($panel->getId() === 'superadmin') {
            return $this->hasRole('super_admin');
        }

        return false;
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->hasRole('super_admin')) {
            return StudyProgram::query()->get();
        }

        return $this->studyPrograms()->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->studyPrograms()
            ->whereKey($tenant->getKey())
            ->exists();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {

                $name = $this->name ?? $this->email ?? $this->id;

                return match ($eventName) {
                    'created' => "Pengguna {$name} dibuat",
                    'updated' => "Pengguna {$name} diperbarui",
                    'deleted' => "Pengguna {$name} dihapus",
                    default   => $eventName,
                };
            });
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->study_program_id = filament()->getTenant()?->id;
    }
}
