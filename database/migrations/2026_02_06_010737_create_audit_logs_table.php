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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // approve, reject, issue, return, etc.
            $table->string('model_type'); // ItemRequest, Issuance, etc.
            $table->unsignedBigInteger('model_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->text('remarks')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
