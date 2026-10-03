<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'label',
        'is_done',
        'order',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'order' => 'integer',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
