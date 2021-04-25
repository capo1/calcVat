<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    calcVat
 * @subpackage calcVat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    calcVat
 * @subpackage calcVat/public
 * @author     Your Name <email@example.com>
 */
class calcVat_Public
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
	 * @param      string    $calcVat       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($calcVat, $version, $postType)
	{

		$this->calcVat = $calcVat;
		$this->version = $version;
		$this->postType = $postType;

		add_action('wp_ajax_nopriv_save_vat_posts', array($this, 'save_vat_posts'));
		add_action('wp_ajax_save_vat_posts', array($this, 'save_vat_posts'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->calcVat, plugin_dir_url(__FILE__) . 'css/calcVat-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->calcVat, plugin_dir_url(__FILE__) . 'js/calcVat-public.js', array('jquery'), $this->version, false);

		$options = get_option( 'cv_plugin_options' );

		wp_localize_script(
			$this->calcVat,
			'calcVat_settings',
			array(
				'error' => __('An unknown error has occurred', 'calcVat'),
				'action' => 'save_vat_posts',
				'ajaxurl'    => admin_url('admin-ajax.php'),
				'lang_currency' => str_replace('_', '-', get_locale()),
				'localStringCurrency' => ['style' => 'currency', 'currency' => '']
			)
		);
	}

	/**
	 * This function is responsible for validating the fields from form
	 */
	private function validateForm($v)
	{

		$errors = [];
		foreach ($v as $key => $value) :
			if (empty($value) || $value === '0') :
				$errors[] = 'empty_' . $key;
			endif;
		endforeach;

		return $errors;
	}


	/**
	 * This function is responsible for prepare data to dave it to DB
	 */
	public function save_vat_posts()
	{
		if (!isset($_POST['cpt_vat_field']) || !wp_verify_nonce($_POST['cpt_vat_field'], 'cpt_vat_action'))
			return;

		$values = array(
			'product_name'    => sanitize_text_field($_POST['product_name']),
			'ammount_netto'  => floatval($_POST['ammount_netto']),
			'currency'   => sanitize_text_field($_POST['currency']),
			'used_vat'   => sanitize_text_field($_POST['used_vat'])
		);

		$url = wp_get_referer();

		$errors = $this->validateForm($values);

		if (!empty($errors)) {
			wp_redirect($url . '?error=' . implode(',', $errors));
			exit();
		}

	  $result = __("The product price %s is: %0.2f PLN gross. The tax amount is:  %0.2f PLN.");
		$vat = ($values['ammount_netto'] * intval($values['used_vat']) / 100);
		$calculated = 	$values['ammount_netto'] + $vat;

		$values =	array_merge(
			$values,
			[
				'calc' => $calculated,
				'date' => current_time('mysql'),
				'ip' => $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR']
			]
		);

		$this->save_to_db($values);

		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = ['success' => sprintf($result, $values['product_name'], $calculated, $vat) ];
			wp_send_json($result);
		} else {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
	}

	/**
	 * This function is responsible for save data to DB
	 */
	private function save_to_db($values)
	{

		$post = array(
			'post_content'    => maybe_serialize($values),
			'post_status'   => 'publish',
			'post_type' => 'vat'
		);

		$new_post_id = wp_insert_post($post, 10, 1);

		do_action('wp_insert_post', 'wp_insert_post', 10, 1);
		return	update_post_meta($new_post_id, '', '');
	}


	/**
	 * This function is responsible for add shortcode 
	 */
	public function add_vat_form_shortcode()
	{
        if (!is_admin()) {
            add_shortcode('vat_form', [$this, 'vat_form']);
        }
	}


	/**
	 * This function is responsible for process and display shortcode
	 */
	public function vat_form($atts, $content = null, $tag)
	{ 
		if(is_single() || is_home() || is_singular())
           require plugin_dir_path(__FILE__) . 'partials/plugin-calcVat-display.php';
       
	}
}
