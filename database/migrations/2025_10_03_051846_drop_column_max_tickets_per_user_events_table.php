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
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'max_tickets_per_user')) {
                $table->dropColumn('max_tickets_per_user');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
