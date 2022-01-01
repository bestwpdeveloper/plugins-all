<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       bestwpdeveloper.com/about
 * @since      1.0.0
 *
 * @package    Blogs_Column_Data
 * @subpackage Blogs_Column_Data/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Blogs_Column_Data
 * @subpackage Blogs_Column_Data/includes
 * @author     bestwpdeveloper.com <info@bestwpdeveloper.com>
 */
class Blogs_Column_Data_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'blogs-column-data',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
