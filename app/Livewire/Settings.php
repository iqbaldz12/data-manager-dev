<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\ProgressPhoto;
use App\Models\User;
use Database\Seeders\LeadCsvSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.codexa')]
#[Title('Pengaturan Sistem — Codexa.id')]
class Settings extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('success', 'Profil Anda berhasil diperbarui!');
    }

    public function switchRole(string $targetRole)
    {
        if (! in_array($targetRole, ['owner', 'marketing', 'developer'], true)) {
            return;
        }

        $user = Auth::user();
        $user->update(['role' => $targetRole]);

        session()->flash('success', "Peran aktif akun Anda berhasil diubah menjadi: " . strtoupper($targetRole) . "! Silakan uji menu dan hak akses sesuai matriks RBAC.");
    }

    public function reseedCsvData()
    {
        $user = Auth::user();
        if (! $user->isOwner()) {
            session()->flash('error', 'Hanya role Owner yang memiliki wewenang mereset / me-reseed database.');
            return;
        }

        try {
            Artisan::call('db:seed', ['--class' => LeadCsvSeeder::class]);
            session()->flash('success', 'Database berhasil di-reseed dari CSV perusahaan_tanpa_web_8_kategori_purwokerto.csv!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal reseed database: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();

        $leadsCount = Lead::count();
        $tasksCount = LeadTask::count();
        $photosCount = ProgressPhoto::count();
        $usersCount = User::count();

        return view('livewire.settings', [
            'user' => $user,
            'leadsCount' => $leadsCount,
            'tasksCount' => $tasksCount,
            'photosCount' => $photosCount,
            'usersCount' => $usersCount,
        ]);
    }
}
