<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name');
            }
            if (! Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name');
            }
            if (! Schema::hasColumn('users', 'suffix')) {
                $table->string('suffix')->nullable();
            }
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique();
            }
            if (! Schema::hasColumn('users', 'sex')) {
                $table->enum('sex', ['male', 'female']);
            }
            if (! Schema::hasColumn('users', 'unit')) {
                $table->string('unit');
            }
            if (! Schema::hasColumn('users', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();

            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'username',
                'sex',
                'unit',
                'category_id',
            ]);
        });
    }
};
