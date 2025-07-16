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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->json('data')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('created_at');

            $table->index(['event', 'created_at']);
            $table->index(['severity', 'created_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
