<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LeadProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'status',
        'photo_path',
        'notes',
        'user_id',
        'chatted_by_id',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marketing yang menghubungi perusahaan (bisa berbeda dengan uploader)
     */
    public function chattedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chatted_by_id');
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->photo_path, 'http')) {
            return $this->photo_path;
        }

        return Storage::url($this->photo_path);
    }
}
