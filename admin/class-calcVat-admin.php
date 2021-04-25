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
class calcVat_Admin
{

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

	private $postType;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $calcVat       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($calcVat, $version, $postType)
	{

		$this->calcVat = $calcVat;
		$this->version = $version;
		$this->postType = $postType;
	
		$this->add_settings_page();
	
	}
		/**
	 * Add settings page
	 *
	 * @since    1.0.0
	 */
	private function add_settings_page(){

		require plugin_dir_path(__FILE__) . 'partials/calcVat-admin-display.php';
		add_action( 'admin_init',  'cvregister_settings' );
		add_action( 'admin_menu', [$this, 'cvadd_settings_page'] );
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->calcVat, plugin_dir_url(__FILE__) . 'css/calcVat-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->calcVat, plugin_dir_url(__FILE__) . 'js/calcVat-admin.js', array('jquery'), $this->version, false);
	}

	public function initial_custom_table()
	{

		add_filter('views_edit-' . $this->postType, [$this, "render_table"]);

		//$list_table->display(); 

	}

	public function render_table()
	{
		require_once plugin_dir_path(__FILE__) . 'calcVat-admin-custom-table.php';
		global $wp_list_table;
		echo '<div class="updated custom-notice">' . __('Add this shortcode to page') . '<p>[vat_form currency="PLN" vat_options="23=%&22=%&8=%&7=%&5=%&3=%&0=%&o.o&zw"]</p></div>';

		$list_table = 	new Vat_List($this->postType);	

		$list_table->prepare_items();

		$wp_list_table = $list_table;

		add_action('current_screen', [$list_table, 'remove_search_filter'], 11);
		add_action('admin_notices', [$this, 'my_admin_notice']);
	}


	function admin_notice()
	{

		global $pagenow;

		if (($pagenow == 'edit.php') && ($_GET['post_type'] == $this->postType)) {
			echo '<div class="updated custom-notice"><p>[vat_form currency="PLN" vat_options="23=%&22=%&8=%&7=%&5=%&3=%&0=%&o.o&zw"]</p></div>';
		}
	}

	function cvadd_settings_page() {
    add_options_page( 'CalcVat Options', 'CalcVat', 'manage_options', 'dbi-example-plugin', 'cvrender_plugin_settings_page' );
}

}
