<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BIGINT primary key
            $table->string('app_id', 100)->unique();
            $table->string('company_name', 100);
            $table->string('description', 200);
            $table->string('logo', 255)->nullable();
            $table->string('location', 100);
            $table->string('city', 100);
            $table->string('country', 100);
            $table->string('email', 50)->unique();
            $table->string('phone', 30)->unique();
            $table->string('postal_address', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('youtube', 100)->nullable();
            $table->string('map_link', 300)->nullable();
            $table->decimal('donation', 11, 2)->default(0.00);
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
