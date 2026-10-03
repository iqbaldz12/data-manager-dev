<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\LeadProof;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.codexa')]
#[Title('Data Prospek — Codexa.id')]
class LeadManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $category = 'all';

    #[Url(history: true)]
    public string $status = 'all';

    #[Url(history: true)]
    public string $minRating = 'all';

    #[Url(history: true)]
    public string $sortBy = 'rating_desc';

    public int $perPage = 15;

    // Modals state
    public bool $showDetailModal = false;
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    // === BUKTI FOTO STATUS ===
    // Modal untuk upload bukti foto saat ganti status
    public bool $showProofModal = false;
    public int $pendingLeadId = 0;
    public string $pendingStatus = '';
    public string $pendingStatusLabel = '';
    public $proofPhoto = null;
    public string $proofNote = '';
    public ?int $chattedById = null;  // Siapa yang chat perusahaan ini

    // Selected lead for detail/edit/delete
    public ?Lead $selectedLead = null;
    public int $selectedLeadId = 0;

    // Form fields for create/edit
    public string $formName = '';
    public string $formCategory = 'Kafe';
    public string $formAddress = '';
    public string $formPhone = '';
    public string $formWhatsapp = '';
    public string $formInstagram = '';
    public ?float $formRating = null;
    public ?int $formReviewsCount = null;
    public string $formMapsLink = '';
    public string $formStatus = 'Belum Dihubungi';
    public string $formNotes = '';
    public ?int $formUserId = null;

    /**
     * Status yang WAJIB upload bukti foto saat ganti
     */
    protected array $proofRequiredStatuses = [
        'WA Terkirim',
        'Follow Up',
        'Negosiasi',
        'Deal / Won',
        'Batal',
    ];

    // ─────────────────────────────────────────────────────
    // STATUS UPDATE dengan BUKTI FOTO
    // ─────────────────────────────────────────────────────

    /**
     * Dipanggil ketika user klik status baru dari dropdown.
     * Jika status memerlukan bukti foto → buka modal proof.
     * Jika tidak (Belum Dihubungi) → langsung simpan.
     */
    public function requestStatusChange(int $leadId, string $newStatus): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak: Developer tidak memiliki izin mengubah status prospek.');
            return;
        }

        if (! in_array($newStatus, Lead::STATUSES, true)) {
            return;
        }

        // Status yang memerlukan bukti foto → tampilkan modal
        if (in_array($newStatus, $this->proofRequiredStatuses, true)) {
            $this->pendingLeadId = $leadId;
            $this->pendingStatus = $newStatus;
            $this->pendingStatusLabel = $newStatus;
            $this->proofPhoto = null;
            $this->proofNote = '';
            $this->chattedById = Auth::id(); // Default: diri sendiri
            $this->showProofModal = true;
            return;
        }

        // Langsung simpan untuk "Belum Dihubungi"
        $this->applyStatusChange($leadId, $newStatus, null, '');
    }

    /**
     * Simpan status + bukti foto dari modal
     */
    public function saveStatusWithProof(): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak.');
            $this->showProofModal = false;
            return;
        }

        // Bukti foto WAJIB ada untuk status selain 'Belum Dihubungi'
        $this->validate([
            'proofPhoto'   => 'required|image|max:10240',
            'proofNote'    => 'nullable|string|max:500',
            'chattedById'  => 'nullable|exists:users,id',
        ], [
            'proofPhoto.required' => 'Bukti foto wajib diunggah sebagai bukti perubahan status.',
            'proofPhoto.image'    => 'File harus berupa gambar (JPG, PNG, WebP).',
            'proofPhoto.max'      => 'Ukuran foto maksimal 10MB.',
        ]);

        $this->applyStatusChange(
            $this->pendingLeadId,
            $this->pendingStatus,
            $this->proofPhoto,
            $this->proofNote,
            $this->chattedById,
        );

        $this->showProofModal = false;
        $this->proofPhoto = null;
        $this->proofNote = '';
        $this->chattedById = null;
        $this->pendingLeadId = 0;
        $this->pendingStatus = '';
    }

    /**
     * Tutup modal proof tanpa menyimpan
     */
    public function cancelProofModal(): void
    {
        $this->showProofModal = false;
        $this->proofPhoto = null;
        $this->proofNote = '';
        $this->chattedById = null;
        $this->pendingLeadId = 0;
        $this->pendingStatus = '';
    }

    /**
     * Core: simpan perubahan status + simpan bukti foto ke lead_proofs
     */
    protected function applyStatusChange(int $leadId, string $newStatus, $photo, string $note, ?int $chattedById = null): void
    {
        $user = Auth::user();
        $lead = Lead::findOrFail($leadId);
        $oldStatus = $lead->status;

        $lead->status = $newStatus;

        // Jika Deal / Won, buat 6 tasks checklist otomatis
        if ($newStatus === 'Deal / Won') {
            $lead->ensureDefaultTasks();
        }

        // Auto-assign marketing jika belum ada
        if (! $lead->assigned_marketing_id && $chattedById) {
            $lead->assigned_marketing_id = $chattedById;
        }

        $lead->save();

        // Simpan bukti foto jika ada
        if ($photo) {
            $path = $photo->store('proof_photos', 'public');

            LeadProof::create([
                'lead_id'       => $lead->id,
                'status'        => $newStatus,
                'photo_path'    => $path,
                'notes'         => $note ?: "Bukti perubahan status dari {$oldStatus} → {$newStatus}",
                'user_id'       => $user->id,
                'chatted_by_id' => $chattedById ?? $user->id,
            ]);
        }

        session()->flash('success', "Status '{$lead->name}' berhasil diubah ke \"{$newStatus}\"" . ($photo ? ' + bukti foto tersimpan ✓' : '.'));
    }

    // ─────────────────────────────────────────────────────
    // DETAIL MODAL
    // ─────────────────────────────────────────────────────

    public function openDetail(int $leadId): void
    {
        $this->selectedLead = Lead::with(['user', 'tasks', 'photos', 'logs', 'proofs.user', 'proofs.chattedBy'])->findOrFail($leadId);
        $this->selectedLeadId = $leadId;
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedLead = null;
    }

    // ─────────────────────────────────────────────────────
    // CREATE MODAL
    // ─────────────────────────────────────────────────────

    public function openCreateModal(): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak: Hanya Marketing dan Owner yang dapat menambah data prospek.');
            return;
        }

        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function saveCreate(): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->validate([
            'formName'         => 'required|string|max:255',
            'formCategory'     => 'required|string',
            'formAddress'      => 'nullable|string',
            'formPhone'        => 'nullable|string|max:50',
            'formWhatsapp'     => 'nullable|string|max:255',
            'formInstagram'    => 'nullable|string|max:255',
            'formRating'       => 'nullable|numeric|min:0|max:5',
            'formReviewsCount' => 'nullable|integer|min:0',
            'formMapsLink'     => 'nullable|string',
            'formStatus'       => 'required|in:' . implode(',', Lead::STATUSES),
            'formNotes'        => 'nullable|string',
        ]);

        $lead = Lead::create([
            'name'             => $this->formName,
            'category'         => $this->formCategory,
            'address'          => $this->formAddress,
            'phone'            => $this->formPhone ?: null,
            'whatsapp_link'    => $this->formWhatsapp ?: null,
            'instagram_search' => $this->formInstagram ?: null,
            'rating'           => $this->formRating,
            'reviews_count'    => $this->formReviewsCount,
            'maps_link'        => $this->formMapsLink ?: null,
            'status'           => $this->formStatus,
            'notes'            => $this->formNotes ?: null,
            'user_id'          => $this->formUserId ?: $user->id,
        ]);

        if ($this->formStatus === 'Deal / Won') {
            $lead->ensureDefaultTasks();
        }

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', "Prospek baru '{$lead->name}' berhasil ditambahkan!");
    }

    // ─────────────────────────────────────────────────────
    // EDIT MODAL
    // ─────────────────────────────────────────────────────

    public function openEditModal(int $leadId): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $lead = Lead::findOrFail($leadId);
        $this->selectedLead   = $lead;
        $this->selectedLeadId = $leadId;
        $this->formName       = $lead->name;
        $this->formCategory   = $lead->category;
        $this->formAddress    = $lead->address ?? '';
        $this->formPhone      = $lead->phone ?? '';
        $this->formWhatsapp   = $lead->whatsapp_link ?? '';
        $this->formInstagram  = $lead->instagram_search ?? '';
        $this->formRating     = $lead->rating;
        $this->formReviewsCount = $lead->reviews_count;
        $this->formMapsLink   = $lead->maps_link ?? '';
        $this->formStatus     = $lead->status;
        $this->formNotes      = $lead->notes ?? '';
        $this->formUserId     = $lead->user_id;
        $this->showEditModal  = true;
    }

    public function saveEdit(): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->validate([
            'formName'         => 'required|string|max:255',
            'formCategory'     => 'required|string',
            'formAddress'      => 'nullable|string',
            'formPhone'        => 'nullable|string|max:50',
            'formWhatsapp'     => 'nullable|string|max:255',
            'formInstagram'    => 'nullable|string|max:255',
            'formRating'       => 'nullable|numeric|min:0|max:5',
            'formReviewsCount' => 'nullable|integer|min:0',
            'formMapsLink'     => 'nullable|string',
            'formStatus'       => 'required|in:' . implode(',', Lead::STATUSES),
            'formNotes'        => 'nullable|string',
        ]);

        if (! $this->selectedLead) {
            return;
        }

        $oldStatus = $this->selectedLead->status;

        $this->selectedLead->update([
            'name'             => $this->formName,
            'category'         => $this->formCategory,
            'address'          => $this->formAddress,
            'phone'            => $this->formPhone ?: null,
            'whatsapp_link'    => $this->formWhatsapp ?: null,
            'instagram_search' => $this->formInstagram ?: null,
            'rating'           => $this->formRating,
            'reviews_count'    => $this->formReviewsCount,
            'maps_link'        => $this->formMapsLink ?: null,
            'status'           => $this->formStatus,
            'notes'            => $this->formNotes ?: null,
            'user_id'          => $this->formUserId,
        ]);

        if ($oldStatus !== 'Deal / Won' && $this->formStatus === 'Deal / Won') {
            $this->selectedLead->ensureDefaultTasks();
        }

        $this->showEditModal = false;
        session()->flash('success', "Data prospek '{$this->selectedLead->name}' berhasil diperbarui.");
    }

    // ─────────────────────────────────────────────────────
    // DELETE MODAL (Owner only)
    // ─────────────────────────────────────────────────────

    public function confirmDelete(int $leadId): void
    {
        $user = Auth::user();
        if (! $user->isOwner()) {
            session()->flash('error', 'Akses ditolak: Hanya Owner yang dapat menghapus data prospek.');
            return;
        }

        $this->selectedLead   = Lead::findOrFail($leadId);
        $this->selectedLeadId = $leadId;
        $this->showDeleteModal = true;
    }

    public function deleteLead(): void
    {
        $user = Auth::user();
        if (! $user->isOwner()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        if ($this->selectedLead) {
            $name = $this->selectedLead->name;
            $this->selectedLead->delete();
            $this->showDeleteModal = false;
            $this->selectedLead = null;
            session()->flash('success', "Prospek '{$name}' telah dihapus dari database.");
        }
    }

    public function resetForm(): void
    {
        $this->formName         = '';
        $this->formCategory     = 'Kafe';
        $this->formAddress      = '';
        $this->formPhone        = '';
        $this->formWhatsapp     = '';
        $this->formInstagram    = '';
        $this->formRating       = null;
        $this->formReviewsCount = null;
        $this->formMapsLink     = '';
        $this->formStatus       = 'Belum Dihubungi';
        $this->formNotes        = '';
        $this->formUserId       = null;
    }

    // ─────────────────────────────────────────────────────
    // EXPORT CSV
    // ─────────────────────────────────────────────────────

    public function exportCsv()
    {
        $user  = Auth::user();
        $isDev = $user->isDeveloper();

        $query = Lead::query();
        if ($isDev) {
            $query->whereNotIn('status', ['Belum Dihubungi', 'Batal']);
        }
        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $leads = $query->get();

        $csvHeader = ['ID', 'Nama', 'Kategori', 'Alamat', 'Telepon', 'WhatsApp', 'Instagram', 'Rating', 'Jumlah Ulasan', 'Status', 'Progress (%)', 'Catatan'];

        $callback = function () use ($leads, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id, $lead->name, $lead->category, $lead->address,
                    $lead->phone, $lead->whatsapp_link, $lead->instagram_search,
                    $lead->rating, $lead->reviews_count, $lead->status,
                    $lead->progress, $lead->notes,
                ]);
            }
            fclose($file);
        };

        $filename = 'codexa_leads_purwokerto_' . date('Y-m-d_His') . '.csv';

        return Response::stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatingCategory(): void { $this->resetPage(); }
    public function updatingStatus(): void   { $this->resetPage(); }

    // ─────────────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────────────

    public function render()
    {
        $user  = Auth::user();
        $isDev = $user->isDeveloper();

        $query = Lead::query()->with('user');

        // RBAC constraint for Developer
        if ($isDev) {
            $query->whereNotIn('status', ['Belum Dihubungi', 'Batal']);
        }

        if (! empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('address', 'like', $term)
                  ->orWhere('phone', 'like', $term);
            });
        }

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->minRating !== 'all') {
            $query->where('rating', '>=', (float) $this->minRating);
        }

        match ($this->sortBy) {
            'rating_desc'  => $query->orderByDesc('rating')->orderByDesc('reviews_count'),
            'reviews_desc' => $query->orderByDesc('reviews_count'),
            'name_asc'     => $query->orderBy('name'),
            'updated_desc' => $query->latest('updated_at'),
            default        => $query->latest('id'),
        };

        $leads      = $query->paginate($this->perPage);
        $teamUsers  = User::orderBy('name')->get();
        $marketingUsers = User::where('role', 'marketing')->orWhere('role', 'owner')->orderBy('name')->get();

        return view('livewire.lead-manager', [
            'leads'          => $leads,
            'user'           => $user,
            'isDev'          => $isDev,
            'categories'     => Lead::CATEGORIES,
            'statuses'       => Lead::STATUSES,
            'teamUsers'      => $teamUsers,
            'marketingUsers' => $marketingUsers,
            'totalCount'     => $leads->total(),
        ]);
    }
}
