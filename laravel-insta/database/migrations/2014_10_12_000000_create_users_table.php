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
            $table->string('name');
            $table->string('email')->unique();
            $table->longText('avatar')->nullable(); //255--->1000--> 1000 and above
            $table->string('password');
            $table->string('introduction', 100)->nullable();
            $table->unsignedBigInteger('role_id')
                ->default(2)    // 1: Admin, 2: Regular User
                ->comment('1: Admin, 2: Regular User');
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
