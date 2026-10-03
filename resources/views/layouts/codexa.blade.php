<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    <title>{{ isset($title) ? $title . ' — Codexa.id Data Manager' : 'Codexa.id — Freelance Web Dev Prospecting & CRM' }}</title>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans selection:bg-indigo-500 selection:text-white relative overflow-x-hidden" x-data="{ mobileMenuOpen: false }">

    <!-- Aurora Background Glow Orbs -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-600/15 blur-[120px]"></div>
        <div class="absolute top-1/3 -right-40 w-[30rem] h-[30rem] rounded-full bg-purple-600/12 blur-[140px]"></div>
        <div class="absolute -bottom-40 left-1/4 w-[28rem] h-[28rem] rounded-full bg-cyan-600/10 blur-[130px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:28px_28px] opacity-20"></div>
    </div>

    <div class="relative z-10 flex min-h-screen">
        <!-- Sidebar for Desktop -->
        <aside class="hidden lg:flex lg:flex-col lg:w-72 fixed inset-y-0 left-0 z-30 glass-panel border-r border-slate-800/80 bg-slate-950/80 backdrop-blur-2xl">
            <!-- Brand Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/70">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/25 ring-1 ring-white/20 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-bolt text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-black tracking-tight bg-gradient-to-r from-white via-indigo-100 to-indigo-300 bg-clip-text text-transparent">Codexa<span class="text-indigo-400">.id</span></span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">PRO</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium tracking-wide">Data & CRM Manager</p>
                    </div>
                </a>
            </div>

            <!-- Current User & Role Badge -->
            <div class="p-4 mx-4 my-4 rounded-xl glass-card border border-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-indigo-400 font-bold text-sm shadow-inner">
                        {{ auth()->user() ? auth()->user()->initials() : 'CX' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            @php
                                $role = auth()->user()->role ?? 'marketing';
                            @endphp
                            @if($role === 'owner')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/15 text-amber-300 border border-amber-500/30 shadow-xs">
                                    <i class="fa-solid fa-crown text-[9px]"></i> Owner
                                </span>
                            @elseif($role === 'marketing')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 shadow-xs">
                                    <i class="fa-solid fa-bullhorn text-[9px]"></i> Marketing
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 shadow-xs">
                                    <i class="fa-solid fa-code text-[9px]"></i> Developer
                                </span>
                            @endif
                            <span class="text-[10px] text-slate-500">Purwokerto</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
                <div class="px-3 pt-2 pb-1 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    Menu Utama
                </div>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-950/50' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    <span>Dashboard KPI</span>
                </a>

                <a href="{{ route('leads.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('leads.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-950/50' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <i class="fa-solid fa-address-book w-5 text-center text-base {{ request()->routeIs('leads.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    <div class="flex-1 flex items-center justify-between">
                        <span>Data Prospek</span>
                        <span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-800 text-slate-300 border border-slate-700">259</span>
                    </div>
                </a>

                <a href="{{ route('projects.board') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('projects.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-950/50' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <i class="fa-solid fa-diagram-project w-5 text-center text-base {{ request()->routeIs('projects.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    <span>Project Board</span>
                </a>

                <div class="px-3 pt-6 pb-1 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    Sistem & Akun
                </div>

                <a href="{{ route('settings.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-950/50' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <i class="fa-solid fa-sliders w-5 text-center text-base {{ request()->routeIs('settings.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    <span>Pengaturan & Role</span>
                </a>
            </nav>

            <!-- Fast Role Switcher Box (Bottom of Sidebar for testing RBAC) -->
            <div class="p-4 mx-4 mb-3 rounded-xl bg-slate-900/40 border border-slate-800/80">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-indigo-400"></i> Cepat Ganti Role
                    </span>
                    <span class="text-[10px] text-slate-500">Live RBAC</span>
                </div>
                <div class="grid grid-cols-3 gap-1.5">
                    <form method="POST" action="{{ route('role.switch', 'owner') }}" class="w-full">
                        @csrf
                        <button type="submit" title="Beralih ke Owner" class="w-full py-1.5 px-1 text-center rounded-lg text-[10px] font-bold transition-all {{ $role === 'owner' ? 'bg-amber-500/30 text-amber-300 border border-amber-500/50 shadow' : 'bg-slate-800/80 text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                            Owner
                        </button>
                    </form>
                    <form method="POST" action="{{ route('role.switch', 'marketing') }}" class="w-full">
                        @csrf
                        <button type="submit" title="Beralih ke Marketing" class="w-full py-1.5 px-1 text-center rounded-lg text-[10px] font-bold transition-all {{ $role === 'marketing' ? 'bg-indigo-500/30 text-indigo-300 border border-indigo-500/50 shadow' : 'bg-slate-800/80 text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                            Mkt
                        </button>
                    </form>
                    <form method="POST" action="{{ route('role.switch', 'developer') }}" class="w-full">
                        @csrf
                        <button type="submit" title="Beralih ke Developer" class="w-full py-1.5 px-1 text-center rounded-lg text-[10px] font-bold transition-all {{ $role === 'developer' ? 'bg-emerald-500/30 text-emerald-300 border border-emerald-500/50 shadow' : 'bg-slate-800/80 text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                            Dev
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer: Logout -->
            <div class="p-4 border-t border-slate-800/70">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 border border-rose-500/20 transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Drawer Overlay -->
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm lg:hidden"
             @click="mobileMenuOpen = false"></div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 glass-panel bg-slate-950/95 border-r border-slate-800 p-6 flex flex-col justify-between lg:hidden overflow-y-auto">
            <div>
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <span class="text-lg font-bold text-white">Codexa<span class="text-indigo-400">.id</span></span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-white rounded-lg bg-slate-900 border border-slate-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- User info in Mobile -->
                <div class="p-3 my-4 rounded-xl bg-slate-900/60 border border-slate-800">
                    <p class="text-sm font-semibold text-slate-200">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-xs text-indigo-400 capitalize">{{ auth()->user()->role ?? 'marketing' }} Role</p>
                </div>

                <nav class="space-y-1.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400' }}">
                        <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('leads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('leads.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400' }}">
                        <i class="fa-solid fa-address-book w-5"></i> Data Prospek (259)
                    </a>
                    <a href="{{ route('projects.board') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('projects.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400' }}">
                        <i class="fa-solid fa-diagram-project w-5"></i> Project Board
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400' }}">
                        <i class="fa-solid fa-sliders w-5"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- Role Switch Mobile -->
            <div class="pt-6 border-t border-slate-800">
                <p class="text-xs text-slate-400 font-medium mb-2">Ganti Role Cepat:</p>
                <div class="grid grid-cols-3 gap-1 mb-4">
                    <form method="POST" action="{{ route('role.switch', 'owner') }}">
                        @csrf
                        <button type="submit" class="w-full py-1 text-center text-xs rounded bg-slate-900 border border-slate-800 text-amber-300">Owner</button>
                    </form>
                    <form method="POST" action="{{ route('role.switch', 'marketing') }}">
                        @csrf
                        <button type="submit" class="w-full py-1 text-center text-xs rounded bg-slate-900 border border-slate-800 text-indigo-300">Mkt</button>
                    </form>
                    <form method="POST" action="{{ route('role.switch', 'developer') }}">
                        @csrf
                        <button type="submit" class="w-full py-1 text-center text-xs rounded bg-slate-900 border border-slate-800 text-emerald-300">Dev</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 rounded-xl text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-20 h-20 glass-panel border-b border-slate-800/80 bg-slate-950/70 backdrop-blur-xl px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <!-- Left: Mobile Menu Trigger + Breadcrumb/Title -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white bg-slate-900 border border-slate-800 focus:outline-hidden">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    <div>
                        <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white flex items-center gap-2">
                            <span>{{ $header ?? 'Dashboard' }}</span>
                        </h1>
                        <p class="text-xs text-slate-400 hidden sm:block">Purwokerto Prospecting & Web Development Delivery Platform</p>
                    </div>
                </div>

                <!-- Right: Status Indicators & Quick Actions -->
                <div class="flex items-center gap-3">
                    <!-- Live Data Pill -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-xs text-slate-300 shadow-inner">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-medium">259 Leads CSV</span>
                        <span class="text-slate-600">|</span>
                        <span class="text-indigo-400 font-semibold">Purwokerto</span>
                    </div>

                    <!-- Role Badge in Header -->
                    <div class="flex items-center gap-2">
                        @if($role === 'owner')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30 flex items-center gap-1.5">
                                <i class="fa-solid fa-crown text-xs"></i>
                                <span class="hidden sm:inline">Akses:</span> Owner
                            </span>
                        @elseif($role === 'marketing')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 flex items-center gap-1.5">
                                <i class="fa-solid fa-bullhorn text-xs"></i>
                                <span class="hidden sm:inline">Akses:</span> Marketing
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 flex items-center gap-1.5">
                                <i class="fa-solid fa-code text-xs"></i>
                                <span class="hidden sm:inline">Akses:</span> Developer
                            </span>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Flash Notifications -->
            @if (session()->has('success'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 flex items-center justify-between text-sm shadow-lg shadow-emerald-950/40"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 flex items-center justify-between text-sm shadow-lg shadow-rose-950/40">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Page Slot -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
