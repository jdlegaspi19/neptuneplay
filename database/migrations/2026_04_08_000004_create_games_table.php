<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable();
            $table->string('vendor_code')->index();
            $table->string('game_id')->nullable();
            $table->string('game_code')->index();
            $table->string('game_name');
            $table->string('slug')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('under_maintenance')->default(false);
            $table->timestamps();

            $table->unique(['vendor_code', 'game_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
