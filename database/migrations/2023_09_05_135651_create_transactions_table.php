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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index()->nullable();
            $table->unsignedBigInteger('updater_id')->index()->nullable();
            $table->string('type', 10)->index()->nullable();
            $table->unsignedBigInteger('payer_id')->index()->nullable();
            $table->unsignedBigInteger('receiver_id')->index()->nullable();
            $table->integer('sum')->nullable();
            $table->text('purpose')->nullable();
            $table->text('error_message')->nullable();
            $table->string('status', 20)->nullable();
            $table->unsignedBigInteger('prov_user_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')
                ->on('users');
            $table->foreign('updater_id')
                ->references('id')
                ->on('users');
            $table->foreign('payer_id')
                ->references('id')
                ->on('users');
            $table->foreign('receiver_id')
                ->references('id')
                ->on('users');
            $table->foreign('prov_user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
