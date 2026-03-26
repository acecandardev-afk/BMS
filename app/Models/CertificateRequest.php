<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateRequest extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RELEASED = 'released';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_RELEASED,
    ];

    const TYPE_BARANGAY_CLEARANCE    = 'barangay_clearance';
    const TYPE_CERTIFICATE_RESIDENCY = 'certificate_of_residency';
    const TYPE_CERTIFICATE_INDIGENCY = 'certificate_of_indigency';
    const TYPE_BUSINESS_CLEARANCE    = 'business_clearance';
    const TYPE_CERTIFICATE_GOOD_MORAL = 'certificate_of_good_moral';

    const TYPES = [
        self::TYPE_BARANGAY_CLEARANCE     => 'Barangay Clearance',
        self::TYPE_CERTIFICATE_RESIDENCY  => 'Certificate of Residency',
        self::TYPE_CERTIFICATE_INDIGENCY  => 'Certificate of Indigency',
        self::TYPE_BUSINESS_CLEARANCE     => 'Business Clearance',
        self::TYPE_CERTIFICATE_GOOD_MORAL => 'Certificate of Good Moral',
    ];

    /** Types available when residents submit their own request (my/requests/create). */
    const TYPES_RESIDENT_SELF_SERVICE = [
        self::TYPE_BARANGAY_CLEARANCE    => 'Barangay Clearance',
        self::TYPE_CERTIFICATE_RESIDENCY => 'Certificate of Residency',
        self::TYPE_CERTIFICATE_INDIGENCY => 'Certificate of Indigency',
        self::TYPE_BUSINESS_CLEARANCE    => 'Business Clearance',
    ];

    protected $fillable = [
        'resident_id',
        'requested_by',
        'processed_by',
        'signatory_id',
        'certificate_type',
        'purpose',
        'status',
        'or_number',
        'fee',
        'requirements_checklist',
        'remarks',
        'approved_at',
        'rejected_at',
        'released_at',
        'printed_at',
    ];

    protected $casts = [
        'requirements_checklist' => 'array',
        'fee'                    => 'decimal:2',
        'approved_at'            => 'datetime',
        'rejected_at'            => 'datetime',
        'released_at'            => 'datetime',
        'printed_at'             => 'datetime',
    ];

    // --- Relationships ---

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function signatory()
    {
        return $this->belongsTo(User::class, 'signatory_id');
    }

    // --- Accessors ---

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->certificate_type] ?? $this->certificate_type;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    // --- Scopes ---

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('certificate_type', $type);
    }
}