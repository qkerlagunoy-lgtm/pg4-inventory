<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_offenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_registry_id')->constrained('device_registry')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('offense_date')->nullable();
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('filed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_offenses');
    }
};