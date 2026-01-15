<?php
/**
 * REST API class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board_Rest_API {

	public static function register_routes() {
		register_rest_route(
			'my_job_board/v1',
			'/jobs',
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'get_jobs' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public static function get_jobs( $request ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'my_job_board_job',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		$jobs = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				$jobs[] = array(
					'id'           => $post_id,
					'title'        => get_the_title(),
					'content'      => get_the_content(),
					'company_name' => get_post_meta( $post_id, '_my_job_board_company_name', true ),
					'salary'       => get_post_meta( $post_id, '_my_job_board_salary', true ),
					'apply_url'    => get_post_meta( $post_id, '_my_job_board_apply_url', true ),
					'job_types'    => wp_get_post_terms( $post_id, 'my_job_board_job_type', array( 'fields' => 'names' ) ),
					'locations'    => wp_get_post_terms( $post_id, 'my_job_board_location', array( 'fields' => 'names' ) ),
					'date'         => get_the_date( 'c' ),
				);
			}
			wp_reset_postdata();
		}

		return new WP_REST_Response( $jobs, 200 );
	}
}
