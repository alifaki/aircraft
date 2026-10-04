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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->foreignId('staff_id')->unique()->constrained('staff')->onDelete('cascade');
            $table->string('password', 64);
            $table->string('reset_token', 20)->nullable();
            $table->string('remember_token', 64)->nullable();
            $table->integer('login_attempts')->default(0);
            $table->timestamp('block_until')->nullable();
            $table->string('status', 10)->default('active');
            $table->string('type', 15)->default('staff');
            $table->foreignId('role_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
