<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('call_id')->nullable()->index();
            $table->string('user_code')->index();
            $table->string('vendor_code')->nullable();
            $table->string('game_code')->nullable();
            $table->string('game_name')->nullable();
            $table->string('type_name')->nullable()->comment('Free or Jackpot');
            $table->string('status_name')->nullable()->comment('Reserve, Applied, Cancelled');
            $table->decimal('call_amount', 16, 4)->default(0);
            $table->decimal('missed_amount', 16, 4)->default(0);
            $table->decimal('applied_amount', 16, 4)->default(0);
            $table->decimal('agent_before_balance', 16, 4)->nullable();
            $table->decimal('agent_after_balance', 16, 4)->nullable();
            $table->boolean('is_auto_call')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_histories');
    }
};
