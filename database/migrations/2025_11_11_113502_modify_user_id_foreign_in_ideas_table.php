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
        Schema::table('ideas', function (Blueprint $table) {
            // 1. Purani foreign key (constraint) ko drop karein
            $table->dropForeign(['user_id']);

            // 2. Nayi foreign key add karein 'cascadeOnDelete' ke saath
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete(); // <-- YEH HAI ASAL FIX
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            // Rollback karne ke liye, hum purani key wapas add kar denge
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
