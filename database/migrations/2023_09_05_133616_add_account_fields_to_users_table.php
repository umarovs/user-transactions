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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_id')->index()->nullable();
            $table->unsignedBigInteger('updater_id')->index()->nullable();
            $table->decimal('balance')->default(0)->nullable();
            $table->foreign('creator_id')
                ->references('id')
                ->on('users');
            $table->foreign('updater_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['creator_id']);
            $table->dropIndex(['updater_id']);
            $table->dropColumn(['creator_id', 'updater_id', 'balance']);
        });
    }
};
