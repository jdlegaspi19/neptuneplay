<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_rtps', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->index();
            $table->string('vendor_code')->index();
            $table->integer('rtp')->default(85);
            $table->timestamps();

            $table->unique(['user_code', 'vendor_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_rtps');
    }
};
