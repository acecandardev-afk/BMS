<?php

namespace App\Policies;

use App\Models\CertificateRequest;
use App\Models\User;

class CertificateRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_SIGNATORY]);
    }

    public function view(User $user, CertificateRequest $request): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_SIGNATORY])) {
            return true;
        }
        return $user->isResident() && $user->resident?->id === $request->resident_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_RESIDENT]);
    }

    public function update(User $user, CertificateRequest $request): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])) {
            return true;
        }
        // Resident can only update own pending requests
        return $user->isResident()
            && $user->resident?->id === $request->resident_id
            && $request->status === CertificateRequest::STATUS_PENDING;
    }

    public function approve(User $user, CertificateRequest $request): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SIGNATORY])
            && $request->status === CertificateRequest::STATUS_PENDING;
    }

    public function reject(User $user, CertificateRequest $request): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SIGNATORY])
            && $request->status === CertificateRequest::STATUS_PENDING;
    }

    public function release(User $user, CertificateRequest $request): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])
            && $request->status === CertificateRequest::STATUS_APPROVED;
    }

    public function print(User $user, CertificateRequest $request): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF])
            && in_array($request->status, [
                CertificateRequest::STATUS_APPROVED,
                CertificateRequest::STATUS_RELEASED,
            ]);
    }

    public function delete(User $user, CertificateRequest $request): bool
    {
        return $user->isAdmin();
    }
}