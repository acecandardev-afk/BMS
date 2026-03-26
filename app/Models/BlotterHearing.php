<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterHearing extends Model
{
    use HasFactory;

    const OUTCOME_SETTLED    = 'settled';
    const OUTCOME_ONGOING    = 'ongoing';
    const OUTCOME_ESCALATED  = 'escalated';
    const OUTCOME_NO_SHOW    = 'no_show';

    const OUTCOMES = [
        self::OUTCOME_SETTLED   => 'Settled',
        self::OUTCOME_ONGOING   => 'Ongoing',
        self::OUTCOME_ESCALATED => 'Escalated',
        self::OUTCOME_NO_SHOW   => 'No Show',
    ];

    protected $fillable = [
        'blotter_record_id',
        'conducted_by',
        'hearing_date',
        'notes',
        'outcome',
        'next_hearing_date',
    ];

    protected $casts = [
        'hearing_date'      => 'datetime',
        'next_hearing_date' => 'datetime',
    ];

    // --- Relationships ---

    public function blotterRecord()
    {
        return $this->belongsTo(BlotterRecord::class);
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }

    // --- Accessors ---

    public function getOutcomeNameAttribute(): string
    {
        return self::OUTCOMES[$this->outcome] ?? $this->outcome;
    }
}