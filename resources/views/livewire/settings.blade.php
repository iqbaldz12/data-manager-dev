<div class="space-y-6">
    <x-slot name="header">Pengaturan & Manajemen Role</x-slot>

    <!-- Top Banner -->
    <div class="glass-panel p-5 sm:p-6 rounded-2xl">
        <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-sliders text-indigo-400"></i>
            <span>Pengaturan Akun & Kontrol Akses (RBAC)</span>
        </h2>
        <p class="text-xs text-slate-400 mt-1">
            Ubah profil akun atau uji coba perpindahan hak akses antara Owner, Marketing, dan Developer secara real-time.
        </p>
    </div>

    <!-- Live RBAC Role Switcher & Matrix -->
    <div class="glass-panel p-5 sm:p-6 rounded-2xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-indigo-400"></i>
                    <span>Uji Coba Hak Akses Role (RBAC Switcher)</span>
                </h3>
                <p class="text-xs text-slate-400">
                    Klik tombol di bawah untuk beralih peran dan melihat bagaimana navigasi serta hak akses berubah secara langsung.
                </p>
            </div>
            <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                Peran Aktif: {{ strtoupper($user->role) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <!-- Role 1: OWNER -->
            <div class="glass-card p-5 rounded-2xl border {{ $user->isOwner() ? 'border-amber-500/70 bg-amber-500/5 shadow-lg shadow-amber-950/30 ring-1 ring-amber-500/40' : 'border-slate-800' }} flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-crown"></i>
                        </span>
                        @if($user->isOwner())
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                SEDANG DIGUNAKAN
                            </span>
                        @endif
                    </div>
                    <h4 class="text-base font-bold text-white">Owner (Pemilik Bisnis)</h4>
                    <p class="text-xs text-slate-400 mt-1 mb-4">
                        Hak akses penuh ke seluruh data, analitik, penghapusan, dan manajemen teknis.
                    </p>
                    <ul class="text-xs space-y-1.5 text-slate-300 mb-5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Lihat seluruh 259 prospek</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Edit & ubah status pipeline</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Tambah & hapus data prospek</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Upload progress & foto delivery</li>
                    </ul>
                </div>

                <button type="button"
                        wire:click="switchRole('owner')"
                        class="w-full py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $user->isOwner() ? 'bg-amber-500/30 text-amber-200 border border-amber-500/50 cursor-default' : 'bg-slate-800 hover:bg-amber-600 hover:text-white text-slate-300' }}">
                    {{ $user->isOwner() ? 'Role Aktif Saat Ini' : 'Beralih ke Role Owner' }}
                </button>
            </div>

            <!-- Role 2: MARKETING -->
            <div class="glass-card p-5 rounded-2xl border {{ $user->isMarketing() ? 'border-indigo-500/70 bg-indigo-500/5 shadow-lg shadow-indigo-950/30 ring-1 ring-indigo-500/40' : 'border-slate-800' }} flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-bullhorn"></i>
                        </span>
                        @if($user->isMarketing())
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">
                                SEDANG DIGUNAKAN
                            </span>
                        @endif
                    </div>
                    <h4 class="text-base font-bold text-white">Marketing</h4>
                    <p class="text-xs text-slate-400 mt-1 mb-4">
                        Fokus pada prospek, outreach WhatsApp, dan pembaruan alur sales funnel.
                    </p>
                    <ul class="text-xs space-y-1.5 text-slate-300 mb-5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Lihat seluruh 259 prospek</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Edit & ubah status pipeline</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Kirim pesan WhatsApp langsung</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-xmark text-rose-400 text-[10px]"></i> Tidak bisa upload progress dev</li>
                    </ul>
                </div>

                <button type="button"
                        wire:click="switchRole('marketing')"
                        class="w-full py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $user->isMarketing() ? 'bg-indigo-500/30 text-indigo-200 border border-indigo-500/50 cursor-default' : 'bg-slate-800 hover:bg-indigo-600 hover:text-white text-slate-300' }}">
                    {{ $user->isMarketing() ? 'Role Aktif Saat Ini' : 'Beralih ke Role Marketing' }}
                </button>
            </div>

            <!-- Role 3: DEVELOPER -->
            <div class="glass-card p-5 rounded-2xl border {{ $user->isDeveloper() ? 'border-emerald-500/70 bg-emerald-500/5 shadow-lg shadow-emerald-950/30 ring-1 ring-emerald-500/40' : 'border-slate-800' }} flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-code"></i>
                        </span>
                        @if($user->isDeveloper())
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                SEDANG DIGUNAKAN
                            </span>
                        @endif
                    </div>
                    <h4 class="text-base font-bold text-white">Developer</h4>
                    <p class="text-xs text-slate-400 mt-1 mb-4">
                        Fokus pada eksekusi teknis web, penyelesaian 6 checklist, dan dokumentasi progress.
                    </p>
                    <ul class="text-xs space-y-1.5 text-slate-300 mb-5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> HANYA lihat lead yang dihubungi</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-xmark text-rose-400 text-[10px]"></i> Tidak bisa ubah status prospek</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Checklist 6 tahapan web</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Upload progress % & foto preview</li>
                    </ul>
                </div>

                <button type="button"
                        wire:click="switchRole('developer')"
                        class="w-full py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $user->isDeveloper() ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-500/50 cursor-default' : 'bg-slate-800 hover:bg-emerald-600 hover:text-white text-slate-300' }}">
                    {{ $user->isDeveloper() ? 'Role Aktif Saat Ini' : 'Beralih ke Role Developer' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Profil Akun & Informasi Sistem -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Edit Profil (6 Cols) -->
        <div class="lg:col-span-6 glass-panel p-5 sm:p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-indigo-400"></i>
                <span>Profil Akun Anda</span>
            </h3>

            <form wire:submit="updateProfile" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" wire:model="name" required
                           class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-indigo-500 focus:outline-hidden">
                    @error('name') <span class="text-rose-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Alamat Email</label>
                    <input type="email" wire:model="email" required
                           class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:border-indigo-500 focus:outline-hidden">
                    @error('email') <span class="text-rose-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Peran Akun (Role)</label>
                    <input type="text" value="{{ strtoupper($user->role) }}" disabled
                           class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 font-mono font-bold cursor-not-allowed">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="py-2.5 px-5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/30 transition-all cursor-pointer">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- System Diagnostics (6 Cols) -->
        <div class="lg:col-span-6 glass-panel p-5 sm:p-6 rounded-2xl flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-server text-cyan-400"></i>
                    <span>Diagnostik & Stack Codexa.id</span>
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Framework</span>
                        <span class="font-mono font-bold text-indigo-400">Laravel 13.34.0</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">TALL Stack</span>
                        <span class="font-mono text-slate-200">Tailwind CSS 4, Alpine, Livewire 3/4</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Database & Data Asli CSV</span>
                        <span class="font-mono text-emerald-400 font-bold">{{ $leadsCount }} Leads (Purwokerto)</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Checklist Tasks & Foto</span>
                        <span class="font-mono text-purple-300">{{ $tasksCount }} Tasks &bull; {{ $photosCount }} Foto</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Data Source File</span>
                        <span class="font-mono text-[10px] text-slate-400 truncate max-w-[200px]">perusahaan_tanpa_web...csv</span>
                    </div>
                </div>
            </div>

            <!-- Reseed Database (Owner Only) -->
            <div class="mt-6 pt-4 border-t border-slate-800/80">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-200 block">Reset / Sinkronisasi CSV</span>
                        <span class="text-[11px] text-slate-500">Muat ulang data asli 259 leads dari CSV</span>
                    </div>
                    @if($user->isOwner())
                        <button type="button"
                                wire:click="reseedCsvData"
                                wire:confirm="Apakah Anda yakin ingin memuat ulang 259 leads dari CSV?"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="fa-solid fa-arrows-rotate text-indigo-400"></i>
                            <span>Reseed Data</span>
                        </button>
                    @else
                        <span class="text-[10px] text-slate-500 italic">Khusus Role Owner</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
