<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_address_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Admin Network", "PG4 LAN"
            $table->string('range_start'); // e.g. 192.168.1.1
            $table->string('range_end');   // e.g. 192.168.1.254
            $table->string('subnet_mask')->nullable(); // e.g. 255.255.255.0
            $table->string('gateway')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_address_ranges');
    }
};