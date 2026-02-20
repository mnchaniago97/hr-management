<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MoveNiaToHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_employees', 'nia')) {
                $table->string('nia', 50)->nullable()->unique()->after('member_id');
            }
        });

        if (Schema::hasColumn('hr_members', 'nia')) {
            $members = DB::table('hr_members')
                ->select('id', 'nia')
                ->whereNotNull('nia')
                ->get();

            foreach ($members as $member) {
                DB::table('hr_employees')->updateOrInsert(
                    ['member_id' => $member->id],
                    ['nia' => $member->nia]
                );
            }

            Schema::table('hr_members', function (Blueprint $table) {
                $table->dropUnique(['nia']);
                $table->dropColumn('nia');
            });
        }

        if (Schema::hasColumn('hr_employees', 'employee_number')) {
            Schema::table('hr_employees', function (Blueprint $table) {
                $table->dropUnique(['employee_number']);
                $table->dropColumn('employee_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_members', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_members', 'nia')) {
                $table->string('nia', 50)->nullable()->unique()->after('name');
            }
        });

        if (Schema::hasColumn('hr_employees', 'nia')) {
            $profiles = DB::table('hr_employees')
                ->select('member_id', 'nia')
                ->whereNotNull('nia')
                ->get();

            foreach ($profiles as $profile) {
                DB::table('hr_members')
                    ->where('id', $profile->member_id)
                    ->update(['nia' => $profile->nia]);
            }

            Schema::table('hr_employees', function (Blueprint $table) {
                $table->dropUnique(['nia']);
                $table->dropColumn('nia');
            });
        }

        Schema::table('hr_employees', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_employees', 'employee_number')) {
                $table->string('employee_number')->nullable()->unique()->after('member_id');
            }
        });
    }
}
