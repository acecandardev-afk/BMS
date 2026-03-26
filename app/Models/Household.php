<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_number',
        'head_resident_id',
        'zone',
        'address',
        'remarks',
    ];

    // --- Relationships ---

    public function members()
    {
        return $this->hasMany(Resident::class);
    }

    public function head()
    {
        return $this->belongsTo(Resident::class, 'head_resident_id');
    }

    // --- Scopes ---

    public function scopeByZone($query, string $zone)
    {
        return $query->where('zone', $zone);
    }

    // --- Accessors ---

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }
}