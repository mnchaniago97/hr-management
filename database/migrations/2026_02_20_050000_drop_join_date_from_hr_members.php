<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_members', function (Blueprint $table) {
            $table->dropColumn('join_date');
        });
    }

    public function down(): void
    {
        Schema::table('hr_members', function (Blueprint $table) {
            $table->date('join_date')->nullable()->after('department');
        });
    }
};
