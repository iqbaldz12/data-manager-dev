<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\ProgressLog;
use App\Models\ProgressPhoto;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.codexa')]
#[Title('Project Board — Codexa.id')]
class ProjectBoard extends Component
{
    use WithFileUploads;

    public string $viewMode = 'deals'; // 'deals' or 'funnel'
    public string $selectedCategory = 'all';

    // Interactive Drawer/Modal for Lead Project Details
    public bool $showDrawer = false;
    public ?Lead $selectedLead = null;
    public int $selectedLeadId = 0;

    // Progress form
    public int $newProgress = 0;
    public string $progressNote = '';

    // Photo upload form
    public $newPhoto;
    public string $photoCaption = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            $this->viewMode = 'deals';
        }
    }

    public function selectLead(int $leadId): void
    {
        $this->selectedLead = Lead::with(['tasks', 'photos.uploader', 'logs.user', 'user'])->findOrFail($leadId);
        $this->selectedLeadId = $leadId;
        $this->newProgress = $this->selectedLead->progress;
        $this->progressNote = '';
        $this->showDrawer = true;

        // Auto ensure 6 tasks if Deal / Won
        if ($this->selectedLead->status === 'Deal / Won') {
            $this->selectedLead->ensureDefaultTasks();
            $this->selectedLead->load('tasks');
        }
    }

    public function closeDrawer(): void
    {
        $this->showDrawer = false;
        $this->selectedLead = null;
        $this->newPhoto = null;
        $this->photoCaption = '';
        $this->progressNote = '';
    }

    // Toggle Task Checklist (Owner & Developer only)
    public function toggleTask(int $taskId): void
    {
        $user = Auth::user();
        if ($user->isMarketing()) {
            session()->flash('error', 'Akses ditolak: Hanya Developer dan Owner yang dapat memperbarui checklist pengerjaan website.');
            return;
        }

        $task = LeadTask::findOrFail($taskId);
        $task->is_done = ! $task->is_done;
        $task->save();

        // Recalculate lead progress
        $lead = $task->lead;
        $newCalc = $lead->recalculateProgressFromTasks();
        $this->newProgress = $newCalc;

        // Log the change
        ProgressLog::create([
            'lead_id' => $lead->id,
            'progress' => $newCalc,
            'note' => ($task->is_done ? 'Menyelesaikan tahapan: ' : 'Membatalkan checklist: ') . $task->label,
            'user_id' => $user->id,
        ]);

        $this->selectedLead->load(['tasks', 'logs.user']);
        session()->flash('success', "Tahapan '{$task->label}' berhasil diperbarui! Progress sekarang: {$newCalc}%.");
    }

    // Update Progress Slider & Note (Owner & Developer only)
    public function saveProgress(): void
    {
        $user = Auth::user();
        if ($user->isMarketing()) {
            session()->flash('error', 'Akses ditolak: Hanya Developer dan Owner yang dapat memperbarui progress.');
            return;
        }

        $this->validate([
            'newProgress' => 'required|integer|min:0|max:100',
            'progressNote' => 'nullable|string|max:1000',
        ]);

        if (! $this->selectedLead) {
            return;
        }

        $this->selectedLead->update([
            'progress' => $this->newProgress,
        ]);

        if (! empty($this->progressNote)) {
            ProgressLog::create([
                'lead_id' => $this->selectedLead->id,
                'progress' => $this->newProgress,
                'note' => $this->progressNote,
                'user_id' => $user->id,
            ]);
            $this->progressNote = '';
        }

        $this->selectedLead->load('logs.user');
        session()->flash('success', "Progress berhasil disimpan ({$this->newProgress}%).");
    }

    // Upload Progress Photo (Owner & Developer only)
    public function uploadPhoto(): void
    {
        $user = Auth::user();
        if ($user->isMarketing()) {
            session()->flash('error', 'Akses ditolak: Hanya Developer dan Owner yang dapat mengunggah foto progress.');
            return;
        }

        $this->validate([
            'newPhoto' => 'required|image|max:10240', // 10MB max
            'photoCaption' => 'nullable|string|max:255',
        ]);

        if (! $this->selectedLead) {
            return;
        }

        $path = $this->newPhoto->store('progress_photos', 'public');

        ProgressPhoto::create([
            'lead_id' => $this->selectedLead->id,
            'path' => $path,
            'caption' => $this->photoCaption ?: null,
            'uploaded_by' => $user->id,
        ]);

        // Also add log
        ProgressLog::create([
            'lead_id' => $this->selectedLead->id,
            'progress' => $this->selectedLead->progress,
            'note' => 'Mengunggah dokumentasi foto progress: ' . ($this->photoCaption ?: 'Screenshot preview website'),
            'user_id' => $user->id,
        ]);

        $this->newPhoto = null;
        $this->photoCaption = '';
        $this->selectedLead->load(['photos.uploader', 'logs.user']);

        session()->flash('success', 'Foto dokumentasi progress berhasil diunggah ke storage!');
    }

    // Quick move column for Funnel (Owner & Marketing only)
    public function moveStatus(int $leadId, string $targetStatus): void
    {
        $user = Auth::user();
        if ($user->isDeveloper()) {
            session()->flash('error', 'Akses ditolak: Developer tidak memiliki izin memindahkan status prospek.');
            return;
        }

        if (! in_array($targetStatus, Lead::STATUSES, true)) {
            return;
        }

        $lead = Lead::findOrFail($leadId);
        $lead->status = $targetStatus;

        if ($targetStatus === 'Deal / Won') {
            $lead->ensureDefaultTasks();
        }

        $lead->save();

        session()->flash('success', "Status '{$lead->name}' dipindahkan ke {$targetStatus}.");
    }

    public function render()
    {
        $user = Auth::user();
        $isDev = $user->isDeveloper();

        $query = Lead::query()->with(['tasks', 'photos', 'user']);

        if ($isDev) {
            $query->whereNotIn('status', ['Belum Dihubungi', 'Batal']);
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('category', $this->selectedCategory);
        }

        // Deal projects for Delivery Board
        $dealProjects = (clone $query)->where('status', 'Deal / Won')->latest('updated_at')->get();

        // Contacted in-progress for Delivery Board
        $inProgressProjects = (clone $query)->whereIn('status', ['Follow Up', 'Negosiasi'])->latest('updated_at')->get();

        // Funnel columns
        $funnelColumns = [
            'Belum Dihubungi' => $isDev ? collect() : (clone $query)->where('status', 'Belum Dihubungi')->take(10)->get(),
            'WA Terkirim' => (clone $query)->where('status', 'WA Terkirim')->get(),
            'Follow Up' => (clone $query)->where('status', 'Follow Up')->get(),
            'Negosiasi' => (clone $query)->where('status', 'Negosiasi')->get(),
            'Deal / Won' => (clone $query)->where('status', 'Deal / Won')->get(),
        ];

        return view('livewire.project-board', [
            'user' => $user,
            'isDev' => $isDev,
            'dealProjects' => $dealProjects,
            'inProgressProjects' => $inProgressProjects,
            'funnelColumns' => $funnelColumns,
            'categories' => Lead::CATEGORIES,
        ]);
    }
}
