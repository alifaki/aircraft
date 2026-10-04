<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipals', function (Blueprint $table) {
            $table->id('municipal_id');
            $table->string('municipal_name', 100);
            $table->string('region', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipals');
    }
};