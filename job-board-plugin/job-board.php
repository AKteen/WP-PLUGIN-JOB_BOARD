<?php
/**
 * Plugin Name: Job Board
 * Description: A mini job portal for WordPress with job listings, filtering, and REST API support.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL-2.0+
 * Text Domain: my-job-board
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MY_JOB_BOARD_VERSION', '1.0.0' );
define( 'MY_JOB_BOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MY_JOB_BOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MY_JOB_BOARD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once MY_JOB_BOARD_PLUGIN_DIR . 'includes/class-my-job-board-activator.php';
require_once MY_JOB_BOARD_PLUGIN_DIR . 'includes/class-my-job-board-deactivator.php';
require_once MY_JOB_BOARD_PLUGIN_DIR . 'includes/class-my-job-board.php';

register_activation_hook( __FILE__, array( 'My_Job_Board_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'My_Job_Board_Deactivator', 'deactivate' ) );

function my_job_board_run() {
	$plugin = new My_Job_Board();
	$plugin->run();
}

my_job_board_run();
