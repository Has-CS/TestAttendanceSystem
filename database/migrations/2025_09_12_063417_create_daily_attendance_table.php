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
        Schema::create('daily_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->date('date');
            $table->dateTime('first_check_in')->nullable();
            $table->dateTime('last_check_out')->nullable();
            $table->unsignedBigInteger('device_id_in')->nullable();
            $table->unsignedBigInteger('device_id_out')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->foreign('device_id_in')->references('id')->on('device')->onDelete('set null');
            $table->foreign('device_id_out')->references('id')->on('device')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_attendance');
    }
};
