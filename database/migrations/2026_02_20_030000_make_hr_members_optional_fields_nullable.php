<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE hr_members MODIFY email VARCHAR(255) NULL");
        DB::statement("ALTER TABLE hr_members MODIFY position VARCHAR(255) NULL");
        DB::statement("ALTER TABLE hr_members MODIFY department VARCHAR(255) NULL");
        DB::statement("ALTER TABLE hr_members MODIFY join_date DATE NULL");
        DB::statement("ALTER TABLE hr_members MODIFY status VARCHAR(32) NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE hr_members MODIFY email VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE hr_members MODIFY position VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE hr_members MODIFY department VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE hr_members MODIFY join_date DATE NOT NULL");
        DB::statement("ALTER TABLE hr_members MODIFY status VARCHAR(32) NOT NULL DEFAULT 'active'");
    }
};
