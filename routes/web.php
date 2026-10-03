<?php

use App\Livewire\Dashboard;
use App\Livewire\LeadManager;
use App\Livewire\ProjectBoard;
use App\Livewire\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/leads', LeadManager::class)->name('leads.index');
    Route::get('/projects', ProjectBoard::class)->name('projects.board');
    Route::get('/settings', Settings::class)->name('settings.index');

    // Quick Role Switcher for instant testing across 3 roles
    Route::post('/role/switch/{role}', function (Request $request, string $role) {
        if (! in_array($role, ['owner', 'marketing', 'developer'], true)) {
            abort(400, 'Role tidak valid');
        }

        $user = $request->user();
        $user->update(['role' => $role]);

        return back()->with('success', 'Peran berhasil dialihkan ke ' . strtoupper($role) . '. Hak akses disesuaikan!');
    })->name('role.switch');
});

require __DIR__.'/settings.php';
