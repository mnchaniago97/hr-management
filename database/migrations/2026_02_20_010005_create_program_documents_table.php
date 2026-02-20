<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->nullable()->constrained('program_activities')->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('file_path');
            $table->string('file_name');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_documents');
    }
}
