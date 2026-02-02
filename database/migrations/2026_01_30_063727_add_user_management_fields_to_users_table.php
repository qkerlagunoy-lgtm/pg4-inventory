<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'username')) {
            $table->string('username')->nullable()->after('email');
        }
        if (!Schema::hasColumn('users', 'type')) {
            $table->string('type')->default('user')->after('password');
        }
        if (!Schema::hasColumn('users', 'unit')) {
            $table->string('unit')->nullable()->after('type');
        }
        if (!Schema::hasColumn('users', 'is_active')) {
            $table->boolean('is_active')->default(true)->after('unit');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('users', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('users', 'unit')) {
                $table->dropColumn('unit');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};