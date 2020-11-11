<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://domosedov.info
 * @since             1.0.0
 * @package           Excellent_Exam_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Excellent Exam
 * Plugin URI:        https://github.com/domosedov/excellent-exam
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Aleksandr Grigorii
 * Author URI:        https://domosedov.info
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       excellent-exam-core
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
define( 'EXCELLENT_EXAM_CORE_VERSION', '1.0.0' );
define( 'EXCELLENT_EXAM_CORE_PREFIX', 'eec_');
define( 'EXCELLENT_EXAM_CORE_API_NAMESPACE', 'eec/v1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-excellent-exam-core-activator.php
 */
function activate_excellent_exam_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excellent-exam-core-activator.php';
	Excellent_Exam_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-excellent-exam-core-deactivator.php
 */
function deactivate_excellent_exam_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-excellent-exam-core-deactivator.php';
	Excellent_Exam_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_excellent_exam_core' );
register_deactivation_hook( __FILE__, 'deactivate_excellent_exam_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-excellent-exam-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_excellent_exam_core() {

	$plugin = new Excellent_Exam_Core();
	$plugin->run();

}
run_excellent_exam_core();
