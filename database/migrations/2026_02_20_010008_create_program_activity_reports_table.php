<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramActivityReportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('program_activities')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->json('output_achieved')->nullable();
            $table->unsignedInteger('participant_count')->default(0);
            $table->decimal('budget_used', 15, 2)->default(0);
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->string('status', 32)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_activity_reports');
    }
}
