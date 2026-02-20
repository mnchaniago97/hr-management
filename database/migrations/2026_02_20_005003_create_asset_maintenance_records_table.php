<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetMaintenanceRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('asset_items')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('asset_vendors')->nullOnDelete();
            $table->string('type', 32);
            $table->date('maintenance_date');
            $table->date('scheduled_date')->nullable();
            $table->string('status', 32);
            $table->decimal('cost', 15, 2)->nullable();
            $table->text('description');
            $table->string('performed_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_records');
    }
}
