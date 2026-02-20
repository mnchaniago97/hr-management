<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramPeriodsTable extends Migration
{
    public function up(): void
    {
        Schema::create('program_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year_start');
            $table->unsignedSmallInteger('year_end');
            $table->boolean('is_active')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->unique(['year_start', 'year_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_periods');
    }
}
