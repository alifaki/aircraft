<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('branch_name', 100);
            $table->string('branch_code', 20)->unique();
            $table->string('location', 100);
            $table->string('city', 100);
            $table->string('country', 100);
            $table->string('manager_name', 100)->nullable();
            $table->string('manager_phone', 30)->nullable();
            $table->string('manager_email', 30)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
