<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('ip_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique();              // contoh: 10.100.180.10
            $table->boolean('is_used')->default(false);  // sudah dipakai atau belum
            $table->foreignId('wg_client_id')
                ->nullable()
                ->constrained('wg_clients')
                ->nullOnDelete();                      // client dihapus → IP dilepas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_allocations');
    }

};
