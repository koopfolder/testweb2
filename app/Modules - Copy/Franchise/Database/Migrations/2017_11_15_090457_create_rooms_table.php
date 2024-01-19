<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->text('amenities')->nullable();
            $table->string('room')->nullable();
            $table->text('other_features')->nullable();
            $table->text('book_type', ['contact-us-to-book', 'make-a-reservation'])->nullable();
            $table->integer('guest')->nullable();
            $table->enum('status', ['publish', 'draft']);
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::table('rooms', function($table) {
            $table->foreign('category_id')->references('id')->on('room_categories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
