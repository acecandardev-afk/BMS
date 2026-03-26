<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an action.
     *
     * @param string      $action      ActivityLog::ACTION_* constant
     * @param string      $description Human-readable description
     * @param Model|null  $subject     The affected model instance
     * @param array|null  $oldValues   Previous values (for updates)
     * @param array|null  $newValues   New values (for updates/creates)
     * @param int|null    $userId      Override actor (defaults to authenticated user)
     */
    public static function log(
        string $action,
        string $description,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'      => $userId ?? Auth::id(),
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'description'  => $description,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
        ]);
    }

    /**
     * Log a create action.
     */
    public static function logCreate(Model $subject, string $description, ?array $newValues = null): ActivityLog
    {
        return self::log(
            ActivityLog::ACTION_CREATE,
            $description,
            $subject,
            null,
            $newValues ?? $subject->toArray()
        );
    }

    /**
     * Log an update action with automatic diff.
     */
    public static function logUpdate(Model $subject, string $description, array $oldValues): ActivityLog
    {
        $newValues = array_intersect_key($subject->toArray(), $oldValues);

        return self::log(
            ActivityLog::ACTION_UPDATE,
            $description,
            $subject,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log a delete action.
     */
    public static function logDelete(Model $subject, string $description): ActivityLog
    {
        return self::log(
            ActivityLog::ACTION_DELETE,
            $description,
            $subject,
            $subject->toArray(),
            null
        );
    }

    /**
     * Log an approval action.
     */
    public static function logApprove(Model $subject, string $description): ActivityLog
    {
        return self::log(ActivityLog::ACTION_APPROVE, $description, $subject);
    }

    /**
     * Log a rejection action.
     */
    public static function logReject(Model $subject, string $description): ActivityLog
    {
        return self::log(ActivityLog::ACTION_REJECT, $description, $subject);
    }

    /**
     * Log a print action.
     */
    public static function logPrint(Model $subject, string $description): ActivityLog
    {
        return self::log(ActivityLog::ACTION_PRINT, $description, $subject);
    }

    /**
     * Log login/logout.
     */
    public static function logAuth(string $action, int $userId, string $description): ActivityLog
    {
        return self::log($action, $description, null, null, null, $userId);
    }
}