<?php
/**
 * Handles Brand's AJAX calls
 *
 * @package MartinCV_OpenAi_Post
 */

namespace MartinCV\OpenAiPost;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax Brand Class
 */
class OpenAi {
	use \MartinCV\OpenAiPost\Traits\Singleton;

	/**
	 * Base API Url
	 *
	 * @var string
	 */
	protected $base_url = 'https://api.openai.com/v1/';

	/**
	 * Initialize object
	 *
	 * @return void
	 */
	private function initialize() {
		$martincv_openai_post = get_option( '_martincv_openai_post', array() );

		$this->api_key = $martincv_openai_post['api_key'] ?? '';
	}

	/**
	 * Ontraport request
	 *
	 * @param  string $endpoint The endpoint to access on Ontraport.
	 * @param  array  $args     List of args to send to Ontraport.
	 *
	 * @return WP_Error|array
	 */
	public function request( $endpoint, $args = array() ) {
		if ( empty( $this->api_key ) ) {
			return new \WP_Error(
				400,
				__( 'OpenAi API Key missing', 'martincv-openai-post' )
			);
		}

		$default_request_args = array(
			'method'  => 'POST',
			'timeout' => '45',
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->api_key,
				'Content-Type'  => 'application/json',
			),
		);

		$request_args = wp_parse_args( $args, $default_request_args );

		$request_args['body']['model'] = 'text-davinci-003';

		$request_args['body'] = wp_json_encode( $request_args['body'] );

		$response = wp_remote_post( $this->base_url . $endpoint, $request_args );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $response['error']['message'] ) ) {
			return new \WP_Error(
				400,
				$response['error']['message']
			);
		}

		return $response;
	}

	/**
	 * Generate post
	 *
	 * @param string $post_title Title of the post.
	 * @param int    $words_number Number of words the post will have.
	 *
	 * @return string|WP_Error
	 */
	public function generate_post( $post_title, $words_number = 500 ) {
		if ( empty( $post_title ) ) {
			return new \WP_Error(
				400,
				__( 'Post Title is missing', 'martincv-openai-post' )
			);
		}

		if ( ! $words_number ) {
			return new \WP_Error(
				400,
				__( 'Please enter number of words', 'martincv-openai-post' )
			);
		}

		$prompt = 'Write blog post about "' . $post_title . '" with maximum of ' . $words_number . ' words';

		$request_args = array(
			'body' => array(
				'max_tokens'        => 2048,
				'temperature'       => 0.9,
				'top_p'             => 1,
				'frequency_penalty' => 0,
				'presence_penalty'  => 0,
				'prompt'            => $prompt,
			),
		);

		$response = $this->request( 'completions', $request_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( isset( $response['choices'][0]['text'] ) ) {
			return $response['choices'][0]['text'];
		}

		return '';
	}
}
