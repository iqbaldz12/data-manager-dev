<div class="space-y-6">
    <x-slot name="header">Dashboard Ikhtisar</x-slot>

    <!-- Role Welcome & Filter Banner -->
    <div class="glass-panel p-5 sm:p-6 rounded-2xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-gradient-to-br from-indigo-500/10 to-purple-500/10 blur-3xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Codexa.id Data Manager</span>
                    <span class="text-slate-600">&bull;</span>
                    <span class="text-xs text-slate-400 font-mono">Purwokerto Prospecting</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white mt-1">
                    Selamat Datang, <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">{{ $user->name }}</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-2xl">
                    @if($isDev)
                        Anda masuk sebagai <strong class="text-emerald-400">Developer</strong>. Menampilkan lead yang telah dihubungi & aktif dalam tahap pengerjaan project website.
                    @elseif($user->isMarketing())
                        Anda masuk sebagai <strong class="text-indigo-400">Marketing</strong>. Pantau funnel prospek, kirim pesan WhatsApp langsung, dan tingkatkan konversi closing.
                    @else
                        Anda masuk sebagai <strong class="text-amber-400">Owner</strong>. Akses analitik komprehensif, pipeline prospecting 259 usaha Purwokerto, dan delivery project.
                    @endif
                </p>
            </div>

            <!-- Category Filter Selector -->
            <div class="flex items-center gap-2 self-start lg:self-center">
                <label for="dashCategory" class="text-xs font-semibold text-slate-400 whitespace-nowrap">Filter Kategori:</label>
                <select id="dashCategory"
                        wire:model.live="selectedCategory"
                        class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs text-slate-200 focus:outline-hidden focus:border-indigo-500">
                    <option value="all">Semua Kategori (8)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Top KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Leads -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    {{ $isDev ? 'Prospek Aktif' : 'Total Data Prospek' }}
                </span>
                <div class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-address-book text-base"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white tracking-tight">{{ $totalLeads }}</span>
                <span class="text-xs text-slate-400 font-medium">Usaha Purwokerto</span>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-slate-400">
                <i class="fa-solid fa-database text-indigo-400"></i>
                <span>Data Nyata CSV (Tanpa Dummy)</span>
            </div>
        </div>

        <!-- Card 2: Contacted / Pipeline -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pipeline Follow-Up</span>
                <div class="w-10 h-10 rounded-xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-comments text-base"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white tracking-tight">{{ $activeContacted }}</span>
                <span class="text-xs text-blue-400 font-medium">WA & Negosiasi</span>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-slate-400">
                <i class="fa-solid fa-paper-plane text-blue-400"></i>
                <span>Tahap prospecting berjalan</span>
            </div>
        </div>

        <!-- Card 3: Deal / Won -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Project Deal / Won</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-check text-base"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-400 tracking-tight">{{ $dealWon }}</span>
                <span class="text-xs text-slate-400 font-medium">Klien Aktif</span>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-400 font-medium">
                <i class="fa-solid fa-chart-line"></i>
                <span>{{ $conversionRate }}% Conversion Rate</span>
            </div>
        </div>

        <!-- Card 4: Dev Delivery Progress -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Progress Dev</span>
                <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-purple-300 tracking-tight">{{ $avgProgress }}%</span>
                <span class="text-xs text-slate-400 font-medium">dari Deal Projects</span>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <div class="flex-1 h-2 rounded-full bg-slate-800 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" style="width: {{ $avgProgress }}%"></div>
                </div>
                <span class="text-[10px] text-slate-400 font-mono">{{ $doneTasks }}/{{ $totalTasks }} Task</span>
            </div>
        </div>
    </div>

    <!-- Charts Section (Chart.js Dark Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart 1: Funnel Pipeline (7 Cols) -->
        <div class="lg:col-span-7 glass-panel p-5 sm:p-6 rounded-2xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-filter text-indigo-400"></i>
                        <span>Pipeline Funnel Prospek</span>
                    </h3>
                    <p class="text-xs text-slate-400">Alur konversi prospek dari kontak hingga deal</p>
                </div>
                <span class="text-xs font-mono text-indigo-400 bg-indigo-500/10 px-2.5 py-1 rounded-lg border border-indigo-500/20">
                    Chart.js v4
                </span>
            </div>

            <div class="relative h-72 w-full" wire:ignore>
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Category Breakdown (5 Cols) -->
        <div class="lg:col-span-5 glass-panel p-5 sm:p-6 rounded-2xl flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-pie-chart text-purple-400"></i>
                        <span>Distribusi 8 Kategori Usaha</span>
                    </h3>
                    <p class="text-xs text-slate-400">Sebaran usaha non-website di Purwokerto</p>
                </div>
                <span class="text-xs text-slate-500 font-mono">259 Total</span>
            </div>

            <div class="relative h-72 w-full flex items-center justify-center" wire:ignore>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Active Contacted Leads & Dev Activity Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- High Priority Active Leads (7 cols) -->
        <div class="lg:col-span-7 glass-panel p-5 sm:p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-fire text-amber-400"></i>
                        <span>Prospek Prioritas & Kontak Aktif</span>
                    </h3>
                    <p class="text-xs text-slate-400">Data usaha ber-rating tinggi yang siap ditawarkan website</p>
                </div>
                <a href="{{ route('leads.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1 transition-colors">
                    <span>Lihat Semua (259)</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentLeads as $lead)
                    <div class="p-3.5 rounded-xl glass-card border border-slate-800/80 hover:border-slate-700 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1 min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-white truncate">{{ $lead->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $lead->getCategoryColorClass() }}">
                                    {{ $lead->category }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $lead->getStatusColorClass() }}">
                                    {{ $lead->status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-slate-500 text-[10px]"></i>
                                <span>{{ $lead->address }}</span>
                            </p>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                @if($lead->rating)
                                    <span class="flex items-center gap-1 text-amber-400 font-semibold">
                                        <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($lead->rating, 1) }}
                                        <span class="text-slate-500 font-normal">({{ $lead->reviews_count }} ulasan)</span>
                                    </span>
                                @endif
                                @if($lead->phone)
                                    <span class="text-slate-400 flex items-center gap-1 font-mono">
                                        <i class="fa-solid fa-phone text-[9px] text-slate-500"></i> {{ $lead->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            @if($lead->clean_whatsapp_url)
                                <a href="{{ $lead->clean_whatsapp_url }}" target="_blank"
                                   title="Chat WhatsApp dengan template penawaran"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500/25 border border-emerald-500/30 flex items-center gap-1.5 transition-all">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>Kirim WA</span>
                                </a>
                            @endif

                            @if($lead->maps_link)
                                <a href="{{ $lead->maps_link }}" target="_blank"
                                   title="Buka Google Maps"
                                   class="p-2 rounded-lg text-xs bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 border border-slate-700 transition-all">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs">
                        Tidak ada prospek yang sesuai kriteria.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Dev Project Timeline / Activity Log (5 cols) -->
        <div class="lg:col-span-5 glass-panel p-5 sm:p-6 rounded-2xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-code-commit text-emerald-400"></i>
                            <span>Aktivitas Development Website</span>
                        </h3>
                        <p class="text-xs text-slate-400">Log kemajuan project & checklist deployment</p>
                    </div>
                    <a href="{{ route('projects.board') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                        Board
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentLogs as $log)
                        <div class="relative pl-6 pb-2 border-l border-slate-800 last:border-0">
                            <div class="absolute -left-1.5 top-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-slate-950"></div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-bold text-slate-200">{{ $log->lead->name ?? 'Lead Project' }}</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Progress {{ $log->progress }}%
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">{{ $log->note }}</p>
                            <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-500">
                                <span><i class="fa-solid fa-user text-[9px]"></i> {{ $log->user->name ?? 'Developer' }}</span>
                                <span>&bull;</span>
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500 text-xs">
                            Belum ada catatan log progress.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Action to Project Board -->
            <div class="mt-6 pt-4 border-t border-slate-800/80">
                <a href="{{ route('projects.board') }}"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center text-slate-200 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-diagram-project text-indigo-400"></i>
                    <span>Buka Papan Kerja Project & Checklist (6 Tahap)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js Dark Mode Script Initialization -->
    <script>
        document.addEventListener('livewire:navigated', initCharts);
        document.addEventListener('DOMContentLoaded', initCharts);

        function initCharts() {
            if (typeof Chart === 'undefined') return;

            // Pipeline Funnel Bar Chart
            const pipelineCtx = document.getElementById('pipelineChart');
            if (pipelineCtx) {
                const existing = Chart.getChart(pipelineCtx);
                if (existing) existing.destroy();

                const pipelineData = @json($pipelineData);
                const labels = Object.keys(pipelineData);
                const values = Object.values(pipelineData);

                new Chart(pipelineCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Prospek',
                            data: values,
                            backgroundColor: [
                                'rgba(100, 116, 139, 0.7)', // Belum Dihubungi (Slate)
                                'rgba(59, 130, 246, 0.7)',  // WA Terkirim (Blue)
                                'rgba(245, 158, 11, 0.7)',  // Follow Up (Amber)
                                'rgba(168, 85, 247, 0.7)',  // Negosiasi (Purple)
                                'rgba(16, 185, 129, 0.8)',  // Deal / Won (Emerald)
                                'rgba(244, 63, 94, 0.7)',   // Batal (Rose)
                            ],
                            borderColor: [
                                '#94a3b8',
                                '#60a5fa',
                                '#fbbf24',
                                '#c084fc',
                                '#34d399',
                                '#fb7185',
                            ],
                            borderWidth: 1.5,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(30, 41, 59, 0.6)' },
                                ticks: { color: '#94a3b8', font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(30, 41, 59, 0.6)' },
                                ticks: { color: '#94a3b8', font: { size: 11 }, precision: 0 }
                            }
                        }
                    }
                });
            }

            // Category Doughnut Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                const existingCat = Chart.getChart(categoryCtx);
                if (existingCat) existingCat.destroy();

                const categoryData = @json($categoryData);
                const catLabels = Object.keys(categoryData);
                const catValues = Object.values(categoryData);

                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            data: catValues,
                            backgroundColor: [
                                '#f59e0b', // Kafe (amber)
                                '#6366f1', // Hotel (indigo)
                                '#14b8a6', // Klinik (teal)
                                '#10b981', // Apotek (emerald)
                                '#06b6d4', // Dokter gigi (cyan)
                                '#0ea5e9', // Laundry (sky)
                                '#a855f7', // Optik (purple)
                                '#d946ef', // Penginapan (fuchsia)
                            ],
                            borderColor: '#020617',
                            borderWidth: 3,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: '#94a3b8',
                                    font: { size: 11 },
                                    boxWidth: 12,
                                    padding: 10
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 10
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        }
    </script>
</div>
