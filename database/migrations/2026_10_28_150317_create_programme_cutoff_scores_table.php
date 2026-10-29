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
        Schema::create('programme_cutoff_scores', function (Blueprint $table) {
            $table->id(); // Cutoff ID

            // Relationship: Academic Session ID references academic_sessions.
            $table->foreignId('academic_session_id')
                ->constrained('academic_sessions')
                ->restrictOnDelete();

            // Relationship: Programme ID references the programmes table.
            $table->foreignId('programme_id')
                ->constrained('programmes')
                ->restrictOnDelete();

            $table->decimal('utme_cutoff_score', 5, 2)->nullable();
            $table->decimal('post_utme_cutoff_score', 5, 2)->nullable();
            $table->decimal('aggregate_cutoff_score', 6, 2)->nullable();

            $table->timestamps();

            // One cutoff record per programme per academic session.
            $table->unique([
                'academic_session_id',
                'programme_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_cutoff_scores');
    }
};
