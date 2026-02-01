<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_name', 255)->default('')->after('tracking_status');
            $table->string('delivery_phone', 32)->default('')->after('delivery_name');
            $table->string('delivery_address', 1000)->default('')->after('delivery_phone');
            $table->string('delivery_state', 255)->default('')->after('delivery_address');
            $table->string('delivery_city', 255)->default('')->after('delivery_state');
            $table->text('delivery_notes')->nullable()->after('delivery_city');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_name',
                'delivery_phone',
                'delivery_address',
                'delivery_state',
                'delivery_city',
                'delivery_notes',
            ]);
        });
    }
};
