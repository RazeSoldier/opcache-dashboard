<?php
class OPcache_List_Table extends WP_List_Table {
	public $data = array();

	function __construct($data) {
		global $status, $page;
		$this->data = $data;

		parent::__construct(array(
			'singular'	=> 'config',
			'plural'	=> 'configs',
			'ajax'		=> false
		));
	}

	function extra_tablenav($which) {
		switch($which) {
			case 'top':
				//echo 'Extra Table Navigation(Top)';
				break;
			case 'bottom':
				//echo 'Extra Table Navigation(Bottom)';
				break;
		}
	}

	function get_columns() {
		$columns = array(
			'name'	=> __('Config Name', 'opcache'),
			'value'	=> _x('Value', 'Value of Config', 'opcache')
		);
		return $columns;
	}

	function column_name($item) {
		$actions = NULL;
		switch($item['name']) {
			case 'directives.opcache.enable':
				$actions = $item['value'] ? NULL : array('notice' => 'You should enabled opcache');
				break;
			case 'directives.opcache.validate_timestamps':
				$actions = $item['value'] ? array('notice' => 'If you are in a production environment you should disabled it') : NULL;
				break;
		}
		return sprintf('<strong><span class="row-title">%1$s</span></strong>%2$s', $item['name'], $this->row_actions($actions));
	}

	function column_value($item) {
		switch($item['name']) {
			case 'directives.opcache.memory_consumption':
				return OPcache_dashboard::size($item['value']);
			case 'directives.opcache.max_wasted_percentage':
				return OPcache_dashboard::number_format($item['value']) . '%';
			default:
				return $item['value'];
		}
	}

	function column_default($item, $column_name) {
		switch($column_name) {
			default:
				return $item[$column_name];
		}
	}

	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$current_page = $this->get_pagenum();

		$total_items = count($this->data);

		$this->items = $this->data;

		$this->set_pagination_args(array(
			'total_items'	=> $total_items,
			'per_page'	=> $total_items,
			'total_pages'	=> 1
		));
	}
}

?>
