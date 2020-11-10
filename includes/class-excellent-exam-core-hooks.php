<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://domosedov.info
 * @since      1.0.0
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/public
 */

/**
 * The functionality of the plugin.
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/public
 * @author     Aleksandr Grigorii <domosedov.dev@gmail.com>
 */
class Excellent_Exam_Core_Hooks
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function test()
    {
        echo 'Working!';
    }

}
