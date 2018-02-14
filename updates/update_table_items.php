<?php namespace Zollerboy\Navigation\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * update_table_items.php
 */
class UpdateTableItems extends Migration {

	public function up() {
		Schema::table('zollerboy_navigation_items', function ($table) {
			$table->string('link');
		});
	}

	public function down() {
		Schema::table('zollerboy_navigation_items', function ($table) {
			$table->dropColumn('link');
		});
	}
}

?>
