<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed tim Codexa.id:
     *  - 1 Owner
     *  - 4 Marketing
     *  - 2 Developer
     *
     * Password default semua: codexa123
     */
    public function run(): void
    {
        $users = [
            // ── OWNER ──────────────────────────────────────────────
            [
                'name'     => 'Nickxmn (Owner)',
                'email'    => 'owner@codexa.id',
                'role'     => 'owner',
                'password' => 'codexa123',
            ],

            // ── MARKETING ──────────────────────────────────────────
            [
                'name'     => 'Hugo',
                'email'    => 'HugoMhdn@codexa.id',
                'role'     => 'marketing',
                'password' => 'codexa123',
            ],
            [
                'name'     => 'Hisyam Kiwil',
                'email'    => 'hisyam@codexa.id',
                'role'     => 'marketing',
                'password' => 'codexa123',
            ],
            [
                'name'     => 'Heriska',
                'email'    => 'heriska@codexa.id',
                'role'     => 'marketing',
                'password' => 'codexa123',
            ],
            [
                'name'     => 'Rizki Pratama',
                'email'    => 'rizki@codexa.id',
                'role'     => 'marketing',
                'password' => 'codexa123',
            ],

            // ── DEVELOPER ──────────────────────────────────────────
            [
                'name'     => 'Nickxmn',
                'email'    => 'Nickxmn@codexa.id',
                'role'     => 'developer',
                'password' => 'codexa123',
            ],
            [
                'name'     => 'Iqbal',
                'email'    => 'Iqbal@codexa.id',
                'role'     => 'developer',
                'password' => 'codexa123',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'role'              => $data['role'],
                    'password'          => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('✅ 7 akun tim Codexa.id berhasil dibuat (1 Owner, 4 Marketing, 2 Developer)');
        $this->command->newLine();
        $this->command->table(
            ['Role', 'Nama', 'Email', 'Password'],
            collect($users)->map(fn ($u) => [
                strtoupper($u['role']),
                $u['name'],
                $u['email'],
                $u['password'],
            ])->toArray()
        );
    }
}
