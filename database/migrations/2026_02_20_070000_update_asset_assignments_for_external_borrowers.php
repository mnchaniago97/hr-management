<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->string('borrower_name')->nullable()->after('member_id');
            $table->string('borrower_institution')->nullable()->after('borrower_name');
            $table->string('borrower_phone')->nullable()->after('borrower_institution');
            $table->string('borrower_address')->nullable()->after('borrower_phone');
            $table->string('request_letter_path')->nullable()->after('borrower_address');
            $table->string('id_card_path')->nullable()->after('request_letter_path');
        });

        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });

        DB::statement('ALTER TABLE asset_assignments MODIFY member_id BIGINT UNSIGNED NULL');

        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->foreign('member_id')
                ->references('id')
                ->on('hr_members')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });

        DB::statement('ALTER TABLE asset_assignments MODIFY member_id BIGINT UNSIGNED NOT NULL');

        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->foreign('member_id')
                ->references('id')
                ->on('hr_members')
                ->cascadeOnDelete();

            $table->dropColumn([
                'borrower_name',
                'borrower_institution',
                'borrower_phone',
                'borrower_address',
                'request_letter_path',
                'id_card_path',
            ]);
        });
    }
};
