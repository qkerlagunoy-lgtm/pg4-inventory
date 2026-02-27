<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            // Remove issuance-specific fields
            $table->dropColumn([
                'issued_quantity',
                'returned_quantity',
                'issue_date',
                'due_date',
                'return_date',
                'condition_on_return',
                'unit_cost_at_time',
                'total_cost',
                'issuance_id',
                'issuance_status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            // Add back the columns in case of rollback
            $table->integer('issued_quantity')->default(0)->after('approved_quantity');
            $table->integer('returned_quantity')->default(0)->after('issued_quantity');
            $table->date('issue_date')->nullable()->after('returned_quantity');
            $table->date('due_date')->nullable()->after('issue_date');
            $table->date('return_date')->nullable()->after('due_date');
            $table->string('condition_on_return')->nullable()->after('return_date');
            $table->decimal('unit_cost_at_time', 10, 2)->nullable()->after('condition_on_return');
            $table->decimal('total_cost', 10, 2)->nullable()->after('unit_cost_at_time');
            $table->foreignId('issuance_id')->nullable()->constrained()->after('total_cost');
            $table->string('issuance_status')->default('not_issued')->after('status');
        });
    }
};