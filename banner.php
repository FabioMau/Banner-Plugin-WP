<?php
/**
 *
 * @link              https://scoprinetwork.com
 * @since             1.0.0
 * @package           Banner
 *
 * @wordpress-plugin
 * Plugin Name:       Banner
 * Plugin URI:        https://scoprinetwork.com
 * Description:       Aggiunge i banner personalizzati in base a TAG degli articoli
 * Version:           1.0.2
 * Author:            Fabio Maulucci
 * Author URI:        https://scoprinetwork.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       banner
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BANNER_VERSION', '1.0.2' );

//register_activation_hook( __FILE__, 'activate_banner' );
//register_deactivation_hook( __FILE__, 'deactivate_banner' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-banner.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_banner() {

	$plugin = new Banner();
	$plugin->run();

}
run_banner();