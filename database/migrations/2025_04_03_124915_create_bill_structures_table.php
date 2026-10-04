<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bill_structures', function (Blueprint $table) {
            $table->id();
            $table->string('short_name', 50);
            $table->string('name', 100);
            $table->string('gfs_code', 100)->unique();
            $table->string('group', 100);
            $table->string('category', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_parameters');
    }
};
