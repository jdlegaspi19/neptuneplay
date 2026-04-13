<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('betting_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('history_id')->nullable()->index();
            $table->string('user_code')->index();
            $table->string('round_id')->nullable();
            $table->string('game_code')->nullable();
            $table->string('game_name')->nullable();
            $table->string('vendor_code')->nullable();
            $table->decimal('bet_amount', 16, 4)->default(0);
            $table->decimal('win_amount', 16, 4)->default(0);
            $table->decimal('before_balance', 16, 4)->default(0);
            $table->decimal('after_balance', 16, 4)->default(0);
            $table->text('detail')->nullable();
            $table->integer('status')->default(0)->comment('0=Unfinished, 1=Finished, 2=Canceled');
            $table->timestamps();

            $table->index(['user_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('betting_histories');
    }
};
