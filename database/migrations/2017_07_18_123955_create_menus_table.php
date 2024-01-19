<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('user_id')->unsigned();
            $table->integer('post_id')->nullable();
            $table->string('title');
            $table->string('url_internal')->nullable();
            $table->string('url_external')->nullable();
            $table->string('video')->nullable();
            $table->text('summary')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('target')->default(false);
            $table->integer('order')->nullable()->default(0);
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
        Schema::dropIfExists('menus');
    }
}
