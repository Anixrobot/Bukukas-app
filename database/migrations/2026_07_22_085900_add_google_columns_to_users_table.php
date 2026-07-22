<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nambahin kolom untuk nyimpen data Google
            $table->string('google_id')->nullable()->after('email');
            $table->string('google_token')->nullable()->after('google_id');
            $table->string('google_refresh_token')->nullable()->after('google_token');
            
            // Bikin password jadi nullable karena kalau login pakai Google, dia gak butuh password
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google_token', 'google_refresh_token']);
            $table->string('password')->nullable(false)->change();
        });
    }
};