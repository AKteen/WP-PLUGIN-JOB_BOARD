<?php
/**
 * Uninstall script.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$post_type = 'my_job_board_job';
$taxonomies = array( 'my_job_board_job_type', 'my_job_board_location' );

$posts = get_posts(
	array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_status'    => 'any',
	)
);

foreach ( $posts as $post ) {
	wp_delete_post( $post->ID, true );
}

foreach ( $taxonomies as $taxonomy ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);

	foreach ( $terms as $term ) {
		wp_delete_term( $term->term_id, $taxonomy );
	}
}

$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_my_job_board_%'" );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'my_job_board_%'" );
