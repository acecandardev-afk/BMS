<?php

namespace App\Policies;

use App\Models\Resident;
use App\Models\User;

class ResidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function view(User $user, Resident $resident): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])) {
            return true;
        }
        return $user->isResident() && $user->resident?->id === $resident->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function update(User $user, Resident $resident): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])) {
            return true;
        }
        return $user->isResident() && $user->resident?->id === $resident->id;
    }

    public function delete(User $user, Resident $resident): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Resident $resident): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Resident $resident): bool
    {
        return $user->isAdmin();
    }
}