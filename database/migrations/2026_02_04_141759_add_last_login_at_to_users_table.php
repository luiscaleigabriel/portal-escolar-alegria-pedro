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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('last_seen')->nullable()->after('last_login_at');
            $table->json('settings')->nullable()->after('last_seen');
            $table->string('timezone')->default('Africa/Luanda')->after('settings');
            $table->string('language')->default('pt')->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_at',
                'last_seen',
                'settings',
                'timezone',
                'language'
            ]);
        });
    }
};
