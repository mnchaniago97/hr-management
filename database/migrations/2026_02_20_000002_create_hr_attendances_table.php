<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrAttendancesTable extends Migration
{
    public function up(): void
    {
        Schema::create('hr_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('hr_members')->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('status', 32);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['member_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_attendances');
    }
}
