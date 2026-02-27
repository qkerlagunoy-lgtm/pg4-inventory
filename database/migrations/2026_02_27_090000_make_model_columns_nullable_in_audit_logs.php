<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for column modifications
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_type` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_id` BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL to restore original constraint (no default)
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_type` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `audit_logs` MODIFY `model_id` BIGINT UNSIGNED NOT NULL');
    }
};
