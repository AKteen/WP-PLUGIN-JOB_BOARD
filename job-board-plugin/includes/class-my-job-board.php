<?php
/**
 * Core plugin class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board {

	protected $admin;
	protected $public_instance;

	public function __construct() {
		$this->load_dependencies();
		$this->admin           = new My_Job_Board_Admin();
		$this->public_instance = new My_Job_Board_Public();
	}

	private function load_dependencies() {
		require_once MY_JOB_BOARD_PLUGIN_DIR . 'admin/class-my-job-board-admin.php';
		require_once MY_JOB_BOARD_PLUGIN_DIR . 'public/class-my-job-board-public.php';
		require_once MY_JOB_BOARD_PLUGIN_DIR . 'includes/class-my-job-board-rest-api.php';
	}

	public function run() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'rest_api_init', array( 'My_Job_Board_Rest_API', 'register_routes' ) );

		$this->admin->init();
		$this->public_instance->init();
	}

	public function register_post_type() {
		register_post_type(
			'my_job_board_job',
			array(
				'labels'             => array(
					'name'          => __( 'Jobs', 'my-job-board' ),
					'singular_name' => __( 'Job', 'my-job-board' ),
					'add_new_item'  => __( 'Add New Job', 'my-job-board' ),
					'edit_item'     => __( 'Edit Job', 'my-job-board' ),
				),
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'rewrite'            => array( 'slug' => 'jobs' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'menu_icon'          => 'dashicons-businessman',
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
			)
		);
	}

	public function register_taxonomies() {
		register_taxonomy(
			'my_job_board_job_type',
			array( 'my_job_board_job' ),
			array(
				'labels'            => array(
					'name'          => __( 'Job Types', 'my-job-board' ),
					'singular_name' => __( 'Job Type', 'my-job-board' ),
				),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'job-type' ),
				'show_in_rest'      => true,
			)
		);

		register_taxonomy(
			'my_job_board_location',
			array( 'my_job_board_job' ),
			array(
				'labels'            => array(
					'name'          => __( 'Locations', 'my-job-board' ),
					'singular_name' => __( 'Location', 'my-job-board' ),
				),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'location' ),
				'show_in_rest'      => true,
			)
		);
	}
}
