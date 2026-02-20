<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramTrainingParticipantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('program_trainings')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('hr_employees')->cascadeOnDelete();
            $table->date('registration_date');
            $table->string('attendance_status', 32)->default('not_attended');
            $table->string('certificate')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['training_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_training_participants');
    }
}
