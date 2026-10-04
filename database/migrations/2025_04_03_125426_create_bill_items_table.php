<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
            $table->string('item_reference', 100);
            $table->string('payment_reference', 50);
            $table->decimal('amount', 10, 2);
            $table->string('gfs_code', 50);
            $table->timestamps();

            $table->foreign('gfs_code')->references('gfs_code')->on('bill_structures')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_items');
    }
};
