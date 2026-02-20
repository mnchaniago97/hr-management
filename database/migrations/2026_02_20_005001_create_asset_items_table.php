<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('asset_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category');
            $table->string('location');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('condition', 32);
            $table->string('status', 32);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('asset_vendors')->nullOnDelete();
            $table->timestamps();
            $table->index(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_items');
    }
}
