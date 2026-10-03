<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    <title>Masuk — Codexa.id Data Manager</title>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Aurora Background Glow Orbs -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-600/20 blur-[130px] animate-pulse"></div>
        <div class="absolute top-1/2 -right-32 w-[32rem] h-[32rem] rounded-full bg-purple-600/15 blur-[150px]"></div>
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full bg-cyan-600/15 blur-[120px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md" x-data="{
        fillUser(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    }">
        <!-- Top Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-500 shadow-xl shadow-indigo-500/30 ring-1 ring-white/20 mb-3 group hover:scale-105 transition-transform duration-300">
                <i class="fa-solid fa-bolt text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center justify-center gap-2">
                <span>Codexa<span class="text-indigo-400">.id</span></span>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">CRM Suite</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Prospecting Manager Freelance Web Developer Purwokerto</p>
        </div>

        <!-- Glassmorphism Card -->
        <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-800/80 shadow-2xl shadow-black/60 bg-slate-900/65 backdrop-blur-2xl">
            <!-- Header inside card -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>Masuk ke Dashboard</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Akses data prospek & manajemen delivery project</p>
            </div>

            <!-- Validation Errors or Session Status -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5"></i>
                    <div class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-slate-500"></i>
                        <span>Email Akun</span>
                    </label>
                    <div class="relative">
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email', 'owner@codexa.id') }}"
                               required
                               autofocus
                               placeholder="nama@codexa.id"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 text-slate-200 text-sm placeholder-slate-500 focus:outline-hidden focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div x-data="{ showPass: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-xs font-semibold text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-slate-500"></i>
                            <span>Kata Sandi</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input id="password"
                               :type="showPass ? 'text' : 'password'"
                               name="password"
                               value="password"
                               required
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 pr-10 rounded-xl bg-slate-950/70 border border-slate-800 text-slate-200 text-sm placeholder-slate-500 focus:outline-hidden focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <button type="button"
                                @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300">
                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500/30">
                        <span class="text-xs text-slate-400">Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Codexa Manager</span>
                </button>
            </form>

            <!-- Quick Demo 1-Click Fillers -->
            <div class="mt-6 pt-5 border-t border-slate-800/80">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles text-indigo-400"></i> Akun Cepat Uji Coba:
                    </span>
                    <span class="text-[10px] text-slate-500">Klik untuk isi</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button"
                            @click="fillUser('owner@codexa.id')"
                            class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 text-left transition-all text-xs group cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-crown text-[10px]"></i> Owner</span>
                            <i class="fa-solid fa-arrow-right text-[9px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <p class="text-[10px] text-amber-400/70 truncate mt-0.5">Akses Penuh</p>
                    </button>

                    <button type="button"
                            @click="fillUser('marketing@codexa.id')"
                            class="p-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-left transition-all text-xs group cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-bullhorn text-[10px]"></i> Mkt</span>
                            <i class="fa-solid fa-arrow-right text-[9px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <p class="text-[10px] text-indigo-400/70 truncate mt-0.5">Kelola Lead</p>
                    </button>

                    <button type="button"
                            @click="fillUser('developer@codexa.id')"
                            class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-left transition-all text-xs group cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-code text-[10px]"></i> Dev</span>
                            <i class="fa-solid fa-arrow-right text-[9px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <p class="text-[10px] text-emerald-400/70 truncate mt-0.5">Progress Dev</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer / Metadata Info -->
        <div class="text-center mt-6 text-xs text-slate-500 space-y-1">
            <p>Codexa.id &bull; Freelance Web Developer Prospecting & Project Tracking</p>
            <p class="text-[11px] text-slate-600">Database berisi 259 Usaha Purwokerto (Data Asli CSV)</p>
        </div>
    </div>

</body>
</html>
