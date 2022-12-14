<?php
/**
 * Hide Posts Metabox class
 *
 * @package    MartinCV_OpenAi_Post
 */

namespace MartinCV\OpenAiPost\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post class.
 */
class Post {
	use \MartinCV\OpenAiPost\Traits\Singleton;

	/**
	 * Enabled for post types
	 *
	 * @var array
	 */
	private $enabled_post_types = array( 'post', 'page' );

	/**
	 * Initialize class
	 *
	 * @return  void
	 */
	private function initialize() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
	}

	/**
	 * Load admin assets
	 *
	 * @return  void
	 */
	public function load_admin_assets() {
		global $post;

		if ( ! $post ) {
			return;
		}

		if ( ! in_array( $post->post_type, $this->enabled_post_types, true ) ) {
			return;
		}

		wp_enqueue_script(
			'martincv-openai-post',
			MARTINCV_OPENAI_POST_PLUGIN_URL . 'assets/admin/js/post.js',
			array( 'jquery' ),
			MARTINCV_OPENAI_POST_VERSION,
			true
		);

		wp_localize_script(
			'martincv-openai-post',
			'martinCVOpenAiPost',
			array(
				'selectImageLabel' => __( 'Select image size', 'martincv-openai-post' ),
			)
		);

		wp_enqueue_style(
			'martincv-openai-post',
			MARTINCV_OPENAI_POST_PLUGIN_URL . 'assets/admin/css/post.css',
			array(),
			MARTINCV_OPENAI_POST_VERSION
		);
	}

	/**
	 * Add Post Hide metabox in sidebar top
	 *
	 * @return void
	 */
	public function add_metabox() {
		add_meta_box(
			'martincv_openai_post',
			__( 'OpenAi', 'martincv-openai-post' ),
			array( $this, 'metabox_callback' ),
			$this->enabled_post_types,
			'side',
			'high'
		);
	}

	/**
	 * Show the metabox template in sidebar top
	 *
	 * @param  WP_Post $post Current post object.
	 *
	 * @return void
	 */
	public function metabox_callback( $post ) {
		$martincv_openai_post = get_option( '_martincv_openai_post', array() );

		require_once MARTINCV_OPENAI_POST_PLUGIN_DIR . 'views/admin/template-admin-post-metabox.php';
	}
}
