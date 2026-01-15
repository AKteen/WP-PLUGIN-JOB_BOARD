<?php
/**
 * Public class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board_Public {

	public function init() {
		add_shortcode( 'job_board', array( $this, 'render_job_board' ) );
	}

	public function render_job_board( $atts ) {
		$atts = shortcode_atts(
			array(
				'posts_per_page' => -1,
			),
			$atts,
			'job_board'
		);

		ob_start();

		$this->render_filters();

		$args = array(
			'post_type'      => 'my_job_board_job',
			'posts_per_page' => intval( $atts['posts_per_page'] ),
			'post_status'    => 'publish',
		);

		if ( ! empty( $_GET['job_type'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'my_job_board_job_type',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['job_type'] ),
			);
		}

		if ( ! empty( $_GET['location'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'my_job_board_location',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['location'] ),
			);
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			echo '<div class="my-job-board-listings">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$this->render_job_item( get_the_ID() );
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'No jobs found.', 'my-job-board' ) . '</p>';
		}

		return ob_get_clean();
	}

	private function render_filters() {
		$job_types = get_terms(
			array(
				'taxonomy'   => 'my_job_board_job_type',
				'hide_empty' => false,
			)
		);

		$locations = get_terms(
			array(
				'taxonomy'   => 'my_job_board_location',
				'hide_empty' => false,
			)
		);

		?>
		<form method="get" class="my-job-board-filters">
			<?php
			foreach ( $_GET as $key => $value ) {
				if ( ! in_array( $key, array( 'job_type', 'location' ), true ) ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
				}
			}
			?>
			<select name="job_type">
				<option value=""><?php esc_html_e( 'All Job Types', 'my-job-board' ); ?></option>
				<?php foreach ( $job_types as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( isset( $_GET['job_type'] ) ? $_GET['job_type'] : '', $term->slug ); ?>>
						<?php echo esc_html( $term->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<select name="location">
				<option value=""><?php esc_html_e( 'All Locations', 'my-job-board' ); ?></option>
				<?php foreach ( $locations as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( isset( $_GET['location'] ) ? $_GET['location'] : '', $term->slug ); ?>>
						<?php echo esc_html( $term->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<button type="submit"><?php esc_html_e( 'Filter', 'my-job-board' ); ?></button>
		</form>
		<?php
	}

	private function render_job_item( $post_id ) {
		$title        = apply_filters( 'my_job_board_job_title', get_the_title( $post_id ), $post_id );
		$company_name = get_post_meta( $post_id, '_my_job_board_company_name', true );
		$salary       = get_post_meta( $post_id, '_my_job_board_salary', true );
		$apply_url    = get_post_meta( $post_id, '_my_job_board_apply_url', true );
		$job_types    = wp_get_post_terms( $post_id, 'my_job_board_job_type' );
		$locations    = wp_get_post_terms( $post_id, 'my_job_board_location' );

		?>
		<div class="my-job-board-item">
			<h3><?php echo esc_html( $title ); ?></h3>
			<?php if ( $company_name ) : ?>
				<p><strong><?php esc_html_e( 'Company:', 'my-job-board' ); ?></strong> <?php echo esc_html( $company_name ); ?></p>
			<?php endif; ?>
			<?php if ( $salary ) : ?>
				<p><strong><?php esc_html_e( 'Salary:', 'my-job-board' ); ?></strong> <?php echo esc_html( $salary ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $job_types ) ) : ?>
				<p><strong><?php esc_html_e( 'Type:', 'my-job-board' ); ?></strong>
					<?php
					$type_names = array_map(
						function( $term ) {
							return esc_html( $term->name );
						},
						$job_types
					);
					echo implode( ', ', $type_names );
					?>
				</p>
			<?php endif; ?>
			<?php if ( ! empty( $locations ) ) : ?>
				<p><strong><?php esc_html_e( 'Location:', 'my-job-board' ); ?></strong>
					<?php
					$location_names = array_map(
						function( $term ) {
							return esc_html( $term->name );
						},
						$locations
					);
					echo implode( ', ', $location_names );
					?>
				</p>
			<?php endif; ?>
			<div class="my-job-board-content">
				<?php echo wp_kses_post( get_the_content( null, false, $post_id ) ); ?>
			</div>
			<?php if ( $apply_url ) : ?>
				<p><a href="<?php echo esc_url( $apply_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Now', 'my-job-board' ); ?></a></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
