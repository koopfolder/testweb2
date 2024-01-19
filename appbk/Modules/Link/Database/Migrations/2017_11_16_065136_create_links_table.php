<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Silber\Bouncer\Database\Models;

class CreateLinksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('user_id')->unsigned();
			$table->enum('site', ['frontend', 'backend'])->default('frontend');
			$table->string('name');
			$table->string('slug')->nullable();
			$table->enum('link_type', ['internal', 'external'])->nullable();
			$table->text('url_internal')->nullable();
			$table->text('url_external')->nullable();
			$table->text('video')->nullable();
			$table->text('description')->nullable();
			$table->string('icon')->nullable();
			$table->integer('target')->nullable()->default(0);
			$table->integer('order')->nullable()->default(0);
			$table->string('classes', 100)->nullable();
			$table->string('layout', 100)->nullable();
			$table->string('module_slug', 100)->nullable();
			$table->string('module_ids', 100)->nullable();
			$table->enum('position', ['Footer Left', 'Footer Center', 'Footer Right'])->nullable();
			$table->enum('status', ['publish', 'draft'])->default('draft');
			$table->string('meta_title')->nullable();
			$table->string('meta_keywords')->nullable();
			$table->text('meta_description')->nullable();
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
		Schema::dropIfExists('links');
	}
}
