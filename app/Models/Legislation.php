<?php

namespace App\Models;

use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Legislation extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_ORDINANCE   = 'ordinance';
    const TYPE_RESOLUTION  = 'resolution';

    const TYPES = [
        self::TYPE_ORDINANCE  => 'Ordinance',
        self::TYPE_RESOLUTION => 'Resolution',
    ];

    const STATUS_DRAFT    = 'draft';
    const STATUS_ACTIVE   = 'active';
    const STATUS_REPEALED = 'repealed';

    const STATUSES = [
        self::STATUS_DRAFT    => 'Draft',
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_REPEALED => 'Repealed',
    ];

    protected $fillable = [
        'title',
        'type',
        'number',
        'series',
        'description',
        'content',
        'tags',
        'status',
        'date_enacted',
        'date_effective',
        'file_path',
        'uploaded_by',
    ];

    protected $casts = [
        'tags'           => 'array',
        'date_enacted'   => 'date',
        'date_effective' => 'date',
    ];

    // --- Relationships ---

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('number', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('content', 'like', "%{$keyword}%");
        });
    }

    public function scopeByTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    // --- Accessors ---

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFileUrlAttribute(): ?string
    {
        return UploadUrl::url($this->file_path);
    }

    public function getFullTitleAttribute(): string
    {
        return "{$this->type_name} No. {$this->number}, Series of {$this->series}";
    }
}