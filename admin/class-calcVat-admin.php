<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    calcVat
 * @subpackage calcVat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    calcVat
 * @subpackage calcVat/admin
 * @author     jozdowska.edyta@protonmail.com
 */
class calcVat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $calcVat    The ID of this plugin.
	 */
	private $calcVat;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $calcVat       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $calcVat, $version ) {

		$this->calcVat = $calcVat;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in calcVat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The calcVat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->calcVat, plugin_dir_url( __FILE__ ) . 'css/calcVat-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in calcVat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The calcVat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->calcVat, plugin_dir_url( __FILE__ ) . 'js/calcVat-admin.js', array( 'jquery' ), $this->version, false );

	}

}
