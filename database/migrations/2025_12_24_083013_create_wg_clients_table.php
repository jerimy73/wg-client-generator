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
        Schema::create('wg_clients', function ($table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();

            $table->string('label');
            $table->string('owner_name')->nullable();
            $table->string('owner_id')->nullable();

            $table->string('vpn_ip')->nullable();
            $table->string('public_key')->nullable();
            $table->text('private_key_enc')->nullable();

            $table->string('conf_path')->nullable();

            $table->enum('status', ['pending','active','revoked'])->default('pending');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wg_clients');
    }
};
