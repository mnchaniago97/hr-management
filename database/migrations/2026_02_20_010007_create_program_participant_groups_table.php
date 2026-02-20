<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramParticipantGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_participant_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('program_activities')->cascadeOnDelete();
            $table->string('group_type');
            $table->unsignedInteger('target_count')->default(0);
            $table->unsignedInteger('actual_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_participant_groups');
    }
}
