<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramDivisionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('program_fields')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('head_id')->nullable()->constrained('hr_members')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_divisions');
    }
}
