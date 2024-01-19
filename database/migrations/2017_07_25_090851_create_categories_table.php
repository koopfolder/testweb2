<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('parent_id')->default(0);
            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->string('module')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['publish', 'draft'])->default('draft');
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
        Schema::dropIfExists('categories');
    }
}
