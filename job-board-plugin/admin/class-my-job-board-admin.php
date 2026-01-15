<?php
/**
 * Admin class.
 *
 * @package My_Job_Board
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class My_Job_Board_Admin {

	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		add_filter( 'manage_my_job_board_job_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_my_job_board_job_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'my_job_board_job_details',
			__( 'Job Details', 'my-job-board' ),
			array( $this, 'render_meta_box' ),
			'my_job_board_job',
			'normal',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		wp_nonce_field( 'my_job_board_save_meta', 'my_job_board_meta_nonce' );

		$company_name = get_post_meta( $post->ID, '_my_job_board_company_name', true );
		$salary       = get_post_meta( $post->ID, '_my_job_board_salary', true );
		$apply_url    = get_post_meta( $post->ID, '_my_job_board_apply_url', true );
		?>
		<p>
			<label for="my_job_board_company_name"><?php esc_html_e( 'Company Name:', 'my-job-board' ); ?></label><br>
			<input type="text" id="my_job_board_company_name" name="my_job_board_company_name" value="<?php echo esc_attr( $company_name ); ?>" class="widefat">
		</p>
		<p>
			<label for="my_job_board_salary"><?php esc_html_e( 'Salary:', 'my-job-board' ); ?></label><br>
			<input type="text" id="my_job_board_salary" name="my_job_board_salary" value="<?php echo esc_attr( $salary ); ?>" class="widefat">
		</p>
		<p>
			<label for="my_job_board_apply_url"><?php esc_html_e( 'Apply URL:', 'my-job-board' ); ?></label><br>
			<input type="url" id="my_job_board_apply_url" name="my_job_board_apply_url" value="<?php echo esc_url( $apply_url ); ?>" class="widefat">
		</p>
		<?php
	}

	public function save_meta_boxes( $post_id ) {
		if ( ! isset( $_POST['my_job_board_meta_nonce'] ) || ! wp_verify_nonce( $_POST['my_job_board_meta_nonce'], 'my_job_board_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['my_job_board_company_name'] ) ) {
			update_post_meta( $post_id, '_my_job_board_company_name', sanitize_text_field( $_POST['my_job_board_company_name'] ) );
		}

		if ( isset( $_POST['my_job_board_salary'] ) ) {
			update_post_meta( $post_id, '_my_job_board_salary', sanitize_text_field( $_POST['my_job_board_salary'] ) );
		}

		if ( isset( $_POST['my_job_board_apply_url'] ) ) {
			update_post_meta( $post_id, '_my_job_board_apply_url', esc_url_raw( $_POST['my_job_board_apply_url'] ) );
		}

		do_action( 'my_job_board_after_job_save', $post_id );
	}

	public function add_custom_columns( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'title' === $key ) {
				$new_columns['company'] = __( 'Company', 'my-job-board' );
				$new_columns['salary']  = __( 'Salary', 'my-job-board' );
			}
		}
		return $new_columns;
	}

	public function render_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'company':
				echo esc_html( get_post_meta( $post_id, '_my_job_board_company_name', true ) );
				break;
			case 'salary':
				echo esc_html( get_post_meta( $post_id, '_my_job_board_salary', true ) );
				break;
		}
	}
}
