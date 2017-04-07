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
	public $line_number_for_cols;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->cols_default = $this->get_cols_default();
		$this->line_number_for_cols = $this->get_column_header_line_number();
	}

	/**
	 *
	 * Get the default column header line number in database
	 *
	 * @return int $column_header_line_number
	 *
	*/
	function get_column_header_line_number() {
		global $wpdb;

		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		return intval( $wpdb->get_var( "SELECT column_header_line_number FROM $setting_table WHERE id = 1" ) );
	}

	/**
	 *
	 * Get the default column header field in order in database
	 *
	 * @return string $column_header_field_in_order
	 *
	*/
	function get_column_header_field_in_order() {
		global $wpdb;

		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		return $wpdb->get_var( "SELECT column_header_field_in_order FROM $setting_table WHERE id = 1" );
	}

	/**
	 *
	 * Get the default column header line number in database
	 *
	 * @param $name
	 * @return string $field_name
	 *
	*/
	function get_wc_field_name_by_name($name) {
		global $wpdb;

		$look_up_table = $wpdb->prefix . 'wc_csv_importer_header_look_up';

		return $wpdb->get_var( "SELECT value FROM $look_up_table WHERE name = '$name'" );
	}

	/**
	 *
	 * Get the default column header in database
	 *
	 * @return array $cols_default
	 *
	*/
	function get_cols_default() {
		$column_header_field_in_order_as_array = $this->get_cols_default_as_array();
		$cols_default = array();

		foreach ( $column_header_field_in_order_as_array as $each_field ) {
			$field_name = $this->get_wc_field_name_by_name($each_field);

			if ( ! $field_name ) {
				// to-do: maybe double check the status here to be on the safe side
				$this->insert_customized_column_header( $each_field );
			}

			array_push($cols_default, array(
										'name' => $each_field,
										'wc_field_name' => $this->get_wc_field_name_by_name($each_field)));
		}

		return $cols_default;
	}

	/**
	 *
	 * Get the default column header in database as array
	 *
	 * @return array $column_header_field_in_order_as_array
	 *
	*/
	function get_cols_default_as_array() {
		$column_header_field_in_order = str_replace('"', "", $this->get_column_header_field_in_order());
		
		return split(",", $column_header_field_in_order);
	}

	/**
	 *
	 * Get the default column header in database as array
	 *
	 * @param $name
	 * @return array $column_header_field_in_order_as_array
	 *
	*/
	function is_the_field_name_customized($name) {
		global $wpdb;

		$look_up_table = $wpdb->prefix . 'wc_csv_importer_header_look_up';

		return intval( $wpdb->get_var( "SELECT id FROM $look_up_table WHERE name = '$name'" ) ) > 7;
	}

	/**
	 *
	 * Insert the customized column header into look up table for future use
	 *
	 * @param $customized_column_header
	 * @return int $status_from_wpdb 1 if success, 0 otherwise
	 *
	*/
	public function insert_customized_column_header($customized_column_header) {
		global $wpdb;

		$look_up_table = $wpdb->prefix . 'wc_csv_importer_header_look_up';

		return $wpdb->insert(
			$look_up_table,
			array(
				'name' => $customized_column_header,
				'value' => $customized_column_header
			)
		);
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
