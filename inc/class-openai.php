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
	 * List of OpenAi Options
	 *
	 * @var array
	 */
	protected $martincv_openai_post = array();

	/**
	 * Initialize object
	 *
	 * @return void
	 */
	private function initialize() {
		$this->martincv_openai_post = get_option( '_martincv_openai_post', array() );

		$this->api_key = $this->martincv_openai_post['api_key'] ?? '';
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
	 * Generation completioon
	 *
	 * @param  string $prompt The promt send to the AI.
	 * @param  array  $args   List of additioal arguments to form the request call.
	 *
	 * @return WP_Response
	 */
	public function completion( $prompt, $args ) {
		$max_tokens        = $this->martincv_openai_post['max_tokens'] ?? 256;
		$temperature       = $this->martincv_openai_post['temperature'] ?? 0.7;
		$top_p             = $this->martincv_openai_post['top_p'] ?? 1;
		$frequency_penalty = $this->martincv_openai_post['frequency_penalty'] ?? 0;
		$presence_penalty  = $this->martincv_openai_post['presence_penalty'] ?? 0;
		$best_of           = $this->martincv_openai_post['best_of'] ?? 1;

		if ( ! empty( $args['max_tokens'] ) ) {
			$max_tokens = absint( $args['max_tokens'] );
		}

		if ( ! empty( $args['temperature'] ) ) {
			$temperature = (float) $args['temperature'];
		}

		if ( ! empty( $args['top_p'] ) ) {
			$top_p = (float) $args['top_p'];
		}

		if ( ! empty( $args['frequency_penalty'] ) ) {
			$frequency_penalty = (float) $args['frequency_penalty'];
		}

		if ( ! empty( $args['presence_penalty'] ) ) {
			$presence_penalty = (float) $args['presence_penalty'];
		}

		if ( ! empty( $args['best_of'] ) ) {
			$best_of = absint( $args['best_of'] );
		}

		$request_args = array(
			'body' => array(
				'model'             => 'text-davinci-003',
				'max_tokens'        => (int) $max_tokens,
				'temperature'       => (float) $temperature,
				'top_p'             => (float) $top_p,
				'frequency_penalty' => (float) $frequency_penalty,
				'presence_penalty'  => (float) $presence_penalty,
				'best_of'           => (int) $best_of,
				'prompt'            => $prompt,
			),
		);

		return $this->request( 'completions', $request_args );
	}

	/**
	 * Generate image(s) using OpenAi
	 *
	 * @param  string $prompt        What the images to be realated to.
	 * @param  int    $images_number Number of images to be generated.
	 * @param  string $size          Images size.
	 *
	 * @return array
	 */
	public function image( $prompt, $images_number, $size ) {
		if ( ! in_array( $size, array( '256x256', '512x512', '1024x1024' ), true ) ) {
			return new \WP_Error(
				400,
				__( 'Unsupported image size', 'martincv-openai-post' )
			);
		}

		$request_args = array(
			'body' => array(
				'prompt' => sanitize_text_field( $prompt ),
				'n'      => absint( $images_number ),
				'size'   => $size,
			),
		);

		return $this->request( 'images/generations', $request_args );
	}

	/**
	 * Generate post
	 *
	 * @param array $args Post args.
	 *
	 * @return string|WP_Error
	 */
	public function generate_post( $args ) {
		if ( empty( $args['post_title'] ) ) {
			return new \WP_Error(
				400,
				__( 'Post Title is missing', 'martincv-openai-post' )
			);
		}

		$include_introduction = $args['introduction'] ?? false;
		$include_conclusion   = $args['conclusion'] ?? false;
		$images_number        = $args['images_number'] ?? 0;
		$headings_number      = $args['headings_number'] ?? 0;

		$main_prompt = 'Write blog post about "' . $args['post_title'] . '".';
		if ( $headings_number > 0 ) {
			$main_prompt .= ' Add ' . $headings_number . ' headings.';

			if ( $include_introduction ) {
				$main_prompt .= ' Skip introduction.';
			}

			if ( $include_conclusion ) {
				$main_prompt .= ' Skip conclusion.';
			}
		}

		$main_response = $this->completion( $main_prompt, $args );

		if ( is_wp_error( $main_response ) ) {
			return $main_response;
		}

		if ( $include_introduction ) {
			$introduction_prompt = 'Write introduction paragpraph about "' . $args['post_title'] . '".';

			$introduction_response = $this->completion( $introduction_prompt, $args );
		}

		if ( $include_conclusion ) {
			$conclusion_prompt = 'Write conclusion paragraph "' . $args['post_title'] . '".';

			$conclusion_response = $this->completion( $conclusion_prompt, $args );
		}

		$final = '';

		if ( isset( $introduction_response['choices'][0]['text'] ) ) {
			$final .= "\n\n<h3>Introduction</h3>\n\n" . $introduction_response['choices'][0]['text'];
		}

		if ( isset( $main_response['choices'][0]['text'] ) ) {
			$final .= $main_response['choices'][0]['text'];
		}

		if ( isset( $conclusion_response['choices'][0]['text'] ) ) {
			$final .= "\n\n<h3>Conclusion</h3>\n\n" . $conclusion_response['choices'][0]['text'];
		}

		$images_tags = array();

		if ( absint( $args['images_number'] ?? 0 ) ) {
			$image_prompt = $args['post_title'];

			$images = $this->image( $image_prompt, $args['images_number'], $args['image_size'] );

			if ( ! is_wp_error( $images ) && ! empty( $images['data'] ) ) {
				$image_size = explode( 'x', $args['image_size'] );
				foreach ( $images['data'] as $img_url ) {
					$images_tags[] = '<img src="' . esc_url( $img_url['url'] ) . '" alt="' . esc_attr( $args['post_title'] ) . '" height="' . esc_attr( $image_size[0] ) . '" width="' . esc_attr( $image_size[1] ) . '">';
				}
			}
		}

		return $final . '###images###' . implode( '|', $images_tags );
	}
}
