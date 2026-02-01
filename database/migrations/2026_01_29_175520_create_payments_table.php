<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('provider')->default('paystack');
            $table->string('reference')->unique();
            $table->string('currency', 3)->default('NGN');
            $table->unsignedBigInteger('amount_kobo');
            $table->string('status')->default('initialized');
            $table->string('access_code')->nullable();
            $table->string('authorization_url')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('provider_payload')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
