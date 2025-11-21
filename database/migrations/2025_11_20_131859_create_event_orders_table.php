<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_orders', function (Blueprint $table) {
        $table->id();
        $table->string('user_name');
        $table->string('email');
        $table->string('mobile');
        $table->string('pass_name')->nullable();
        $table->unsignedBigInteger('event_id');
        $table->unsignedBigInteger('pass_id');
        $table->integer('qty');
        $table->decimal('amount', 10, 2);
        $table->uuid('merchant_transaction_id')->unique();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_orders');
    }
};
