<?php

namespace App\Providers;

use App\Models\BlotterRecord;
use App\Models\CertificateRequest;
use App\Models\Legislation;
use App\Models\Resident;
use App\Models\User;
use App\Policies\BlotterRecordPolicy;
use App\Policies\CertificateRequestPolicy;
use App\Policies\LegislationPolicy;
use App\Policies\ResidentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Resident::class           => ResidentPolicy::class,
        CertificateRequest::class => CertificateRequestPolicy::class,
        BlotterRecord::class      => BlotterRecordPolicy::class,
        Legislation::class        => LegislationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Super gate — admin bypasses all
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Module-level gates
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-residents', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        });

        Gate::define('manage-certificates', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        });

        Gate::define('approve-certificates', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SIGNATORY]);
        });

        Gate::define('manage-blotter', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        });

        Gate::define('manage-legislation', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        });

        Gate::define('view-audit-logs', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-reports', function (User $user) {
            return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_STAFF]);
        });

        Gate::define('request-certificate', function (User $user) {
            return $user->isResident();
        });
    }
}