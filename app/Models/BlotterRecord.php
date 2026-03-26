<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlotterRecord extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_OPEN       = 'open';
    const STATUS_ONGOING    = 'ongoing';
    const STATUS_RESOLVED   = 'resolved';
    const STATUS_ESCALATED  = 'escalated';
    const STATUS_CLOSED     = 'closed';

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_ONGOING,
        self::STATUS_RESOLVED,
        self::STATUS_ESCALATED,
        self::STATUS_CLOSED,
    ];

    const INCIDENT_TYPES = [
        'physical_assault'    => 'Physical Assault',
        'verbal_abuse'        => 'Verbal Abuse',
        'property_damage'     => 'Property Damage',
        'theft'               => 'Theft',
        'trespassing'         => 'Trespassing',
        'noise_complaint'     => 'Noise Complaint',
        'domestic_dispute'    => 'Domestic Dispute',
        'boundary_dispute'    => 'Boundary Dispute',
        'others'              => 'Others',
    ];

    protected $fillable = [
        'blotter_number',
        'complainant_id',
        'respondent_id',
        'complainant_name',
        'respondent_name',
        'incident_type',
        'incident_date',
        'incident_location',
        'narrative',
        'status',
        'resolution',
        'resolved_at',
        'encoded_by',
        'assigned_to',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolved_at'   => 'datetime',
    ];

    // --- Relationships ---

    public function complainant()
    {
        return $this->belongsTo(Resident::class, 'complainant_id');
    }

    public function respondent()
    {
        return $this->belongsTo(Resident::class, 'respondent_id');
    }

    public function encoder()
    {
        return $this->belongsTo(User::class, 'encoded_by');
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function hearings()
    {
        return $this->hasMany(BlotterHearing::class);
    }

    public function attachments()
    {
        return $this->hasMany(BlotterAttachment::class);
    }

    // --- Scopes ---

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('incident_type', $type);
    }

    // --- Accessors ---

    public function getIncidentTypeNameAttribute(): string
    {
        return self::INCIDENT_TYPES[$this->incident_type] ?? $this->incident_type;
    }

    public function getIsResolvedAttribute(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }
}