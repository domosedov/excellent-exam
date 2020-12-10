<?php

/**
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
 */

use Domosed\EEC\App;
use Domosed\EEC\AppActivator;
use Domosed\EEC\AppDeactivator;
use Domosed\EEC\AppHooks;
use Domosed\EEC\AppLoader;

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'EEC_VERSION', '1.0.0' );
define( 'EEC_PREFIX', 'eec_' );
define( 'EEC_API_NAMESPACE', 'eec/v1' );
define( 'EEC_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EEC_DIR_URL', plugin_dir_url( __FILE__ ) );

function bootstrap() {

	$loader = new AppLoader();
	$hooks  = new AppHooks();
	$plugin = new App( $loader, $hooks );

	register_activation_hook( __FILE__, [ $plugin, 'createTables' ] );
	register_deactivation_hook( __FILE__, [ $plugin, 'dropTables' ] );

	$plugin->run();

}

bootstrap();
