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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
