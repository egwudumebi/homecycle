<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained('sub_categories');
            $table->foreignId('state_id')->constrained('location_states');
            $table->foreignId('city_id')->constrained('location_cities');

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');

            $table->decimal('price', 12, 2);

            $table->string('seller_name');
            $table->string('seller_phone');
            $table->string('whatsapp_phone')->nullable();

            $table->string('status')->default('active')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
