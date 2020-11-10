<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://domosedov.info
 * @since      1.0.0
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/includes
 * @author     Aleksandr Grigorii <domosedov.dev@gmail.com>
 */
class Excellent_Exam_Core_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'excellent-exam-core',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
