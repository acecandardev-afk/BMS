<?php

namespace App\Models;

use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blotter_record_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    // --- Relationships ---

    public function blotterRecord()
    {
        return $this->belongsTo(BlotterRecord::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // --- Accessors ---

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function getFileUrlAttribute(): string
    {
        return UploadUrl::url($this->file_path) ?? '';
    }
}