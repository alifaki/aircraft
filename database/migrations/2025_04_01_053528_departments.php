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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description'); // Changed to text for longer descriptions
            $table->foreignId('head_ofd_id')
                ->nullable()
                ->constrained('staff')
                ->onDelete('set null'); // Added proper deletion handling
            $table->boolean('is_active')->default(true);
            $table->timestamp('deactivated_at')->nullable(); // Track when deactivated
            $table->timestamps();
            $table->softDeletes(); // Added soft delete capability
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
