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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('ticket_price', 10, 2)->nullable();
            $table->boolean('multiple_price')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('draw_time')->nullable();
            $table->string('cause')->nullable();
            $table->string('rules')->nullable(); // upload pdf or text
            $table->integer('max_tickets_per_user')->default(1);
            $table->boolean('is_publish')->default(false);
            $table->enum('status', ['active', 'paused', 'inactive'])->default('active');
            $table->string('location')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
