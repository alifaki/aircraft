<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained();
            $table->decimal('paidAmount', 10, 2);
            $table->string('serviceProvider', 200)->comment('Tigo, Zantel, Crdb Bank, Mpesa, Pbz, others');
            $table->string('receiptNumber', 200)->comment('receipt number');
            $table->string('billRefrence', 200)->comment('bill reference');
            $table->string('CtrAccNum', 200)->comment('control account number');
            $table->string('PyrName', 200)->comment('payer name');
            $table->string('payerPhone', 200)->comment('payer phone');
            $table->text('remark')->nullable()->comment('any comment, reason');
            $table->timestamp('paidAt');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_flows');
    }
};
