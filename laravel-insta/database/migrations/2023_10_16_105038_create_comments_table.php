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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();//integer
            $table->text('body'); //text data type that candle character up to 1000
            $table->unsignedBigInteger('user_id');//the id of the user who created the comment
            $table->unsignedBigInteger('post_id');//the id of the post being commented
            $table->timestamps();
        
            //Link the fields to users and posts table
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');//data is deleted on post

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
