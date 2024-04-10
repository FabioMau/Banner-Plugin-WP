<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://scoprinetwork.com
 * @since      1.0.0
 *
 * @package    Banner
 * @subpackage Banner/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Banner
 * @subpackage Banner/includes
 * @author     Fabio Maulucci <fabio.maulucci@loscoprinetwork.it>
 */
class Banner
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Banner_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('BANNER_VERSION')) {
			$this->version = BANNER_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'banner';

		$this->load_dependencies();
		$this->set_locale();
		$this->load_post_types();
		$this->define_gutenberg_blocks();
		$this->define_widgets();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Banner_Loader. Orchestrates the hooks of the plugin.
	 * - Banner_i18n. Defines internationalization functionality.
	 * - Banner_Admin. Defines all hooks for the admin area.
	 * - Banner_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-banner-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-banner-i18n.php';

		// Custom post types dependencies
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/post-types/banner.php';

		// Widget dependencies
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/widget/banner-widget.php';

		$this->loader = new Banner_Loader();

		$this->loader->add_action('the_content', $this, 'add_template_single');
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Banner_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Banner_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Define the Post Types for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_post_types()
	{

		$plugin_PT_Banner = new Banner_PT_Banner();

		$this->loader->add_action('init', $plugin_PT_Banner, 'load_pt');
		$this->loader->add_action('add_meta_boxes', $plugin_PT_Banner, 'add_meta_boxes');
		$this->loader->add_action('do_meta_boxes', $plugin_PT_Banner, 'do_meta_boxes');
		$this->loader->add_action('rwmb_meta_boxes', $plugin_PT_Banner, 'add_rwmb_meta_boxes');
		$this->loader->add_action('after_setup_theme', $plugin_PT_Banner, 'add_banner_images_size');
		$this->loader->add_action('save_post_banner', $plugin_PT_Banner, 'save_post_banner');
	}

	/**
	 * Register Gutenberg Blocks for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_gutenberg_blocks()
	{
		register_block_type(plugin_dir_path(dirname(__FILE__)) . '/build');
	}

	/**
	 * Register Widgets for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_widgets()
	{

		$plugin_Widget_Banner = new Banner_Widget_Banner();

		$this->loader->add_action('widgets_init', $plugin_Widget_Banner, 'load_widget');
	}

	/**
	 * Add banner on single template for themes for Zox News.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function add_template_single($content)
	{

		// Impostare il percorso del file esterno da includere
		$file_path = plugin_dir_path(dirname(__FILE__)) . 'templates/post-single.php';

		// Verifica se il file esterno esiste
		if (file_exists($file_path)) {
			// Includi il file esterno
			ob_start();
			include $file_path;
			$file_content = ob_get_clean();

			$content .= $file_content;
		}

		return $content;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Banner_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
