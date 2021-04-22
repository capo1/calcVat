<?php
// Get required Classes !important

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
if (!class_exists('WP_Posts_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');
}

// Process and display custom list table for custom post type

class Vat_List extends WP_List_Table
{
	/**
	 * The postType of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $calcVat    The ID of this plugin.
	 */
	private	$postType;

	/**
	 * The per_page on table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $calcVat    The per_page of this plugin.
	 */
	private	$per_page;

	/** Class constructor */
	public function __construct($postType)
	{
		$this->postType = 	$postType;
		$this->per_page  = 10;

		parent::__construct([
			'singular' => __('Vat', 'calcVat'), //singular name of the listed records
			'plural'   => __('Vat', 'calcVat'), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		]);
	}

	//Does not work 
	function remove_search_filter()
	{
		global $wc_list_table;
		if ($wc_list_table instanceof Vat_List) {
			remove_action('restrict_manage_posts', array($wc_list_table, 'restrict_manage_posts'));
		}
	}


	/**
	 * Add columns to grid view
	 */
	function get_columns()
	{
		$columns = array(
			'id' => 'ID',
			'product_name' =>  __('Product name', 'calcVat'),
			'ammount_netto' =>  __('Amount', 'calcVat'),
			'currency' => __('Currency', 'calcVat'),
			'used_vat' => __('Used vat', 'calcVat'),
			'calc' => __('Calculated', 'calcVat'),
			'date' => __('Date', 'calcVat'),
			'ip' => __('IP', 'calcVat')
		);
		return $columns;
	}

	
	/**
	 * Add delete link to product name
	 */
	function column_product_name($item)
	{
		$delete_nonce = wp_create_nonce('delete-post_' . $item['id']);

		$title = '<strong>' . $item['product_name'] . '</strong>';

		$actions = [
			'delete' => sprintf('<a href="post.php?&action=%s&post=%s&_wpnonce=%s">Delete</a>', 'delete', absint($item['id']), $delete_nonce)
		];

		return $title . $this->row_actions($actions);
	}
	
	/**
	 * Add default columns to list
	*/
	function column_default($item, $column_name)
	{

		switch ($column_name) {
			case 'id':
			case 'product_name':
			case 'ammount_netto':
			case 'currency':
			case 'used_vat':
			case 'calc':
			case 'date':
			case 'ip':
				return $item[$column_name] ?? '';
			default:
				return print_r($item, true); //Show the whole array for troubleshooting purposes
		}
	}


	/**
	 * Prepare admin view with data
	 */
	function prepare_items()
	{
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
		$orderby =  (!empty($_GET['orderby'])) ? $_GET['orderby'] :
		$order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
		$search = [];

		//if is search variable		
		if (isset($_GET['s']) && !empty($_GET['s'])) {
			$search = [
				's' => $_GET['s']
			];

			// count posts - its uggly but work
			$q = new WP_Query(array('post_type' => $this->postType, 'posts_per_page' => -1, 's' => $_GET['s']));
			$count = count($q->posts);
		} else {

			//its preatty - count posts with no filters eg. search
			$count = wp_count_posts($this->postType)->publish;
		}

		//main query
		$items = new WP_Query(
			array_merge(array(
				'post_type' => $this->postType,
				'posts_per_page' => $this->per_page,
				'paged' => $paged,
				'orderby' => $orderby,
				'order' => $order,
			), $search)
		);

		$new = [];

		foreach ($items->posts as $key => $item) :
			$new[$key] = maybe_unserialize($item->post_content);
			$new[$key]['id'] = $item->ID;
		endforeach;

		$columns = $this->get_columns();
		$hidden = array();

		//	$sortable = $this->get_sortable_columns();

		// Set table th
		$this->_column_headers = array($columns, $hidden);

		// Set the data
		$this->items = $new;

		// Set the pagination
		$this->set_pagination_args(array(
			'total_items' => $count,
			'per_page' => $this->per_page,
			'total_pages' => ceil($count / $this->per_page)
		));
	}


	/**
	 * No items found in table 
	 */
	function no_items()
	{
		_e('No vats found.');
	}

}
