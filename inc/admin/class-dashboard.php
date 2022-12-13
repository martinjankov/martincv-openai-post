<?php
/**
 * Admin dashboard settings
 *
 * @package    MartinCV_OpenAi_Post
 */

namespace MartinCV\OpenAiPost\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin dashboard class
 */
class Dashboard {
	use \MartinCV\OpenAiPost\Traits\Singleton;

	/**
	 * Initialize class
	 *
	 * @return  void
	 */
	private function initialize() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}

	/**
	 * Register new submenu and Settings
	 *
	 * @return  void
	 */
	public function menu() {
		add_submenu_page(
			'options-general.php',
			__( 'OpenAi Post', 'martincv-openai-post' ),
			__( 'OpenAi Post', 'martincv-openai-post' ),
			'administrator',
			'martincv-openai-post-settings',
			array( $this, 'settings' )
		);
	}

	/**
	 * Show the settings form with options
	 *
	 * @return  void
	 */
	public function settings() {
		$martincv_openai_post = get_option( '_martincv_openai_post', array() );

		require_once MARTINCV_OPENAI_POST_PLUGIN_DIR . 'views/admin/template-admin-dashboard.php';
	}

	/**
	 * Register plugin settings
	 *
	 * @return  void
	 */
	public function register_settings() {
		register_setting(
			'martincv-openai-post-settings-group',
			'_martincv_openai_post',
			array(
				'sanitize_callback' => array( $this, 'sanitize_options' ),
			)
		);
	}

	/**
	 * Sanitize the fields before saving
	 *
	 * @param mixed<string|array> $option The option to be sanitized.
	 *
	 * @return  array
	 */
	public function sanitize_options( $option ) {
		if ( is_array( $option ) ) {
			foreach ( $option as $field => $value ) {
				if ( empty( $value ) ) {
					unset( $option[ $field ] );
					continue;
				}

				if ( is_numeric( $value ) ) {
					$option[ $field ] = $value;
				} else {
					if ( is_array( $value ) ) {
						$option[ $field ] = $this->sanitize_options( $value );
					} else {
						$option[ $field ] = sanitize_text_field( $value );
					}
				}
			}

			return array_filter( $option );
		}

		return $option;
	}
}
