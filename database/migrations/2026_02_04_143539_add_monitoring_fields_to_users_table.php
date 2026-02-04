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
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }

            if (!Schema::hasColumn('users', 'last_seen')) {
                $table->timestamp('last_seen')->nullable()->after('last_login_at');
            }

            if (!Schema::hasColumn('users', 'login_count')) {
                $table->integer('login_count')->default(0)->after('last_seen');
            }

            if (!Schema::hasColumn('users', 'settings')) {
                $table->json('settings')->nullable()->after('login_count');
            }
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
                'login_count',
                'settings'
            ]);
        });
    }
};
