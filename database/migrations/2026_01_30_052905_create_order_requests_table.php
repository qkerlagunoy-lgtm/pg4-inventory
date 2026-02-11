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
        Schema::create('order_requests', function (Blueprint $table) {
    $table->id();
    $table->string('requester');
    $table->string('purpose');
    $table->enum('urgency', ['Low', 'Medium', 'High']);
    $table->enum('status', ['Pending', 'Approved', 'Delivered'])->default('Pending');
    $table->date('date_requested');
    $table->date('date_delivered')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
