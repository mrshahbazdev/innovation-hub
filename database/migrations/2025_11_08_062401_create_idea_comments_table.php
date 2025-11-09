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
        Schema::create('idea_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained('ideas')->cascadeOnDelete(); // Idea se link
            $table->foreignId('user_id')->constrained('users'); // Kis user ne comment kiya
            $table->text('body'); // Comment ka text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_comments');
    }
};
