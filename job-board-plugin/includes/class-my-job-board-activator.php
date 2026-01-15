<?php
/**
 * Activator class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board_Activator {

	public static function activate() {
		self::create_default_terms();
		flush_rewrite_rules();
	}

	private static function create_default_terms() {
		$job_types = array( 'Internship', 'Full-Time', 'Contract' );
		foreach ( $job_types as $type ) {
			if ( ! term_exists( $type, 'my_job_board_job_type' ) ) {
				wp_insert_term( $type, 'my_job_board_job_type' );
			}
		}

		$locations = array( 'Remote', 'Pune', 'Bangalore' );
		foreach ( $locations as $location ) {
			if ( ! term_exists( $location, 'my_job_board_location' ) ) {
				wp_insert_term( $location, 'my_job_board_location' );
			}
		}
	}
}
