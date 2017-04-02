<?php

/**
 * columns helper functions
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/src
 */

class col {

	public $cols_default;
	public $wc_field_name;
	public $line_number_for_cols;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->wc_field_name = array(
			'圖型'    => 'thumb_url',
			'編號'    => '_sku',
			'產品名稱' => 'post_title',
			'尺寸'    => 'product_size',
			'材質'    => 'product_texture',
			'價格'    => 'product_regular_price'
		);

		$this->cols_default = array(
			0 => array(
				'name' => '圖型',
				'wc_field_name' => $this->wc_field_name['圖型']),
			1 => array(
				'name' => '編號',
				'wc_field_name' => $this->wc_field_name['編號']),
			2 => array(
				'name' => '產品名稱',
				'wc_field_name' => $this->wc_field_name['產品名稱']),
			3 => array(
				'name' => '尺寸',
				'wc_field_name' => $this->wc_field_name['尺寸']),
			4 => array(
				'name' => '材質',
				'wc_field_name' => $this->wc_field_name['材質']),
			5 => array(
				'name' => '價格',
				'wc_field_name' => $this->wc_field_name['價格'])
		);

		$this->line_number_for_cols = 2;
	}

	/**
	 *
	 * Save the default column header line number into database
	 *
	 * @param $column_header_line_number
	 * @return int $status_from_wpdb 1 if success, 0 otherwise
	 *
	*/
	public function save_column_header_line_number($column_header_line_number) {
		global $wpdb;

		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		return $wpdb->update(
			$setting_table,
			array( 'column_header_line_number' => $column_header_line_number ),
			array( 'id' => 1 )
		);
	}

	/**
	 *
	 * Save the default column header field order into database
	 *
	 * @param $column_header_field_in_order
	 * @return int $status_from_wpdb 1 if success, 0 otherwise
	 *
	*/
	public function save_column_header_field_in_order($column_header_field_in_order) {
		global $wpdb;

		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		return $wpdb->update(
			$setting_table,
			array( 'column_header_field_in_order' => '"' . $column_header_field_in_order . '"' ),
			array( 'id' => 1 )
		);
	}
}

?>
