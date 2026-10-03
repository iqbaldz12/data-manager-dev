<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('instagram_search')->nullable();
            $table->float('rating')->nullable();
            $table->integer('reviews_count')->nullable();
            $table->text('maps_link')->nullable();
            $table->enum('status', [
                'Belum Dihubungi',
                'WA Terkirim',
                'Follow Up',
                'Negosiasi',
                'Deal / Won',
                'Batal',
            ])->default('Belum Dihubungi');
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('progress')->default(0); // 0-100
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
