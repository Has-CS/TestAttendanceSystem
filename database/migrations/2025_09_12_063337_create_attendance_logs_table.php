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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->dateTime('timestamp');
            $table->string('status')->nullable(); // checkin/checkout or raw
            $table->unsignedBigInteger('device_id')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'timestamp']);
            $table->foreign('device_id')->references('id')->on('device')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
