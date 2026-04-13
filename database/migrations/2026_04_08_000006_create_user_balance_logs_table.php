<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->nullable()->index();
            $table->string('user_code')->index();
            $table->decimal('amount', 16, 4);
            $table->integer('type')->comment('1=Deposit, 2=Withdraw');
            $table->decimal('agent_before_balance', 16, 4)->nullable();
            $table->decimal('user_before_balance', 16, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_balance_logs');
    }
};
