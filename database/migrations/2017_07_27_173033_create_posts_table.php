<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

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
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('start_published')->nullable();
            $table->dateTime('end_published')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('revision_id')->default(0);
            $table->integer('order')->default(0);
            $table->integer('is_home')->default(0);
            $table->string('status', 100)->nullable();
            $table->integer('view')->default(0);
            $table->string('layout', 100)->nullable();
            $table->text('link')->nullable();
            $table->text('video')->nullable();
            $table->text('banners')->nullable();
            $table->text('caption1')->nullable();
            $table->text('caption2')->nullable();
            $table->integer('pin')->default(0);
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(Models::table('users'))
                  ->onUpdate('cascade')->onDelete('cascade');

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
