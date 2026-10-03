<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\ProgressLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.codexa')]
#[Title('Dashboard — Codexa.id')]
class Dashboard extends Component
{
    public string $selectedCategory = 'all';

    public function render()
    {
        $user = Auth::user();
        $isDev = $user->isDeveloper();

        // Base query respecting RBAC
        $baseQuery = Lead::query();
        if ($isDev) {
            $baseQuery->whereNotIn('status', ['Belum Dihubungi', 'Batal']);
        }

        if ($this->selectedCategory !== 'all') {
            $baseQuery->where('category', $this->selectedCategory);
        }

        // Metrics calculation
        $totalLeads = (clone $baseQuery)->count();
        $belumDihubungi = $isDev ? 0 : (clone $baseQuery)->where('status', 'Belum Dihubungi')->count();
        $waTerkirim = (clone $baseQuery)->where('status', 'WA Terkirim')->count();
        $followUp = (clone $baseQuery)->where('status', 'Follow Up')->count();
        $negosiasi = (clone $baseQuery)->where('status', 'Negosiasi')->count();
        $dealWon = (clone $baseQuery)->where('status', 'Deal / Won')->count();
        $batal = (clone $baseQuery)->where('status', 'Batal')->count();

        $activeContacted = $waTerkirim + $followUp + $negosiasi;
        $totalContacted = $activeContacted + $dealWon + $batal;
        $conversionRate = $totalContacted > 0 ? round(($dealWon / $totalContacted) * 100, 1) : 0;

        // Deal development metrics
        $dealLeads = (clone $baseQuery)->where('status', 'Deal / Won')->get();
        $avgProgress = $dealLeads->count() > 0 ? round($dealLeads->avg('progress')) : 0;
        $totalTasks = LeadTask::whereIn('lead_id', $dealLeads->pluck('id'))->count();
        $doneTasks = LeadTask::whereIn('lead_id', $dealLeads->pluck('id'))->where('is_done', true)->count();

        // Category breakdown for charts
        $categories = Lead::CATEGORIES;
        $categoryData = [];
        foreach ($categories as $cat) {
            $q = Lead::query()->where('category', $cat);
            if ($isDev) {
                $q->whereNotIn('status', ['Belum Dihubungi', 'Batal']);
            }
            $categoryData[$cat] = $q->count();
        }

        // Funnel pipeline data
        $pipelineData = [
            'Belum Dihubungi' => $belumDihubungi,
            'WA Terkirim' => $waTerkirim,
            'Follow Up' => $followUp,
            'Negosiasi' => $negosiasi,
            'Deal / Won' => $dealWon,
            'Batal' => $batal,
        ];

        // Recent leads
        $recentLeads = (clone $baseQuery)
            ->whereNotNull('status')
            ->orderByRaw("CASE WHEN status = 'Deal / Won' THEN 1 WHEN status = 'Negosiasi' THEN 2 WHEN status = 'Follow Up' THEN 3 WHEN status = 'WA Terkirim' THEN 4 ELSE 5 END")
            ->latest('updated_at')
            ->take(6)
            ->get();

        // Recent progress logs for developers & owners
        $recentLogs = ProgressLog::with(['lead', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'user' => $user,
            'isDev' => $isDev,
            'totalLeads' => $totalLeads,
            'belumDihubungi' => $belumDihubungi,
            'activeContacted' => $activeContacted,
            'dealWon' => $dealWon,
            'batal' => $batal,
            'conversionRate' => $conversionRate,
            'avgProgress' => $avgProgress,
            'totalTasks' => $totalTasks,
            'doneTasks' => $doneTasks,
            'categoryData' => $categoryData,
            'pipelineData' => $pipelineData,
            'recentLeads' => $recentLeads,
            'recentLogs' => $recentLogs,
            'categories' => $categories,
        ]);
    }
}
