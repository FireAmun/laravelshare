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
            $table->boolean('is_admin')->default(false)->after('email');
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'last_login_at', 'last_login_ip', 'is_active']);
        });
    }
};
