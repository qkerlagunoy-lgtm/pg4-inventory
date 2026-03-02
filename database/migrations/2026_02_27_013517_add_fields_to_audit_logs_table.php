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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('module')->nullable()->after('action');
            $table->text('description')->nullable()->after('remarks');
            $table->string('url')->nullable()->after('user_agent');
            $table->string('method', 10)->nullable()->after('url');
            $table->timestamp('performed_at')->nullable()->after('method');
            $table->softDeletes()->after('updated_at');
            
            $table->index('module');
            $table->index('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            //
        });
    }
};
