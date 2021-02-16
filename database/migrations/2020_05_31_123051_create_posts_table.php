<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('html')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('published')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('type');
            $table->text('custom_excerpt')->nullable();
            $table->unsignedBigInteger('author_id');
            
            $table->timestamps();
            
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
