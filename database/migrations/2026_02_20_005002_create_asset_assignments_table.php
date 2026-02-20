<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetAssignmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('asset_items')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('hr_members')->cascadeOnDelete();
            $table->date('assignment_date');
            $table->date('return_date');
            $table->date('actual_return_date')->nullable();
            $table->string('status', 32)->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
    }
}
