<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'address',
        'phone',
        'whatsapp_link',
        'instagram_search',
        'rating',
        'reviews_count',
        'maps_link',
        'status',
        'notes',
        'progress',
        'user_id',
        'assigned_marketing_id',
    ];

    protected $casts = [
        'rating' => 'float',
        'reviews_count' => 'integer',
        'progress' => 'integer',
    ];

    public const STATUSES = [
        'Belum Dihubungi',
        'WA Terkirim',
        'Follow Up',
        'Negosiasi',
        'Deal / Won',
        'Batal',
    ];

    public const CATEGORIES = [
        'Kafe',
        'Hotel',
        'Klinik',
        'Apotek',
        'Dokter gigi',
        'Laundry',
        'Optik',
        'Penginapan / Guest house',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Marketing PIC yang di-assign untuk handle prospek ini
     */
    public function assignedMarketing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_marketing_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(LeadTask::class)->orderBy('order');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class)->latest();
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ProgressLog::class)->latest();
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(LeadProof::class)->latest();
    }

    /**
     * Check if the lead has been contacted (not Belum Dihubungi or Batal).
     */
    public function isContacted(): bool
    {
        return !in_array($this->status, ['Belum Dihubungi', 'Batal']);
    }

    /**
     * Auto-create the 6 standard project workflow tasks when Deal / Won.
     */
    public function ensureDefaultTasks(): void
    {
        if ($this->tasks()->count() === 0) {
            $defaultTasks = [
                '📋 Brief & Requirement',
                '🎨 Desain UI/UX',
                '⚙️ Development Frontend',
                '🔧 Development Backend',
                '🧪 Testing & QA',
                '🚀 Deploy & Launching',
            ];

            foreach ($defaultTasks as $index => $label) {
                $this->tasks()->create([
                    'label' => $label,
                    'is_done' => false,
                    'order' => $index + 1,
                ]);
            }
        }
    }

    /**
     * Recalculate progress percentage based on completed tasks if Deal/Won.
     */
    public function recalculateProgressFromTasks(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return $this->progress;
        }

        $done = $this->tasks()->where('is_done', true)->count();
        $calc = (int) round(($done / $total) * 100);
        $this->update(['progress' => $calc]);
        return $calc;
    }

    /**
     * Get clean WhatsApp URL.
     */
    public function getCleanWhatsappUrlAttribute(): ?string
    {
        if (!empty($this->whatsapp_link)) {
            return $this->whatsapp_link;
        }

        if (!empty($this->phone)) {
            $clean = preg_replace('/[^0-9]/', '', $this->phone);
            if (str_starts_with($clean, '0')) {
                $clean = '62' . substr($clean, 1);
            }
            if (!str_starts_with($clean, '62') && strlen($clean) >= 9) {
                $clean = '62' . $clean;
            }
            $defaultMessage = urlencode("Halo {$this->name}, salam kenal dari Codexa.id! Kami melihat profil bisnis Anda di Google Maps dan ingin menawarkan solusi pembuatan website profesional & modern untuk meningkatkan penjualan.");
            return "https://wa.me/{$clean}?text={$defaultMessage}";
        }

        return null;
    }

    /**
     * Status color styling helper
     */
    public function getStatusColorClass(): string
    {
        return match ($this->status) {
            'Belum Dihubungi' => 'bg-slate-800/80 text-slate-300 border-slate-700',
            'WA Terkirim'     => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
            'Follow Up'       => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
            'Negosiasi'       => 'bg-purple-500/15 text-purple-300 border-purple-500/30',
            'Deal / Won'      => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
            'Batal'           => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
            default           => 'bg-slate-800 text-slate-400 border-slate-700',
        };
    }

    /**
     * Category badge color styling helper
     */
    public function getCategoryColorClass(): string
    {
        return match ($this->category) {
            'Kafe'                     => 'bg-amber-500/10 text-amber-300 border-amber-500/20',
            'Hotel'                    => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20',
            'Klinik'                   => 'bg-teal-500/10 text-teal-300 border-teal-500/20',
            'Apotek'                   => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
            'Dokter gigi'              => 'bg-cyan-500/10 text-cyan-300 border-cyan-500/20',
            'Laundry'                  => 'bg-sky-500/10 text-sky-300 border-sky-500/20',
            'Optik'                    => 'bg-purple-500/10 text-purple-300 border-purple-500/20',
            'Penginapan / Guest house' => 'bg-fuchsia-500/10 text-fuchsia-300 border-fuchsia-500/20',
            default                    => 'bg-slate-800 text-slate-300 border-slate-700',
        };
    }
}
