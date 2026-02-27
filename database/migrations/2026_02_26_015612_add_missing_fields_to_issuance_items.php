<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('issuance_items', function (Blueprint $table) {
            // Add missing fields
            $table->foreignId('item_request_id')->nullable()->after('issuance_id')
                  ->constrained('item_requests');
            $table->foreignId('request_item_id')->nullable()->after('item_request_id')
                  ->constrained('request_items');
            $table->date('issue_date')->nullable()->after('quantity_returned');
            $table->date('return_date')->nullable()->after('due_date');
            $table->enum('condition_on_return', ['good', 'damaged', 'lost', 'partially_damaged'])
                  ->nullable()->after('return_date');
            $table->decimal('unit_cost_at_time', 10, 2)->nullable()->after('condition_on_return');
            $table->decimal('total_cost', 10, 2)->nullable()->after('unit_cost_at_time');
            $table->foreignId('processed_by')->nullable()->after('notes')
                  ->constrained('users');
            
            // Add indexes
            $table->index('item_request_id');
            $table->index('request_item_id');
            $table->index('issue_date');
            $table->index('return_date');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('issuance_items', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['item_request_id']);
            $table->dropForeign(['request_item_id']);
            $table->dropForeign(['processed_by']);
            
            // Drop columns
            $table->dropColumn([
                'item_request_id',
                'request_item_id',
                'issue_date',
                'return_date',
                'condition_on_return',
                'unit_cost_at_time',
                'total_cost',
                'processed_by'
            ]);
            
            // Drop indexes
            $table->dropIndex(['item_request_id']);
            $table->dropIndex(['request_item_id']);
            $table->dropIndex(['issue_date']);
            $table->dropIndex(['return_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
        });
    }
};