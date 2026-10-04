<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('confirmation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('type'); // e.g., 'email_verification', 'password_reset'
            $table->morphs('confirmable'); // Polymorphic relation (user, admin, etc.)
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['code', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('confirmation_codes');
    }
};
