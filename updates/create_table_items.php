<?php namespace Zollerboy\Navigation\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * create_table_items.php
 */
class CreateTableItems extends Migration {

	public function up() {
		Schema::create('zollerboy_navigation_items', function ($table) {
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->integer('order');
			$table->string('title');
			$table->string('description')->nullable();
			$table->integer('access')->default(1);
			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('zollerboy_navigation_items');
	}
}

?>
