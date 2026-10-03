<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_proofs', function (Blueprint $table) {
            // Siapa yang CHAT / menghubungi perusahaan (bisa beda dengan uploader)
            $table->foreignId('chatted_by_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('users')
                  ->nullOnDelete();
        });

        // Tambah assigned_marketing_id ke leads (PIC marketing yang handle lead ini)
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('assigned_marketing_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lead_proofs', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'chatted_by_id');
            $table->dropColumn('chatted_by_id');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'assigned_marketing_id');
            $table->dropColumn('assigned_marketing_id');
        });
    }
};
