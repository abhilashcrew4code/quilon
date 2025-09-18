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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->tinyInteger('session_reset_flag')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->string('ip')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->default('manual');
            $table->text('user_agent')->nullable();
            $table->string('device_model')->nullable();
            $table->string('browser_name')->nullable();
            $table->longText('browser_detect')->nullable();
            $table->string('platform_name')->nullable();
            $table->longText('location_json')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('last_active_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
