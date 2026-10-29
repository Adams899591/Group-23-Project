<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Admin ID
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin');
            $table->boolean('is_active')->default(true); // Account Status
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | 2. ACADEMIC SESSION TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id(); // Session ID
            $table->string('name', 20)->unique(); // e.g. 2024/2025
            $table->unsignedSmallInteger('start_year');
            $table->unsignedSmallInteger('end_year');
            $table->date('application_start_date')->nullable();
            $table->date('application_end_date')->nullable();
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | 3. FACULTY TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('faculties', function (Blueprint $table) {
            $table->id(); // Faculty ID
            $table->string('name')->unique(); // Faculty Name
            $table->string('code')->nullable()->unique(); // Faculty Code
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | 4. DEPARTMENT TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // Department ID

            // Relationship: Faculty ID references the faculties table.
            $table->foreignId('faculty_id')
                ->constrained('faculties')
                ->restrictOnDelete();

            $table->string('name'); // Department Name
            $table->string('code')->nullable()->unique(); // Department Code
            $table->timestamps();

            $table->unique(['faculty_id', 'name']);
        });


        /*
        |--------------------------------------------------------------------------
        | 5. PROGRAMME TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('programmes', function (Blueprint $table) {
            $table->id(); // Programme ID

            // Relationship: Department ID references the departments table.
            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();

            $table->string('name'); // Programme Name
            $table->string('degree_award_type')->nullable();
            $table->unsignedTinyInteger('duration_years')->nullable();
            $table->boolean('is_active')->default(true); // Programme Status
            $table->timestamps();

            $table->unique(['department_id', 'name']);
        });


        /*
        |--------------------------------------------------------------------------
        | 6. APPLICANT TABLE
        |--------------------------------------------------------------------------
        */

        Schema::create('applicants', function (Blueprint $table) {
            $table->id(); // Applicant ID
            $table->string('applicant_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender', 20)->nullable();
            $table->string('state_of_origin')->nullable();
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | 7. APPLICATION TABLE
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | 8. PROGRAMME CAPACITY TABLE
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | 9. PROGRAMME CUTOFF SCORE TABLE
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | 10. SAVED DASHBOARD FILTER TABLE (OPTIONAL)
        |--------------------------------------------------------------------------
        */

        Schema::create('saved_dashboard_filters', function (Blueprint $table) {
            $table->id(); // Filter ID

            // Relationship: Admin ID references the admins table.
            $table->foreignId('admin_id')
                ->constrained('admins')
                ->cascadeOnDelete();

            $table->string('filter_name');
            $table->json('saved_filter_settings')->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        // Drop tables in reverse order to respect foreign-key relationships.
        Schema::dropIfExists('saved_dashboard_filters');
        Schema::dropIfExists('programme_cutoff_scores');
        Schema::dropIfExists('programme_capacities');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('academic_sessions');
        Schema::dropIfExists('admins');
    }
};