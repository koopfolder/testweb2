<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->enum('status', ['publish', 'draft']);
            $table->timestamps();
        });
        Schema::create('restaurant_open_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_id');
            $table->string('day');
            $table->string('opening');
            $table->string('closed');
            $table->text('message');
            $table->enum('status', ['publish', 'draft']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
        Schema::dropIfExists('restaurant_open_hours');
    }
}
