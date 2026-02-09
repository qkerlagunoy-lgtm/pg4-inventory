<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('items');
        
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id') ->constrained('categories')->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('minimum_quantity')->default(10);
            $table->string('unit_of_measure')->default('pcs'); // e.g., pcs, boxes, liters
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit
            $table->decimal('total_value', 10, 2)->nullable(); // quantity * unit_cost
            $table->date('last_restocked')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add index for better performance
            $table->index(['is_active', 'quantity']);
            $table->index('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
