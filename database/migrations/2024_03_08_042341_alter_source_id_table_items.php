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
        Schema::table('items', function (Blueprint $table) {
            $table->string('source_id')->change()->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->datetime('expected_arival_date')->nullable();
            $table->text('delivery_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('source_id')->change();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('expected_arival_date');
            $table->dropColumn('delivery_address');
        });
    }
};
