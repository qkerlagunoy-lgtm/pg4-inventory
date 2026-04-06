<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issuance_item_id')->constrained('issuance_items')->cascadeOnDelete();
            $table->foreignId('issuance_id')->constrained('issuances')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('processed_by')->constrained('users');
            $table->integer('quantity_returned');
            $table->enum('condition', ['good', 'damaged', 'lost'])->default('good');
            $table->text('notes')->nullable();
            $table->boolean('restocked')->default(false);
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_transactions');
    }
};