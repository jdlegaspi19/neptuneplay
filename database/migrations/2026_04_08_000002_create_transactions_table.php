<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->string('user_code')->index();
            $table->decimal('amount', 16, 4);
            $table->decimal('balance_before', 16, 4);
            $table->decimal('balance_after', 16, 4);
            $table->string('vendor_code')->nullable();
            $table->string('game_code')->nullable();
            $table->string('round_id')->nullable();
            $table->integer('history_id')->nullable();
            $table->integer('game_type')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->text('detail')->nullable();
            $table->string('batch_id')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_code', 'round_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
