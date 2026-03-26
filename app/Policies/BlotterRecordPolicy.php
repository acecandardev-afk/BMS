<?php

namespace App\Policies;

use App\Models\BlotterRecord;
use App\Models\User;

class BlotterRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function view(User $user, BlotterRecord $blotter): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])) {
            return true;
        }
        // Resident can view if they are complainant or respondent
        return $user->isResident() && (
            $user->resident?->id === $blotter->complainant_id ||
            $user->resident?->id === $blotter->respondent_id
        );
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function update(User $user, BlotterRecord $blotter): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])
            && !$blotter->getIsResolvedAttribute();
    }

    public function addHearing(User $user, BlotterRecord $blotter): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])
            && !$blotter->getIsResolvedAttribute();
    }

    public function addAttachment(User $user, BlotterRecord $blotter): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
    }

    public function resolve(User $user, BlotterRecord $blotter): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])
            && !$blotter->getIsResolvedAttribute();
    }

    public function delete(User $user, BlotterRecord $blotter): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, BlotterRecord $blotter): bool
    {
        return $user->isAdmin();
    }
}