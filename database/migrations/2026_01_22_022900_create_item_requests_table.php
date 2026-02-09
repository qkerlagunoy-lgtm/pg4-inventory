<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'urgent'])->default('pending');
            $table->enum('issuance_status', ['not_issued', 'partially_issued', 'fully_issued', 'cancelled'])->default('not_issued');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('request_date')->useCurrent();
            $table->timestamp('required_date')->nullable();
            $table->date('scheduled_issue_date')->nullable();
            $table->date('actual_issue_date')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->date('expected_return_date')->nullable();
            $table->enum('return_status', ['not_returned', 'partially_returned', 'fully_returned'])->default('not_returned');
            $table->string('tracking_number')->nullable()->unique();
            $table->enum('delivery_method', ['pickup', 'delivery', 'courier'])->default('pickup');
            $table->text('remarks')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'issuance_status']);
            $table->index(['user_id', 'created_at']);
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};