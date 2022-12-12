<?php
/**
 * Handles Post's AJAX calls
 *
 * @package MartinCV_OpenAi_Post
 */

namespace MartinCV\OpenAiPost\AJAX;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax Post Class
 */
class Post {
	use \MartinCV\OpenAiPost\Traits\Singleton;

	/**
	 * Initialize class
	 *
	 * @return void
	 */
	private function initialize() {
		add_action( 'wp_ajax_martincv_openai_generate_post', array( $this, 'generate_post' ) );
	}

	/**
	 * AJAX - Generate post
	 *
	 * Action: martincv_openai_generate_post
	 *
	 * @return void
	 */
	public function generate_post() {
		$nonce_check = check_ajax_referer( 'ajax-martincv-openai-post-nonce', '__nonce', false );

		if ( ! $nonce_check ) {
			wp_send_json_error(
				__( 'Security check failed. Refresh page and try again', 'martincv-openai-post' ),
				403
			);
		}

		$words_number = absint( $_POST['words_number'] ?? 0 );
		$post_title   = sanitize_text_field( wp_unslash( $_POST['post_title'] ?? '' ) );

		$openai_post = \MartinCV\OpenAiPost\OpenAi::get_instance()->generate_post( $post_title, $words_number );

		if ( is_wp_error( $openai_post ) ) {
			wp_send_json_error(
				$openai_post->get_error_message(),
				$openai_post->get_error_code()
			);
		}

		wp_send_json_success( $openai_post );
	}
}
