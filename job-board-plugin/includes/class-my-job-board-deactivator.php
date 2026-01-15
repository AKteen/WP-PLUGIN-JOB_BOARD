<?php
/**
 * Deactivator class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board_Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();
	}
}
