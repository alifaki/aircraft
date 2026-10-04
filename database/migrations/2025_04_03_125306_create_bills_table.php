<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            // The parking table is created in 2026, after this legacy migration.
            $table->unsignedBigInteger('parking_entry_id')->index();
            $table->string('control_number', 50)->nullable()->comment('bill number');
            $table->string('call_back_url', 255)->nullable()->comment('call back url');
            $table->enum('bill_option', ['full', 'partial', 'exactly'])->default('full');
            $table->enum('customer_type', ['staff','others','parking','guest','customer'])->default('others');
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 15)->nullable();
            $table->string('customer_email', 50)->nullable();
            $table->string('payer_name', 50)->nullable();
            $table->string('description', 200)->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->dateTime('expires_at');
            $table->string('bill_reference', 255)->nullable();
            $table->string('cancellation_reason', 200)->nullable();
            $table->string('currency', 50)->default('TZS');
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired','partial-paid'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
