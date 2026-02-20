<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDivisionAssignmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('hr_division_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('hr_members')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('hr_positions')->cascadeOnDelete();
            $table->unsignedBigInteger('division_id');
            $table->date('assigned_date');
            $table->date('end_date')->nullable();
            $table->string('status', 32);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['member_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_division_assignments');
    }
}
