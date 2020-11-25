<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://domosedov.info
 * @since      1.0.0
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization and
 * site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/includes
 * @author     Aleksandr Grigorii <domosedov.dev@gmail.com>
 */
class Excellent_Exam_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Excellent_Exam_Core_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EXCELLENT_EXAM_CORE_VERSION' ) ) {
			$this->version = EXCELLENT_EXAM_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'excellent-exam-core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Excellent_Exam_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Excellent_Exam_Core_i18n. Defines internationalization functionality.
	 * - Excellent_Exam_Core_Hooks. Defines all hooks.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-excellent-exam-core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-excellent-exam-core-i18n.php';

		/**
		 * The class responsible for defining all actions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-excellent-exam-core-hooks.php';

		$this->loader = new Excellent_Exam_Core_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Excellent_Exam_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Excellent_Exam_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {
		$plugin_hooks = new Excellent_Exam_Core_Hooks( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_hooks, 'registerCustomPostTypes' );
		$this->loader->add_action( 'init', $plugin_hooks, 'registerCustomTaxonomies' );
		$this->loader->add_action( 'init', $plugin_hooks, 'registerCustomMeta' );
		$this->loader->add_action( 'rest_api_init', $plugin_hooks, 'registerCustomRoutes' );

		$this->loader->add_action( 'delete_attachment', $plugin_hooks, 'handleDeleteAttachment', 100, 2 );

		$this->loader->add_filter( 'pre_wp_unique_post_slug', $plugin_hooks, 'generateUniquePostSlug', 100, 6 );



	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Excellent_Exam_Core_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

}
