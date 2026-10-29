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
        Schema::create('applications', function (Blueprint $table) {
            $table->id(); // Application ID

            // Relationship: Applicant ID references the applicants table.
            $table->foreignId('applicant_id')
                ->constrained('applicants')
                ->restrictOnDelete();

            // Relationship: Academic Session ID references academic_sessions.
            $table->foreignId('academic_session_id')
                ->constrained('academic_sessions')
                ->restrictOnDelete();

            // Relationship: Programme ID references the programmes table.
            $table->foreignId('programme_id')
                ->constrained('programmes')
                ->restrictOnDelete();

            $table->string('application_number')->unique();
            $table->date('application_date');

            $table->decimal('utme_score', 5, 2)->nullable();
            $table->decimal('post_utme_score', 5, 2)->nullable();
            $table->decimal('aggregate_score', 6, 2)->nullable();

            $table->string('application_status', 30)->default('pending');
            $table->date('admission_date')->nullable();

            $table->timestamps();

            $table->index([
                'academic_session_id',
                'application_status'
            ]);

            $table->index([
                'programme_id',
                'academic_session_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
