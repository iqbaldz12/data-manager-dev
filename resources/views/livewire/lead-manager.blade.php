<div class="space-y-6">
    <x-slot name="header">Data Prospek Purwokerto</x-slot>

    <!-- Top Action & Search Bar -->
    <div class="glass-panel p-4 sm:p-6 rounded-2xl space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-indigo-400"></i>
                    <span>Database Prospek & Klien</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        {{ $totalCount }} Leads
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    @if($isDev)
                        Mode Developer: Menampilkan prospek yang telah dihubungi & aktif.
                    @else
                        259 Data Nyata Usaha Purwokerto — Ganti status = <strong class="text-amber-300">wajib upload bukti foto chat</strong> sebagai dokumentasi
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <button wire:click="exportCsv"
                        class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 transition-all flex items-center gap-2 cursor-pointer shadow-xs">
                    <i class="fa-solid fa-file-csv text-emerald-400"></i>
                    <span>Ekspor CSV</span>
                </button>

                @if(! $isDev)
                    <button wire:click="openCreateModal"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-600/30 flex items-center gap-2 transition-all cursor-pointer">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Prospek</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 pt-2 border-t border-slate-800/80">
            <div class="sm:col-span-2 md:col-span-1 lg:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama usaha, alamat, no telepon..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-950/70 border border-slate-800 text-xs text-slate-200 placeholder-slate-500 focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <select wire:model.live="category"
                    class="w-full px-3 py-2 rounded-xl bg-slate-950/70 border border-slate-800 text-xs text-slate-200 focus:outline-hidden focus:border-indigo-500">
                <option value="all">Semua Kategori (8)</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <select wire:model.live="status"
                    class="w-full px-3 py-2 rounded-xl bg-slate-950/70 border border-slate-800 text-xs text-slate-200 focus:outline-hidden focus:border-indigo-500">
                <option value="all">Semua Status</option>
                @foreach($statuses as $st)
                    @if(! ($isDev && in_array($st, ['Belum Dihubungi', 'Batal'])))
                        <option value="{{ $st }}">{{ $st }}</option>
                    @endif
                @endforeach
            </select>

            <select wire:model.live="sortBy"
                    class="w-full px-3 py-2 rounded-xl bg-slate-950/70 border border-slate-800 text-xs text-slate-200 focus:outline-hidden focus:border-indigo-500">
                <option value="rating_desc">Rating Tertinggi</option>
                <option value="reviews_desc">Ulasan Terbanyak</option>
                <option value="name_asc">Nama (A - Z)</option>
                <option value="updated_desc">Terakhir Diperbarui</option>
            </select>
        </div>
    </div>

    <!-- ─── PROOF UPLOAD BANNER (info reminder) ─── -->
    @if(! $isDev)
        <div class="flex items-start gap-3 p-4 rounded-2xl bg-amber-500/8 border border-amber-500/25 text-xs text-amber-300">
            <i class="fa-solid fa-camera mt-0.5 text-amber-400 text-sm shrink-0"></i>
            <div>
                <span class="font-bold text-amber-200">Dokumentasi Bukti Status Wajib:</span>
                Setiap kali mengubah status prospek (WA Terkirim, Follow Up, Negosiasi, Deal, Batal) akan muncul popup untuk upload <strong>screenshot / foto bukti chat</strong> sebagai dokumentasi teraudit.
            </div>
        </div>
    @endif

    <!-- Table (Desktop) / Cards (Mobile) -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-slate-800/80">

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-800/90 bg-slate-950/60 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Nama Usaha & Kategori</th>
                        <th class="py-3.5 px-4">Alamat</th>
                        <th class="py-3.5 px-4">Rating</th>
                        <th class="py-3.5 px-4">Kontak & WA</th>
                        <th class="py-3.5 px-4">Status + Bukti</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-900/50 transition-colors group">
                            <!-- Name & Category -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-sm text-slate-200 group-hover:text-indigo-300 transition-colors">{{ $lead->name }}</div>
                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $lead->getCategoryColorClass() }}">
                                        {{ $lead->category }}
                                    </span>
                                    @if($lead->progress > 0)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono bg-purple-500/10 text-purple-300 border border-purple-500/20">
                                            {{ $lead->progress }}%
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Address -->
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-slate-400 text-xs truncate" title="{{ $lead->address }}">
                                    {{ $lead->address ?: 'Purwokerto, Banyumas' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($lead->maps_link)
                                        <a href="{{ $lead->maps_link }}" target="_blank"
                                           class="text-[11px] text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                                            <i class="fa-solid fa-map-pin text-[10px]"></i> Maps
                                        </a>
                                    @endif
                                    @if($lead->instagram_search)
                                        <a href="{{ $lead->instagram_search }}" target="_blank"
                                           class="text-[11px] text-fuchsia-400 hover:text-fuchsia-300 flex items-center gap-1">
                                            <i class="fa-brands fa-instagram text-[10px]"></i> IG
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Rating -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($lead->rating)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-amber-400 font-bold text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-star text-[11px]"></i>{{ number_format($lead->rating, 1) }}
                                        </span>
                                        <span class="text-slate-500 text-[11px]">({{ $lead->reviews_count ?? 0 }})</span>
                                    </div>
                                @else
                                    <span class="text-slate-600">—</span>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($lead->phone)
                                    <p class="font-mono text-xs text-slate-300 mb-1">{{ $lead->phone }}</p>
                                @endif
                                @if($lead->clean_whatsapp_url)
                                    <a href="{{ $lead->clean_whatsapp_url }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500/25 border border-emerald-500/30 transition-all">
                                        <i class="fa-brands fa-whatsapp text-xs"></i> Kirim WA
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-600">Tidak ada no WA</span>
                                @endif
                            </td>

                            <!-- Status + Proof Count -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if(! $isDev)
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                                class="px-2.5 py-1 rounded-lg text-xs font-bold border flex items-center gap-1.5 cursor-pointer {{ $lead->getStatusColorClass() }}">
                                            <span>{{ $lead->status }}</span>
                                            <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                        </button>

                                        <div x-show="open" @click.away="open = false" x-cloak
                                             class="absolute left-0 mt-1 w-48 rounded-xl bg-slate-900 border border-slate-700 shadow-2xl p-1 z-30">
                                            @foreach($statuses as $st)
                                                <button type="button"
                                                        wire:click="requestStatusChange({{ $lead->id }}, '{{ $st }}')"
                                                        @click="open = false"
                                                        class="w-full text-left px-2.5 py-2 rounded-lg text-xs font-medium text-slate-300 hover:bg-slate-800 hover:text-white flex items-center justify-between gap-2 group">
                                                    <span>{{ $st }}</span>
                                                    <span class="flex items-center gap-1.5">
                                                        @if(in_array($st, ['WA Terkirim', 'Follow Up', 'Negosiasi', 'Deal / Won', 'Batal']))
                                                            <span title="Wajib upload bukti foto"
                                                                  class="text-[9px] px-1 py-0.5 rounded bg-amber-500/15 text-amber-400 border border-amber-500/25 opacity-70 group-hover:opacity-100">
                                                                <i class="fa-solid fa-camera"></i>
                                                            </span>
                                                        @endif
                                                        @if($lead->status === $st)
                                                            <i class="fa-solid fa-check text-indigo-400 text-[10px]"></i>
                                                        @endif
                                                    </span>
                                                </button>
                                            @endforeach
                                            <div class="mt-1 pt-1 border-t border-slate-800 px-2 py-1">
                                                <p class="text-[10px] text-slate-500 flex items-center gap-1">
                                                    <i class="fa-solid fa-camera text-amber-400"></i>
                                                    Status bertanda kamera wajib upload bukti foto
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border inline-block {{ $lead->getStatusColorClass() }}">
                                        {{ $lead->status }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button wire:click="openDetail({{ $lead->id }})"
                                            title="Lihat Detail & Riwayat Bukti"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700 transition-all cursor-pointer">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    @if(! $isDev)
                                        <button wire:click="openEditModal({{ $lead->id }})"
                                                title="Edit Data Prospek"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-300 bg-slate-800/80 hover:bg-slate-800 border border-slate-700 transition-all cursor-pointer">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                    @endif

                                    @if($user->isOwner())
                                        <button wire:click="confirmDelete({{ $lead->id }})"
                                                title="Hapus"
                                                class="p-1.5 rounded-lg text-rose-400 hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 transition-all cursor-pointer">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-600 block"></i>
                                Tidak ada data prospek yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-slate-800/80">
            @forelse($leads as $lead)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ $lead->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border {{ $lead->getCategoryColorClass() }}">{{ $lead->category }}</span>
                                @if($lead->rating)
                                    <span class="text-amber-400 font-bold text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($lead->rating, 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(! $isDev)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="px-2 py-1 rounded text-[11px] font-bold border flex items-center gap-1 {{ $lead->getStatusColorClass() }}">
                                    <span>{{ $lead->status }}</span>
                                    <i class="fa-solid fa-chevron-down text-[8px]"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute right-0 mt-1 w-44 rounded-xl bg-slate-900 border border-slate-700 shadow-2xl p-1 z-30">
                                    @foreach($statuses as $st)
                                        <button type="button"
                                                wire:click="requestStatusChange({{ $lead->id }}, '{{ $st }}')"
                                                @click="open = false"
                                                class="w-full text-left px-2 py-1.5 rounded text-xs text-slate-300 hover:bg-slate-800 flex items-center justify-between">
                                            <span>{{ $st }}</span>
                                            @if(in_array($st, ['WA Terkirim', 'Follow Up', 'Negosiasi', 'Deal / Won', 'Batal']))
                                                <i class="fa-solid fa-camera text-amber-400 text-[10px]"></i>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <span class="px-2 py-1 rounded text-[11px] font-bold border {{ $lead->getStatusColorClass() }}">{{ $lead->status }}</span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-400 line-clamp-2">{{ $lead->address }}</p>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-800/60">
                        <div class="flex items-center gap-2">
                            @if($lead->clean_whatsapp_url)
                                <a href="{{ $lead->clean_whatsapp_url }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp"></i> WA
                                </a>
                            @endif
                            @if($lead->maps_link)
                                <a href="{{ $lead->maps_link }}" target="_blank" class="p-1.5 rounded-lg text-xs bg-slate-800 text-slate-300 border border-slate-700">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </a>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button wire:click="openDetail({{ $lead->id }})" class="p-1.5 rounded-lg text-slate-300 bg-slate-800 border border-slate-700">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                            @if(! $isDev)
                                <button wire:click="openEditModal({{ $lead->id }})" class="p-1.5 rounded-lg text-indigo-300 bg-slate-800 border border-slate-700">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                            @endif
                            @if($user->isOwner())
                                <button wire:click="confirmDelete({{ $lead->id }})" class="p-1.5 rounded-lg text-rose-400 bg-rose-500/10 border border-rose-500/20">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 text-xs">Tidak ada data prospek.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
            {{ $leads->links() }}
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════
         MODAL: UPLOAD BUKTI FOTO STATUS
    ═══════════════════════════════════════════════════════ --}}
    @if($showProofModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" x-data>
            <div class="glass-panel w-full max-w-md rounded-3xl p-6 border border-amber-500/40 shadow-2xl shadow-amber-950/30 relative"
                 x-data="{ previewUrl: null }"
                 x-on:livewire-upload-start="$el.querySelectorAll('button[type=submit]').forEach(b => b.disabled = true)"
                 x-on:livewire-upload-finish="$el.querySelectorAll('button[type=submit]').forEach(b => b.disabled = false)">

                <!-- Header -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl shrink-0">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Upload Bukti Foto</h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Status baru:
                            <span class="font-bold text-amber-300">{{ $pendingStatusLabel }}</span>
                        </p>
                    </div>
                </div>

                <!-- Info box -->
                <div class="mb-4 p-3 rounded-xl bg-amber-500/8 border border-amber-500/20 text-xs text-amber-300 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                    <p>
                        Upload screenshot bukti chat WhatsApp, email, atau dokumentasi lain sebagai bukti bahwa status telah berubah.
                        <strong>Foto ini tersimpan permanen</strong> dan bisa dilihat di detail prospek.
                    </p>
                </div>

                <form wire:submit="saveStatusWithProof" class="space-y-4 text-xs">

                    <!-- ── SIAPA YANG CHAT ── -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tie text-indigo-400"></i>
                            <span>Yang Menghubungi Perusahaan Ini <span class="text-rose-400">*</span></span>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($marketingUsers as $mUser)
                                <button type="button"
                                        wire:click="$set('chattedById', {{ $mUser->id }})"
                                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border text-xs font-medium transition-all cursor-pointer
                                            {{ $chattedById == $mUser->id
                                                ? 'bg-indigo-600/30 border-indigo-500 text-white ring-1 ring-indigo-500'
                                                : 'bg-slate-900/70 border-slate-700 text-slate-300 hover:border-slate-600 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black shrink-0
                                        {{ $mUser->role === 'owner' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/40' }}">
                                        {{ strtoupper(substr($mUser->name, 0, 2)) }}
                                    </div>
                                    <div class="text-left min-w-0">
                                        <p class="truncate font-semibold text-[11px]">{{ explode(' ', $mUser->name)[0] }}</p>
                                        <p class="text-[10px] {{ $mUser->role === 'owner' ? 'text-amber-400' : 'text-slate-500' }} capitalize">{{ $mUser->role }}</p>
                                    </div>
                                    @if($chattedById == $mUser->id)
                                        <i class="fa-solid fa-circle-check text-indigo-400 ml-auto text-xs"></i>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        @if(! $chattedById)
                            <p class="mt-1 text-[11px] text-slate-500 flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up text-amber-400"></i> Pilih siapa yang sudah menghubungi perusahaan ini
                            </p>
                        @else
                            <p class="mt-1 text-[11px] text-emerald-400 flex items-center gap-1">
                                <i class="fa-solid fa-check-circle"></i>
                                @php $selected = $marketingUsers->firstWhere('id', $chattedById); @endphp
                                {{ $selected?->name ?? 'Sudah dipilih' }} yang menghubungi
                            </p>
                        @endif
                    </div>
                    <!-- Photo Upload -->
                    <div x-data="{ dragging: false }">
                        <label class="block font-semibold text-slate-300 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-image text-slate-500"></i>
                            <span>Bukti Foto Chat / Screenshot <span class="text-rose-400">*</span></span>
                        </label>

                        <div
                            class="relative border-2 border-dashed rounded-2xl p-5 text-center transition-all"
                            :class="dragging ? 'border-amber-400 bg-amber-500/10' : 'border-slate-700 bg-slate-950/60 hover:border-slate-600'"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false">

                            <input type="file"
                                   wire:model="proofPhoto"
                                   accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   x-on:change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = e => previewUrl = e.target.result;
                                            reader.readAsDataURL(file);
                                        }
                                   ">

                            <template x-if="!previewUrl">
                                <div class="pointer-events-none">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-500 mb-2 block"></i>
                                    <p class="text-slate-400 font-medium">Klik atau drag foto di sini</p>
                                    <p class="text-slate-600 text-[11px] mt-1">JPG, PNG, WebP — Maks 10MB</p>
                                </div>
                            </template>

                            <template x-if="previewUrl">
                                <div class="pointer-events-none">
                                    <img :src="previewUrl" class="max-h-44 mx-auto rounded-xl border border-amber-500/30 object-cover shadow-lg">
                                    <p class="text-[11px] text-amber-400 mt-2 flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-check-circle"></i> Foto siap diupload
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Loading indicator -->
                        <div wire:loading wire:target="proofPhoto" class="mt-2 text-[11px] text-indigo-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-spinner fa-spin"></i> Memuat pratinjau foto...
                        </div>

                        @error('proofPhoto')
                            <p class="mt-1 text-rose-400 text-[11px] flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Optional Note -->
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-note-sticky text-slate-500"></i>
                            <span>Catatan Tambahan <span class="text-slate-500 font-normal">(opsional)</span></span>
                        </label>
                        <textarea wire:model="proofNote"
                                  rows="2"
                                  placeholder="Contoh: Chat WA dibalas jam 14.30, tertarik tanya harga..."
                                  class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-amber-500 focus:outline-hidden resize-none leading-relaxed"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-800">
                        <button type="button"
                                wire:click="cancelProofModal"
                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold text-slate-400 bg-slate-900 hover:bg-slate-800 border border-slate-700 cursor-pointer transition-all">
                            Batal (Jangan Ubah Status)
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 shadow-lg shadow-amber-600/30 flex items-center justify-center gap-2 cursor-pointer transition-all">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Simpan Status + Bukti</span>
                        </button>
                    </div>
                </form>

                <!-- Loading overlay -->
                <div wire:loading wire:target="saveStatusWithProof"
                     class="absolute inset-0 rounded-3xl bg-slate-950/80 backdrop-blur-sm flex flex-col items-center justify-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full border-2 border-amber-400 border-t-transparent animate-spin"></div>
                    <p class="text-xs text-amber-300 font-semibold">Menyimpan bukti & memperbarui status...</p>
                </div>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════
         MODAL: DETAIL LEAD (dengan Riwayat Bukti Foto)
    ═══════════════════════════════════════════════════════ --}}
    @if($showDetailModal && $selectedLead)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="glass-panel w-full max-w-2xl max-h-[92vh] overflow-y-auto rounded-3xl p-6 border border-slate-700 shadow-2xl relative">
                <button wire:click="closeDetail" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-white bg-slate-800 border border-slate-700 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $selectedLead->getCategoryColorClass() }}">{{ $selectedLead->category }}</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $selectedLead->getStatusColorClass() }}">{{ $selectedLead->status }}</span>
                    @if($selectedLead->rating)
                        <span class="text-amber-400 font-bold text-xs flex items-center gap-1">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($selectedLead->rating, 1) }}
                            <span class="text-slate-500 font-normal">({{ $selectedLead->reviews_count }} ulasan)</span>
                        </span>
                    @endif
                </div>

                <h2 class="text-xl font-black text-white">{{ $selectedLead->name }}</h2>
                <p class="text-xs text-slate-400 mt-1 flex items-start gap-1.5">
                    <i class="fa-solid fa-location-dot text-indigo-400 mt-0.5"></i>
                    <span>{{ $selectedLead->address }}</span>
                </p>

                <!-- Quick Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-5">
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="text-[10px] text-slate-500 uppercase font-semibold block">Rating</span>
                        <span class="text-base font-bold text-amber-400 flex items-center justify-center gap-1 mt-0.5">
                            <i class="fa-solid fa-star text-xs"></i> {{ $selectedLead->rating ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="text-[10px] text-slate-500 uppercase font-semibold block">Ulasan</span>
                        <span class="text-base font-bold text-slate-200 mt-0.5 block">{{ $selectedLead->reviews_count ?? 0 }}</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="text-[10px] text-slate-500 uppercase font-semibold block">Progress</span>
                        <span class="text-base font-bold text-purple-300 mt-0.5 block">{{ $selectedLead->progress }}%</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-center">
                        <span class="text-[10px] text-slate-500 uppercase font-semibold block">Bukti Foto</span>
                        <span class="text-base font-bold text-amber-300 mt-0.5 block">{{ $selectedLead->proofs->count() }} foto</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-2.5 flex-wrap my-4">
                    @if($selectedLead->clean_whatsapp_url)
                        <a href="{{ $selectedLead->clean_whatsapp_url }}" target="_blank"
                           class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/30">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi WhatsApp
                        </a>
                    @endif
                    @if($selectedLead->maps_link)
                        <a href="{{ $selectedLead->maps_link }}" target="_blank"
                           class="py-2.5 px-4 rounded-xl text-xs font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-indigo-400"></i> Buka Maps
                        </a>
                    @endif
                </div>

                <!-- Notes -->
                @if($selectedLead->notes)
                    <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 mb-5">
                        <h4 class="text-xs font-bold uppercase text-slate-400 mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-note-sticky text-amber-400"></i> Catatan Prospek
                        </h4>
                        <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $selectedLead->notes }}</p>
                    </div>
                @endif

                <!-- ── RIWAYAT BUKTI FOTO STATUS (ini yang baru!) ── -->
                @if($selectedLead->proofs->count() > 0)
                    <div class="p-5 rounded-2xl bg-amber-500/5 border border-amber-500/25 mb-5">
                        <h4 class="text-xs font-bold uppercase text-amber-300 mb-3 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-camera text-amber-400"></i>
                                Riwayat Bukti Foto Perubahan Status
                            </span>
                            <span class="font-mono text-amber-400">{{ $selectedLead->proofs->count() }} Bukti</span>
                        </h4>

                        <div class="space-y-4">
                            @foreach($selectedLead->proofs as $proof)
                                <div class="flex gap-4 p-3 rounded-xl bg-slate-900/50 border border-slate-800">
                                    <!-- Thumbnail -->
                                    <a href="{{ $proof->url }}" target="_blank" class="shrink-0">
                                        <img src="{{ $proof->url }}"
                                             alt="Bukti {{ $proof->status }}"
                                             class="w-24 h-20 sm:w-28 sm:h-24 object-cover rounded-xl border border-amber-500/30 hover:scale-105 transition-transform cursor-zoom-in">
                                    </a>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                                {{ $proof->status }}
                                            </span>
                                            <span class="text-[10px] text-slate-500">{{ $proof->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>

                                        @if($proof->notes)
                                            <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">{{ $proof->notes }}</p>
                                        @endif

                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            @if($proof->chattedBy && $proof->chattedBy->id !== $proof->user?->id)
                                                <span class="flex items-center gap-1 text-[11px] text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 rounded-lg px-2 py-1">
                                                    <i class="fa-solid fa-comments text-[10px]"></i>
                                                    <span>{{ $proof->chattedBy->name }}</span>
                                                    <span class="text-indigo-500">yang chat</span>
                                                </span>
                                            @elseif($proof->chattedBy)
                                                <span class="flex items-center gap-1 text-[11px] text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 rounded-lg px-2 py-1">
                                                    <i class="fa-solid fa-comments text-[10px]"></i>
                                                    <span>{{ $proof->chattedBy->name }}</span>
                                                </span>
                                            @endif
                                            <span class="flex items-center gap-1 text-[11px] text-slate-500">
                                                <i class="fa-solid fa-cloud-arrow-up text-[10px]"></i>
                                                <span>Upload: {{ $proof->user->name ?? 'Tim' }}</span>
                                                <span class="text-slate-600">&bull;</span>
                                                <span>{{ $proof->created_at->diffForHumans() }}</span>
                                            </span>
                                        </div>

                                        <a href="{{ $proof->url }}" target="_blank"
                                           class="inline-flex items-center gap-1 mt-2 text-[11px] text-indigo-400 hover:text-indigo-300 font-medium">
                                            <i class="fa-solid fa-expand text-[10px]"></i> Lihat Foto Penuh
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-900/40 border border-slate-800 mb-5 text-center">
                        <i class="fa-solid fa-camera text-3xl text-slate-700 mb-1.5 block"></i>
                        <p class="text-xs text-slate-500">Belum ada bukti foto status. Bukti akan muncul di sini setelah marketing mengubah status prospek.</p>
                    </div>
                @endif

                <!-- Checklist Preview (if Deal) -->
                @if($selectedLead->tasks->count() > 0)
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                        <h4 class="text-xs font-bold uppercase text-slate-400 mb-2.5 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-list-check text-purple-400"></i>
                                Checklist Pengerjaan Website
                            </span>
                            <span class="font-mono text-purple-400">{{ $selectedLead->tasks->where('is_done', true)->count() }}/6</span>
                        </h4>
                        <div class="space-y-1.5">
                            @foreach($selectedLead->tasks as $task)
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fa-solid {{ $task->is_done ? 'fa-circle-check text-emerald-400' : 'fa-circle text-slate-600' }}"></i>
                                    <span class="{{ $task->is_done ? 'line-through text-slate-400' : 'text-slate-200' }}">{{ $task->label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <button wire:click="closeDetail" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════
         MODAL: CREATE
    ═══════════════════════════════════════════════════════ --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="glass-panel w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl p-6 border border-slate-700 shadow-2xl relative">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-indigo-400"></i> Tambah Prospek Baru
                    </h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form wire:submit="saveCreate" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nama Usaha *</label>
                        <input type="text" wire:model="formName" required placeholder="Contoh: Kopi Janji Kita Purwokerto"
                               class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500 focus:outline-hidden">
                        @error('formName') <span class="text-rose-400 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Kategori *</label>
                            <select wire:model="formCategory" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                                @foreach($categories as $cat)<option value="{{ $cat }}">{{ $cat }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Status Awal *</label>
                            <select wire:model="formStatus" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                                @foreach($statuses as $st)<option value="{{ $st }}">{{ $st }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Alamat</label>
                        <textarea wire:model="formAddress" rows="2" placeholder="Jl. HR Boenyamin No..."
                                  class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500 focus:outline-hidden"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Telepon</label>
                            <input type="text" wire:model="formPhone" placeholder="0812-xxxx-xxxx"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Link WhatsApp</label>
                            <input type="text" wire:model="formWhatsapp" placeholder="https://wa.me/62..."
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Rating (0-5)</label>
                            <input type="number" step="0.1" wire:model="formRating" placeholder="4.8"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Jumlah Ulasan</label>
                            <input type="number" wire:model="formReviewsCount" placeholder="150"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Catatan</label>
                        <textarea wire:model="formNotes" rows="2"
                                  class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                                class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white cursor-pointer">Batal</button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-600/30 cursor-pointer">
                            Simpan Prospek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════
         MODAL: EDIT
    ═══════════════════════════════════════════════════════ --}}
    @if($showEditModal && $selectedLead)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="glass-panel w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl p-6 border border-slate-700 shadow-2xl relative">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-indigo-400"></i> Edit Data Prospek
                    </h3>
                    <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form wire:submit="saveEdit" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nama Usaha *</label>
                        <input type="text" wire:model="formName" required
                               class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500 focus:outline-hidden">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Kategori</label>
                            <select wire:model="formCategory" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200">
                                @foreach($categories as $cat)<option value="{{ $cat }}">{{ $cat }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Status</label>
                            <select wire:model="formStatus" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200">
                                @foreach($statuses as $st)<option value="{{ $st }}">{{ $st }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Alamat</label>
                        <textarea wire:model="formAddress" rows="2"
                                  class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Telepon</label>
                            <input type="text" wire:model="formPhone"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">WhatsApp</label>
                            <input type="text" wire:model="formWhatsapp"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Catatan</label>
                        <textarea wire:model="formNotes" rows="2"
                                  class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2 border-t border-slate-800">
                        <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white cursor-pointer">Batal</button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-600/30 cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════
         MODAL: DELETE CONFIRM
    ═══════════════════════════════════════════════════════ --}}
    @if($showDeleteModal && $selectedLead)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="glass-panel w-full max-w-md rounded-3xl p-6 border border-rose-500/30 shadow-2xl text-center">
                <div class="w-12 h-12 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-400 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Hapus Data Prospek?</h3>
                <p class="text-xs text-slate-400 mt-1">
                    Hapus <strong class="text-rose-300">{{ $selectedLead->name }}</strong> beserta seluruh bukti foto & task terkait. Tidak dapat dibatalkan.
                </p>
                <div class="flex items-center justify-center gap-2 mt-6">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 rounded-xl text-xs bg-slate-800 text-slate-300 hover:text-white cursor-pointer">Batal</button>
                    <button wire:click="deleteLead"
                            class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-600/30 cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
