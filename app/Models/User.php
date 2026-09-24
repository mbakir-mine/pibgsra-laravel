<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'full_name', 'email', 'phone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function hasAnyPibgsraRole(array $roles): bool
    {
        return $this->roles()->whereIn('role', $roles)->exists();
    }

    public function primaryRole(): ?UserRole
    {
        return $this->roles()->with('school')->first();
    }

    public function isOwner(): bool
    {
        return $this->hasAnyPibgsraRole([UserRole::OWNER]);
    }

    public function isParent(): bool
    {
        return $this->hasAnyPibgsraRole([UserRole::PARENT]);
    }

    public function accessibleSchoolsQuery()
    {
        if ($this->isOwner() || $this->hasAnyPibgsraRole([UserRole::STATE_ADMIN])) {
            return School::query();
        }

        $districts = $this->roles()
            ->where('role', UserRole::DISTRICT_ADMIN)
            ->whereNotNull('district')
            ->pluck('district');

        if ($districts->isNotEmpty()) {
            return School::query()->whereIn('district', $districts);
        }

        $schoolIds = $this->roles()
            ->whereIn('role', [UserRole::SCHOOL_ADMIN, UserRole::HEADMASTER, UserRole::PARENT])
            ->whereNotNull('school_id')
            ->pluck('school_id');

        return School::query()->whereIn('id', $schoolIds);
    }

    public function accessibleSchoolIds(): Collection
    {
        return $this->accessibleSchoolsQuery()->pluck('id');
    }

    public function accessibleFamilyIds(): Collection
    {
        if ($this->isParent()) {
            return Family::whereHas('guardians', fn ($query) => $query->where('user_id', $this->id))
                ->pluck('families.id');
        }

        return Family::whereIn('school_id', $this->accessibleSchoolIds())->pluck('id');
    }

    public function platformScopeLabel(): string
    {
        $role = $this->primaryRole();

        if (! $role) {
            return 'Selangor';
        }

        if ($role->role === UserRole::PARENT) {
            return $role->school?->name ?? 'Nama Sekolah';
        }

        if ($role->role === UserRole::SCHOOL_ADMIN) {
            return trim('Selangor / '.($role->school?->district ?? 'Daerah').' / '.($role->school?->name ?? 'Nama Sekolah'));
        }

        if ($role->role === UserRole::DISTRICT_ADMIN) {
            return 'Selangor / '.($role->district ?? 'Daerah');
        }

        return 'Selangor';
    }

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
}
