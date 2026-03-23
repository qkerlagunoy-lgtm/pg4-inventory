<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rank')->nullable()->after('last_name');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete()->after('rank');
            $table->foreignId('personnel_category_id')->nullable()->constrained('personnel_categories')->nullOnDelete()->after('unit_id');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete()->after('personnel_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['personnel_category_id']);
            $table->dropForeign(['designation_id']);
            $table->dropColumn(['rank', 'unit_id', 'personnel_category_id', 'designation_id']);
        });
    }
};