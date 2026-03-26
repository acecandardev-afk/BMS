<?php

namespace App\Models;

use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'household_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'birthplace',
        'gender',
        'civil_status',
        'nationality',
        'religion',
        'occupation',
        'zone',
        'address',
        'contact_number',
        'email',
        'voter_status',
        'is_indigenous',
        'is_pwd',
        'is_solo_parent',
        'is_4ps',
        'photo',
        'remarks',
    ];

    protected $casts = [
        'birthdate'      => 'date',
        'voter_status'   => 'boolean',
        'is_indigenous'  => 'boolean',
        'is_pwd'         => 'boolean',
        'is_solo_parent' => 'boolean',
        'is_4ps'         => 'boolean',
    ];

    // --- Accessors ---

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ]);
        return implode(' ', $parts);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate?->age;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return UploadUrl::url($this->photo);
    }

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public function blotterRecordsAsComplainant()
    {
        return $this->hasMany(BlotterRecord::class, 'complainant_id');
    }

    public function blotterRecordsAsRespondent()
    {
        return $this->hasMany(BlotterRecord::class, 'respondent_id');
    }

    public function blotterRecords()
    {
        return BlotterRecord::query()
            ->where('complainant_id', $this->id)
            ->orWhere('respondent_id', $this->id);
    }

    // --- Scopes ---

    public function scopeByZone($query, string $zone)
    {
        return $query->where('zone', $zone);
    }

    public function scopeVoters($query)
    {
        return $query->where('voter_status', true);
    }

    public function scopePwd($query)
    {
        return $query->where('is_pwd', true);
    }
}