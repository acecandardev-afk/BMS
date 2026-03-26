<?php

namespace App\Policies;

use App\Models\Legislation;
use App\Models\User;

class LegislationPolicy
{
    public function viewAny(User $user): bool
    {
        // All authenticated users can browse legislation
        return true;
    }

    public function view(User $user, Legislation $legislation): bool
    {
        // Drafts only visible to admin/staff
        if ($legislation->status === Legislation::STATUS_DRAFT) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function update(User $user, Legislation $legislation): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function delete(User $user, Legislation $legislation): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Legislation $legislation): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Legislation $legislation): bool
    {
        return $user->isAdmin();
    }
}