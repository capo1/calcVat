<?php

class calcVat_Post_Type
{
	private  $post_type = 'vat';


	public function __construct($postType)
	{
		$this->post_type = $postType;
		add_action('init', [$this, 'register_CPT_for_Vat']);
	}

	/**
	 * Add custom post type
	 * 
	 * 
	 */
	public function register_CPT_for_Vat()
	{
		$labels = array(
			'name' => _x('CalcVat', 'post type general name'),
			'singular_name' => _x('CalcVat', 'post type singular name'),
			'add_new' => _x('Dodaj element', 'add'),
			'add_new_item' => _x('Dodaj', 'add'),
			'edit_item' => _x('Edytuj', 'edit'),
			'new_item' => _x('Nowy', 'new'),
			'all_items' => _x('Wszystkie', 'all'),
			'view_item' => _x('Zobacz', 'see'),
			'search_items' => _x('Szukaj', 'search'),
			'not_found' => _x('No found', ''),
			'not_found_in_trash' => _x('No found in the Trash', ''),
			'parent_item_colon' => ',',
			'menu_name' => __('CalcVat', 'calcVat')
		);

		$args = array(
			'capability_type' => 'post',
			'capabilities' => [
				'create_posts' => 'do_not_allow'
			],
			'map_meta_cap' => true,
			'labels' => $labels,
			'description' => '',
			'public' => true,
			'menu_position' => 6,
			'supports' => array('title', 'thumbnail'),
			'has_archive' => false,
			'show_in_nav_menus' => false,
			'with_front' => false
		);

		register_post_type($this->post_type, $args);
	}
}
