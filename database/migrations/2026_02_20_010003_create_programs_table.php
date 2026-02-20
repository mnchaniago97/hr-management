<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('program_periods')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('program_fields')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('program_divisions')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['terprogram', 'insidentil']);
            $table->text('description')->nullable();
            $table->unsignedInteger('target')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('status', 32)->default('draft');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
}
