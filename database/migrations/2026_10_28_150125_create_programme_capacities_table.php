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
        Schema::create('programme_capacities', function (Blueprint $table) {
            $table->id(); // Capacity ID

            // Relationship: Academic Session ID references academic_sessions.
            $table->foreignId('academic_session_id')
                ->constrained('academic_sessions')
                ->restrictOnDelete();

            // Relationship: Programme ID references the programmes table.
            $table->foreignId('programme_id')
                ->constrained('programmes')
                ->restrictOnDelete();

            $table->unsignedInteger('approved_quota_capacity');

            $table->timestamps();

            // One capacity record per programme per academic session.
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
        Schema::dropIfExists('programme_capacities');
    }
};
