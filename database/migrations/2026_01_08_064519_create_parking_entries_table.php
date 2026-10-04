<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_entries', function (Blueprint $table) {
            $table->id('entry_id');
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types', 'vehicle_type_id')->onDelete('cascade');
            $table->string('plate_number', 20);
            $table->string('phone_number', 20)->nullable();
            $table->timestamp('entry_time')->useCurrent();
            $table->timestamp('exit_time')->nullable();
            $table->foreignId('location_id')->constrained('parking_locations', 'location_id')->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('rate_per_hour', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_entries');
    }
};