<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNiaToHrMembersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hr_members', function (Blueprint $table) {
            $table->string('nia', 50)->nullable()->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_members', function (Blueprint $table) {
            $table->dropUnique(['nia']);
            $table->dropColumn('nia');
        });
    }
}
