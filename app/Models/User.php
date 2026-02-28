<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'profile_photo_path',
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
            'roles' => 'array',
            'permissions' => 'array',
        ];
    }

    public function vcards(): HasMany
    {
        return $this->hasMany(Vcard::class);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        $userRoles = collect($this->roles ?? [])->map(fn ($role) => strtolower((string) $role));

        foreach ($roles as $role) {
            if ($userRoles->contains(strtolower((string) $role))) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(string|array $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];
        $current = collect($this->roles ?? []);

        $this->roles = $current
            ->merge($roles)
            ->filter()
            ->map(fn ($role) => (string) $role)
            ->unique()
            ->values()
            ->all();

        $this->save();

        return $this;
    }

    public function syncRoles(array $roles): self
    {
        $this->roles = collect($roles)
            ->filter()
            ->map(fn ($role) => (string) $role)
            ->unique()
            ->values()
            ->all();

        $this->save();

        return $this;
    }

    public function getRoleNames(): array
    {
        return $this->roles ?? [];
    }

    public function getAllPermissions(): array
    {
        return $this->permissions ?? [];
    }
}
