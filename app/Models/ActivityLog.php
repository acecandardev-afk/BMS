<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    const ACTION_CREATE  = 'create';
    const ACTION_UPDATE  = 'update';
    const ACTION_DELETE  = 'delete';
    const ACTION_RESTORE = 'restore';
    const ACTION_APPROVE = 'approve';
    const ACTION_REJECT  = 'reject';
    const ACTION_RELEASE = 'release';
    const ACTION_PRINT   = 'print';
    const ACTION_LOGIN   = 'login';
    const ACTION_LOGOUT  = 'logout';

    const ACTIONS = [
        self::ACTION_CREATE,
        self::ACTION_UPDATE,
        self::ACTION_DELETE,
        self::ACTION_RESTORE,
        self::ACTION_APPROVE,
        self::ACTION_REJECT,
        self::ACTION_RELEASE,
        self::ACTION_PRINT,
        self::ACTION_LOGIN,
        self::ACTION_LOGOUT,
    ];

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // No soft deletes — logs must be immutable

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // --- Scopes ---

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySubject($query, string $type, int $id)
    {
        return $query->where('subject_type', $type)
                     ->where('subject_id', $id);
    }

    public function scopeRecent($query, int $limit = 50)
    {
        return $query->latest()->limit($limit);
    }
}