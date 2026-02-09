<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('approved_quantity')->nullable(); // Quantity admin approves
            $table->integer('issued_quantity')->default(0); // Actually issued
            $table->integer('returned_quantity')->default(0); // Returned by user
            $table->enum('issuance_status', ['not_issued', 'partially_issued', 'fully_issued', 'cancelled'])->default('not_issued');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('condition_on_return', ['good', 'damaged', 'lost'])->nullable();
            $table->decimal('unit_cost_at_time', 10, 2)->nullable(); // For cost tracking
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->foreignId('issuance_id')->nullable()->constrained('issuances')->nullOnDelete(); // Issuance tracking
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index(['issuance_id', 'issuance_status']);
            $table->index(['item_request_id', 'issuance_status']);
            $table->index(['due_date', 'issuance_status']);
        }); 
    }

    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};