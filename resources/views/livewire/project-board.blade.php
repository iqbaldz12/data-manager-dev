<div class="space-y-6">
    <x-slot name="header">Project Board & Delivery</x-slot>

    <!-- Top Mode Switcher & Filter -->
    <div class="glass-panel p-4 sm:p-6 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-indigo-400"></i>
                <span>Papan Pengerjaan & Pipeline Web</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola tahapan brief, UI/UX, dev frontend, backend, QA hingga deployment live
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <!-- View Mode Switcher -->
            <div class="p-1 rounded-xl bg-slate-950/80 border border-slate-800 flex items-center gap-1">
                <button type="button"
                        wire:click="$set('viewMode', 'deals')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'deals' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-rocket mr-1"></i> Delivery Board (Deal)
                </button>
                @if(! $isDev)
                    <button type="button"
                            wire:click="$set('viewMode', 'funnel')"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'funnel' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">
                        <i class="fa-solid fa-table-columns mr-1"></i> Funnel Pipeline
                    </button>
                @endif
            </div>

            <!-- Category Selector -->
            <div>
                <select wire:model.live="selectedCategory"
                        class="px-3 py-1.5 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-200 focus:outline-hidden focus:border-indigo-500">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- VIEW MODE 1: DELIVERY BOARD (DEAL / WON PROJECTS) -->
    @if($viewMode === 'deals')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Project Deal / Won Aktif ({{ $dealProjects->count() }})</span>
                </h3>
                <span class="text-xs text-slate-400">Klik project untuk buka checklist 6 tahapan & upload foto</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($dealProjects as $deal)
                    <div wire:click="selectLead({{ $deal->id }})"
                         class="glass-panel p-5 rounded-2xl border border-slate-800/80 hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-950/40 transition-all duration-300 cursor-pointer flex flex-col justify-between group relative overflow-hidden">
                        
                        <!-- Glow highlight -->
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-indigo-500/10 blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>

                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $deal->getCategoryColorClass() }}">
                                    {{ $deal->category }}
                                </span>
                                <span class="text-[11px] font-mono font-bold text-emerald-400 bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 rounded">
                                    {{ $deal->progress }}% Selesai
                                </span>
                            </div>

                            <h4 class="text-base font-bold text-white group-hover:text-indigo-300 transition-colors">
                                {{ $deal->name }}
                            </h4>
                            <p class="text-xs text-slate-400 line-clamp-1 mt-1">
                                <i class="fa-solid fa-location-dot text-slate-500 text-[10px]"></i> {{ $deal->address }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mt-4 space-y-1.5">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400 font-medium">Progress Pengerjaan</span>
                                    <span class="text-indigo-300 font-mono font-bold">{{ $deal->progress }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-950 overflow-hidden border border-slate-800">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 rounded-full transition-all duration-500"
                                         style="width: {{ $deal->progress }}%"></div>
                                </div>
                            </div>

                            <!-- Tasks Breakdown -->
                            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-list-check text-purple-400"></i>
                                    <span>Checklist: <strong>{{ $deal->tasks->where('is_done', true)->count() }}/6</strong></span>
                                </span>

                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera text-cyan-400"></i>
                                    <span>Foto: <strong>{{ $deal->photos->count() }}</strong></span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs">
                            <span class="text-slate-400 flex items-center gap-1 text-[11px]">
                                <i class="fa-solid fa-user-circle text-slate-500"></i>
                                <span>{{ $deal->user->name ?? 'Belum Assigned' }}</span>
                            </span>

                            <span class="text-indigo-400 font-bold group-hover:translate-x-1 transition-transform flex items-center gap-1 text-[11px]">
                                <span>Buka Panel</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 text-center text-slate-500 glass-panel rounded-2xl">
                        <i class="fa-solid fa-folder-open text-4xl text-slate-600 mb-2 block"></i>
                        Belum ada project dengan status Deal / Won di kategori ini.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- VIEW MODE 2: FUNNEL KANBAN PIPELINE -->
    @else
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-table-columns text-indigo-400"></i>
                    <span>Pipeline Sales Prospecting Purwokerto</span>
                </h3>
                <span class="text-xs text-slate-400">Pindahkan prospek antar tahap funnel</span>
            </div>

            <!-- Kanban Horizontal Scroll Container -->
            <div class="flex gap-4 overflow-x-auto pb-6">
                @foreach($funnelColumns as $statusName => $leadsInColumn)
                    <div class="w-80 shrink-0 glass-panel rounded-2xl p-4 flex flex-col max-h-[75vh]">
                        <!-- Column Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $statusName === 'Deal / Won' ? 'bg-emerald-400' : ($statusName === 'Negosiasi' ? 'bg-purple-400' : ($statusName === 'Follow Up' ? 'bg-amber-400' : ($statusName === 'WA Terkirim' ? 'bg-blue-400' : 'bg-slate-500'))) }}"></span>
                                <h4 class="text-xs font-bold text-white uppercase">{{ $statusName }}</h4>
                            </div>
                            <span class="text-[11px] font-mono px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                {{ $leadsInColumn->count() }}
                            </span>
                        </div>

                        <!-- Column Cards Scroll -->
                        <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                            @forelse($leadsInColumn as $colLead)
                                <div class="glass-card p-3.5 rounded-xl border border-slate-800/90 hover:border-slate-700 transition-all text-xs space-y-2">
                                    <div class="flex items-start justify-between gap-1">
                                        <h5 class="font-bold text-white line-clamp-1 hover:text-indigo-300 cursor-pointer"
                                            wire:click="selectLead({{ $colLead->id }})">
                                            {{ $colLead->name }}
                                        </h5>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-semibold border {{ $colLead->getCategoryColorClass() }}">
                                            {{ $colLead->category }}
                                        </span>
                                    </div>

                                    <p class="text-[11px] text-slate-400 line-clamp-1">{{ $colLead->address }}</p>

                                    @if($colLead->rating)
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                            <span class="text-amber-400 font-bold flex items-center gap-0.5">
                                                <i class="fa-solid fa-star text-[9px]"></i> {{ number_format($colLead->rating, 1) }}
                                            </span>
                                            @if($colLead->phone)
                                                <span class="font-mono text-slate-400">{{ $colLead->phone }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Quick Move Status Buttons (for Owner & Marketing) -->
                                    @if(! $isDev)
                                        <div class="pt-2 border-t border-slate-800/70 flex items-center justify-between">
                                            <button wire:click="selectLead({{ $colLead->id }})" class="text-[10px] font-semibold text-slate-400 hover:text-indigo-300 flex items-center gap-1">
                                                <i class="fa-solid fa-eye"></i> Detail
                                            </button>

                                            <!-- Move Dropdown -->
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                                                    <span>Pindah</span>
                                                    <i class="fa-solid fa-chevron-down text-[8px]"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-cloak
                                                     class="absolute right-0 bottom-full mb-1 w-36 rounded-xl bg-slate-900 border border-slate-700 shadow-2xl p-1 z-30">
                                                    @foreach(array_keys($funnelColumns) as $target)
                                                        @if($target !== $statusName)
                                                            <button type="button"
                                                                    wire:click="moveStatus({{ $colLead->id }}, '{{ $target }}')"
                                                                    @click="open = false"
                                                                    class="w-full text-left px-2 py-1 rounded text-[11px] text-slate-300 hover:bg-slate-800">
                                                                {{ $target }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-600 text-xs">
                                    Kosong
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- INTERACTIVE PROJECT DRAWER / MODAL -->
    @if($showDrawer && $selectedLead)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/80 backdrop-blur-md">
            <div class="glass-panel w-full max-w-4xl max-h-[92vh] overflow-y-auto rounded-3xl p-5 sm:p-7 border border-slate-700 shadow-2xl relative">
                
                <!-- Close Button -->
                <button wire:click="closeDrawer" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-white bg-slate-800 border border-slate-700 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <!-- Header Info -->
                <div class="pr-10">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $selectedLead->getCategoryColorClass() }}">
                            {{ $selectedLead->category }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $selectedLead->getStatusColorClass() }}">
                            {{ $selectedLead->status }}
                        </span>
                        @if($selectedLead->rating)
                            <span class="text-amber-400 font-bold text-xs flex items-center gap-1 ml-2">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($selectedLead->rating, 1) }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-white">{{ $selectedLead->name }}</h2>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-indigo-400"></i>
                        <span>{{ $selectedLead->address }}</span>
                    </p>

                    <!-- Contact Bar -->
                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        @if($selectedLead->clean_whatsapp_url)
                            <a href="{{ $selectedLead->clean_whatsapp_url }}" target="_blank"
                               class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500/25 border border-emerald-500/30 flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-sm"></i> Kirim WhatsApp
                            </a>
                        @endif
                        @if($selectedLead->maps_link)
                            <a href="{{ $selectedLead->maps_link }}" target="_blank"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-map-location-dot text-indigo-400"></i> Buka Maps
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Main Content Tabs / Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6 pt-6 border-t border-slate-800">
                    
                    <!-- Left: 6-Step Checklist & Progress (7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        
                        <!-- 6 Default Tasks Checklist -->
                        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-list-check text-purple-400"></i>
                                        <span>Checklist 6 Tahapan Web Development</span>
                                    </h4>
                                    <p class="text-[11px] text-slate-400">
                                        @if($user->isMarketing())
                                            (Mode Baca: Pengelolaan tahap oleh Developer / Owner)
                                        @else
                                            Klik untuk menyelesaikan tahapan & kalkulasi otomatis progress %
                                        @endif
                                    </p>
                                </div>
                                <span class="text-xs font-mono font-bold text-purple-300 px-2 py-0.5 rounded bg-purple-500/15 border border-purple-500/30">
                                    {{ $selectedLead->tasks->where('is_done', true)->count() }}/6 Selesai
                                </span>
                            </div>

                            <div class="space-y-2">
                                @forelse($selectedLead->tasks as $task)
                                    <div class="p-3 rounded-xl border transition-all flex items-center justify-between gap-3 {{ $task->is_done ? 'bg-emerald-950/20 border-emerald-500/30 text-slate-200' : 'bg-slate-950/50 border-slate-800/80 text-slate-400' }}">
                                        <div class="flex items-center gap-3">
                                            @if($user->isMarketing())
                                                <!-- Read only for marketing -->
                                                <i class="fa-solid {{ $task->is_done ? 'fa-circle-check text-emerald-400' : 'fa-circle text-slate-700' }} text-base"></i>
                                            @else
                                                <!-- Clickable toggle for Owner & Dev -->
                                                <button type="button"
                                                        wire:click="toggleTask({{ $task->id }})"
                                                        class="cursor-pointer transition-transform hover:scale-110">
                                                    <i class="fa-solid {{ $task->is_done ? 'fa-circle-check text-emerald-400' : 'fa-circle text-slate-600 hover:text-slate-400' }} text-base"></i>
                                                </button>
                                            @endif
                                            <span class="text-xs font-medium {{ $task->is_done ? 'line-through text-slate-300' : 'text-slate-200' }}">
                                                {{ $task->label }}
                                            </span>
                                        </div>

                                        <span class="text-[10px] font-mono {{ $task->is_done ? 'text-emerald-400 font-bold' : 'text-slate-600' }}">
                                            {{ $task->is_done ? 'SELESAI' : 'MENUNGGU' }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500">Tidak ada checklist.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Progress Slider & Manual Update (Developer & Owner) -->
                        @if(! $user->isMarketing())
                            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800">
                                <h4 class="text-sm font-bold text-white flex items-center gap-2 mb-3">
                                    <i class="fa-solid fa-sliders text-indigo-400"></i>
                                    <span>Pembaruan Progress Manual & Catatan</span>
                                </h4>

                                <div class="space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-medium text-slate-300 mb-1">
                                            <span>Tingkat Kelulusan (Progress %)</span>
                                            <span class="text-indigo-400 font-mono font-bold">{{ $newProgress }}%</span>
                                        </div>
                                        <input type="range" min="0" max="100" wire:model.live="newProgress"
                                               class="w-full accent-indigo-500 bg-slate-950 h-2 rounded-lg cursor-pointer">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Catatan Pengerjaan (Opsional)</label>
                                        <textarea wire:model="progressNote" rows="2" placeholder="Contoh: Selesai integrasi payment gateway dan testing responsive mobile..."
                                                  class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-200 focus:border-indigo-500 focus:outline-hidden"></textarea>
                                    </div>

                                    <button type="button" wire:click="saveProgress"
                                            class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/30 flex items-center justify-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        <span>Simpan Log Progress</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Photos Gallery & Upload & Timeline (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Progress Photos Gallery -->
                        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="fa-solid fa-images text-cyan-400"></i>
                                    <span>Dokumentasi Foto Progress</span>
                                </h4>
                                <span class="text-xs font-mono text-cyan-400">{{ $selectedLead->photos->count() }} Foto</span>
                            </div>

                            <!-- Photo Upload Form (Owner & Dev) -->
                            @if(! $user->isMarketing())
                                <div class="mb-4 p-3 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2">
                                    <label class="block text-[11px] font-semibold text-slate-300">Unggah Tangkapan Layar / Foto Progress</label>
                                    <input type="file" wire:model="newPhoto" accept="image/*"
                                           class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                                    @error('newPhoto') <span class="text-rose-400 text-[10px]">{{ $message }}</span> @enderror

                                    <input type="text" wire:model="photoCaption" placeholder="Keterangan foto (misal: Mockup UI Beranda)"
                                           class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-200">

                                    <div wire:loading wire:target="newPhoto" class="text-[10px] text-indigo-400">
                                        <i class="fa-solid fa-spinner fa-spin"></i> Mengunggah file preview...
                                    </div>

                                    @if($newPhoto)
                                        <div class="mt-2">
                                            <img src="{{ $newPhoto->temporaryUrl() }}" class="h-24 w-auto rounded-lg border border-indigo-500/40 object-cover">
                                        </div>
                                    @endif

                                    <button type="button" wire:click="uploadPhoto"
                                            class="w-full py-1.5 rounded-lg text-xs font-bold bg-cyan-600 hover:bg-cyan-500 text-white flex items-center justify-center gap-1.5 cursor-pointer">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Unggah Foto ke Storage</span>
                                    </button>
                                </div>
                            @endif

                            <!-- Photos Grid -->
                            <div class="grid grid-cols-2 gap-2 max-h-56 overflow-y-auto pr-1">
                                @forelse($selectedLead->photos as $photo)
                                    <div class="group relative rounded-xl overflow-hidden border border-slate-800 bg-slate-950">
                                        <a href="{{ $photo->url }}" target="_blank">
                                            <img src="{{ $photo->url }}" alt="{{ $photo->caption }}" class="w-full h-24 object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                        <div class="p-1.5 bg-slate-950/90 text-[10px]">
                                            <p class="font-medium text-slate-200 truncate">{{ $photo->caption ?: 'Dokumentasi' }}</p>
                                            <p class="text-slate-500 text-[9px]">{{ $photo->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 p-6 text-center text-slate-600 text-xs">
                                        Belum ada foto dokumentasi diunggah.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Progress Logs History -->
                        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800">
                            <h4 class="text-sm font-bold text-white flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
                                <span>Riwayat Aktivitas Pengerjaan</span>
                            </h4>

                            <div class="space-y-3 max-h-52 overflow-y-auto pr-1">
                                @forelse($selectedLead->logs as $log)
                                    <div class="relative pl-5 pb-1 border-l border-slate-800 last:border-0 text-xs">
                                        <div class="absolute -left-1 top-1 w-2 h-2 rounded-full bg-indigo-500 ring-2 ring-slate-900"></div>
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="font-semibold text-slate-300">{{ $log->user->name ?? 'User' }}</span>
                                            <span class="font-mono text-indigo-400 font-bold">{{ $log->progress }}%</span>
                                        </div>
                                        <p class="text-slate-400 text-[11px] mt-0.5">{{ $log->note }}</p>
                                        <span class="text-[9px] text-slate-600 mt-0.5 block">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-600 text-center py-4">Belum ada riwayat tercatat.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Drawer -->
                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button wire:click="closeDrawer" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 cursor-pointer">
                        Selesai & Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
