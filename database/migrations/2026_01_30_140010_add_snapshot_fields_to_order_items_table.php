<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('title_snapshot')->nullable()->after('listing_id');
            $table->decimal('price_snapshot', 12, 2)->nullable()->after('title_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['title_snapshot', 'price_snapshot']);
        });
    }
};
