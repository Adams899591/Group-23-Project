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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
