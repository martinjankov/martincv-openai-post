<?php
/**
 * Plugin Name: MartinCV OpenAI Blog Post
 * Description: Creates blog posts using the OpenAI API
 * Author:      MartinCV
 * Author URI:  https://www.martincv.com
 * Version:     1.0
 * Text Domain: martincv-openai-post
 *
 * @package    MartinCV_OpenAi_Post
 * @author     MartinCV
 * @since      1.0
 * @license    GPL-3.0+
 * @copyright  Copyright (c) 2022, MartinCV
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 */
final class MartinCV_OpenAi_Post {
	/**
	 * Instance of the plugin
	 *
	 * @var MartinCV_OpenAi_Post
	 */
	private static $instance;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $version = '1.0';

	/**
	 * Instance of this plugin
	 *
	 * @return  MartinCV_OpenAi_Post
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MartinCV_OpenAi_Post ) ) {
			self::$instance = new MartinCV_OpenAi_Post();
			self::$instance->constants();
			self::$instance->includes();

			add_action( 'plugins_loaded', array( self::$instance, 'run' ) );
		}

		return self::$instance;
	}

	/**
	 * 3rd party includes
	 *
	 * @return  void
	 */
	private function includes() {
		require_once MARTINCV_OPENAI_POST_PLUGIN_DIR . 'inc/core/autoloader.php';
	}

	/**
	 * Define plugin constants
	 *
	 * @return  void
	 */
	private function constants() {
		// Plugin version.
		if ( ! defined( 'MARTINCV_OPENAI_POST_VERSION' ) ) {
			define( 'MARTINCV_OPENAI_POST_VERSION', $this->version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MARTINCV_OPENAI_POST_PLUGIN_DIR' ) ) {
			define( 'MARTINCV_OPENAI_POST_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MARTINCV_OPENAI_POST_PLUGIN_URL' ) ) {
			define( 'MARTINCV_OPENAI_POST_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MARTINCV_OPENAI_POST_PLUGIN_FILE' ) ) {
			define( 'MARTINCV_OPENAI_POST_PLUGIN_FILE', __FILE__ );
		}
	}

	/**
	 * Initialize classes / objects here
	 *
	 * @return  void
	 */
	public function run() {
		$this->load_textdomain();

		// Init classes if is Admin/Dashboard.
		if ( is_admin() ) {
			\MartinCV\OpenAiPost\Admin\Dashboard::get_instance();
			\MartinCV\OpenAiPost\Admin\Post::get_instance();

			\MartinCV\OpenAiPost\AJAX\Post::get_instance();
		}
	}

	/**
	 * Register textdomain
	 *
	 * @return  void
	 */
	private function load_textdomain() {
		load_plugin_textdomain( 'martincv-openai-post', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

MartinCV_OpenAi_Post::instance();
