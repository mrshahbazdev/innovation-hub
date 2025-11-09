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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id(); // Har idea ka unique ID

            // --- Step 1: Idea Kisne Submit Kiya ---
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('team_id')->constrained('teams');
            $table->enum('submitter_type', ['visitor', 'user', 'team_member']);
            $table->string('contact_info')->nullable();

            // --- Step 2: Idea ki Tafseelat (Form se) ---
            $table->string('problem_short'); // 4 lafzon wala masla
            $table->text('goal'); // Maqsad
            $table->text('problem_detail'); // Tafseeli masla

            // --- Step 3: Workflow aur Status ---
            $table->enum('status', [
                'new',
                'pending_review',
                'pending_pricing',
                'approved',
                'rejected',
                'completed'
            ])->default('new');

            // --- Step 4: Aapke Grid Ke Columns ---

            // Team Yellow/Orange (Work-Bees) ke fields
            $table->integer('schmerz')->default(0);
            $table->decimal('prio_1', 8, 2)->nullable();
            $table->decimal('prio_2', 8, 2)->nullable();
            $table->integer('umsetzung')->nullable();

            // Team Red (Developer) ke fields
            $table->text('loesung')->nullable();
            $table->decimal('kosten', 10, 2)->default(0);
            $table->integer('dauer')->default(0);

            $table->timestamps(); // Created_at aur updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
