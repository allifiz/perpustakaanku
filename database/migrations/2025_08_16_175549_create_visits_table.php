<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_id');                 // UUID untuk device pengunjung (dari cookie)
            $table->string('visitor_hash', 64);         // hash dari visitor_id (untuk index unik)
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->date('visited_at');                 // tanggal (YYYY-MM-DD)
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            // 1 baris per (visitor per hari)
            $table->unique(['visitor_hash', 'visited_at'], 'visits_unique_per_day');
            $table->index('visited_at');
            $table->index('last_seen_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('visits');
    }
};
