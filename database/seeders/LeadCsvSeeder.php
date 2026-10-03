<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\ProgressLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LeadCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update Default Users for 3 roles
        $owner = User::firstOrCreate(
            ['email' => 'owner@codexa.id'],
            [
                'name' => 'Owner Codexa',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ]
        );

        $marketing = User::firstOrCreate(
            ['email' => 'marketing@codexa.id'],
            [
                'name' => 'Marketing',
                'password' => Hash::make('password'),
                'role' => 'marketing',
                'email_verified_at' => now(),
            ]
        );

        $developer = User::firstOrCreate(
            ['email' => 'developer@codexa.id'],
            [
                'name' => 'Developer',
                'password' => Hash::make('password'),
                'role' => 'developer',
                'email_verified_at' => now(),
            ]
        );

        // 2. Read CSV file
        $csvPath = database_path('data/perusahaan_tanpa_web_8_kategori_purwokerto.csv');
        if (! file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command->error("Could not open CSV file at: {$csvPath}");
            return;
        }

        // Handle BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xef\xbb\xbf") {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            return;
        }

        // Clean headers
        $cleanHeaders = array_map(function ($h) {
            return trim(str_replace(["\xef\xbb\xbf", '"', "'"], '', $h));
        }, $header);

        $leadCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $name = trim($row[0] ?? '');
            if (empty($name)) {
                continue;
            }

            $category = trim($row[1] ?? 'Lainnya');
            $address = trim($row[2] ?? '');
            $phone = trim($row[3] ?? '');
            $whatsapp = trim($row[4] ?? '');
            $instagram = trim($row[5] ?? '');
            $rating = is_numeric($row[6] ?? null) ? (float) $row[6] : null;
            $reviews = is_numeric($row[7] ?? null) ? (int) $row[7] : null;
            $maps = trim($row[8] ?? '');

            Lead::create([
                'name' => $name,
                'category' => $category,
                'address' => $address,
                'phone' => !empty($phone) ? $phone : null,
                'whatsapp_link' => !empty($whatsapp) ? $whatsapp : null,
                'instagram_search' => !empty($instagram) ? $instagram : null,
                'rating' => $rating,
                'reviews_count' => $reviews,
                'maps_link' => !empty($maps) ? $maps : null,
                'status' => 'Belum Dihubungi',
                'progress' => 0,
                'notes' => null,
                'user_id' => null,
            ]);

            $leadCount++;
        }

        fclose($handle);

        $this->command->info("Successfully imported {$leadCount} real leads from CSV!");
    }
}
