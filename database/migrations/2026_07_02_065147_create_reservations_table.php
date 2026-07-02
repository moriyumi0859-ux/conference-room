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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conference_room_id')->constrained()->cascadeOnDelete();
            $table->date('reservation_date');   // 予約日
            $table->time('start_time');         // 開始時間
            $table->time('end_time');           // 終了時間
            $table->string('status')->default('予約中'); // 予約中／キャンセル
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
