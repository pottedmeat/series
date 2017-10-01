<?php
/**
 * Plugin Name: Series
 * Plugin URI: http://themehybrid.com/plugins/series
 * Description: Creates a new taxonomy called "Series" that allows you to tie posts together in a series.
 * Version: 1.0.0
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not,
 * write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   Series
 * @version   1.0.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2009 - 2015, Justin Tadlock
 * @link      http://themehybrid.com/plugins/series
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Series;

/**
 * Singleton class that sets up and initializes the plugin.
 *
 * @since  2.0.0
 * @access public
 * @return void
 */
final class Plugin {

	/**
	 * Plugin directory path.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $dir = '';

	/**
	 * Plugin directory URI.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    string
	 */
	public $uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  2.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function __toString() {
		return 'series';
	}

	/**
	 * Sets up globals.
	 *
	 * @since  2.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		// Main plugin directory path and URI.
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );
	}

	/**
	 * Loads files needed by the plugin.
	 *
	 * @since  2.0.0
	 * @access private
	 * @return void
	 */
	private function includes() {

		require_once( $this->dir . 'inc/functions-options.php'    );
		require_once( $this->dir . 'inc/functions-rewrite.php'    );
		require_once( $this->dir . 'inc/functions-shortcodes.php' );
		require_once( $this->dir . 'inc/functions-taxonomies.php' );
		require_once( $this->dir . 'inc/functions-deprecated.php' );

		require_once( "{$this->dir}inc/template.php"                  );
		require_once( "{$this->dir}inc/class-widget-list-posts.php"   );
		require_once( "{$this->dir}inc/class-widget-list-related.php" );
	}

	/**
	 * Sets up main plugin actions and filters.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Register widgets. */
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'series', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function register_widgets() {
		register_widget( 'Series_Widget_List_Posts'   );
		register_widget( 'Series_Widget_List_Related' );
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function activation() {

		// Get the administrator role.
		$role = get_role( 'administrator' );

		// If the administrator role exists, add required capabilities for the plugin.
		if ( ! empty( $role ) ) {

			$role->add_cap( 'manage_series' );
		}
	}
}

/**
 * Gets the instance of the `Plugin` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 *
 * @since  2.0.0
 * @access public
 * @return object
 */
function plugin() {

	return Plugin::get_instance();
}

// Let's roll!
plugin();
