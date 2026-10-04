<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('section_id');
            $table->string('employee_number')->unique();
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('initial', 20)->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 16);
            $table->string('nationality')->nullable();
            $table->string('id_card_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_commencement')->nullable();
            $table->string('highest_education')->nullable();
            $table->string('specialization')->nullable();
            $table->string('position')->nullable();
            $table->string('center_permit')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('status', 12)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
