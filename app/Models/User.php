<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const ROLE_ADMIN     = 'admin';
    const ROLE_STAFF     = 'staff';
    const ROLE_SIGNATORY = 'signatory';
    const ROLE_RESIDENT  = 'resident';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_STAFF,
        self::ROLE_SIGNATORY,
        self::ROLE_RESIDENT,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'supabase_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // --- Role Helpers ---

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isSignatory(): bool
    {
        return $this->role === self::ROLE_SIGNATORY;
    }

    public function isResident(): bool
    {
        return $this->role === self::ROLE_RESIDENT;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // --- Relationships ---

    public function resident()
    {
        return $this->hasOne(Resident::class);
    }

    /**
     * Ensure this account has a linked resident record (self-registration and legacy accounts).
     */
    public function ensureResidentProfile(): Resident
    {
        if (! $this->isResident()) {
            throw new \LogicException('Only resident accounts have a resident profile.');
        }

        $existing = $this->relationLoaded('resident')
            ? $this->resident
            : $this->resident()->first();

        if ($existing) {
            return $existing;
        }

        [$first, $middle, $last] = self::splitDisplayName($this->name);

        return $this->resident()->create([
            'first_name'  => $first,
            'middle_name' => $middle,
            'last_name'   => $last,
            'email'       => $this->email,
        ]);
    }

    /**
     * @return array{0: string, 1: ?string, 2: string}
     */
    private static function splitDisplayName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($parts === []) {
            return ['Resident', null, ''];
        }

        if (count($parts) === 1) {
            return [$parts[0], null, ''];
        }

        $first = array_shift($parts);
        $last  = array_pop($parts);

        return [$first, $parts !== [] ? implode(' ', $parts) : null, $last];
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public function sentChatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedChatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function barangayEvents()
    {
        return $this->hasMany(BarangayEvent::class);
    }

    public function isOfficeUser(): bool
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_STAFF, self::ROLE_SIGNATORY]);
    }

    /**
     * Whether this user may start or send a message to the other user.
     */
    public function canChatWith(User $other): bool
    {
        if ($this->id === $other->id || ! $other->is_active) {
            return false;
        }

        if ($this->isOfficeUser()) {
            return true;
        }

        if ($this->isResident()) {
            return $other->isOfficeUser();
        }

        return false;
    }

    /**
     * Whether the two accounts may open a conversation (including an empty thread).
     */
    public function canCommunicateWith(User $other): bool
    {
        return $this->canChatWith($other) || $other->canChatWith($this);
    }
}