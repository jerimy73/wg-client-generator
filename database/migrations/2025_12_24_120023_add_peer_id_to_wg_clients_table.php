<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('wg_clients', function ($table) {
            $table->string('peer_id')->nullable()->after('public_key');
        });
    }
    public function down(): void
    {
        Schema::table('wg_clients', function ($table) {
            $table->dropColumn('peer_id');
        });
    }
    
};
