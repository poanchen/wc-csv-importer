<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/includes
 * @author     PoAn (Baron) Chen <chen.baron@hotmail.com>
 */
class wc_csv_importer_Activator {

	/**
	 * Create necessary tables in the database.
	 *
	 * Added necessary tables for lookup table for column header and settings.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$look_up_table = $wpdb->prefix . 'wc_csv_importer_header_look_up';
		$charset_collate = $wpdb->get_charset_collate();

		$create_look_up_table_sql = "CREATE TABLE $look_up_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			value text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		$create_setting_table_sql = "CREATE TABLE $setting_table (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			column_header_line_number mediumint(9) NOT NULL,
			column_header_field_in_order text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $create_look_up_table_sql );
		dbDelta( $create_setting_table_sql );

		// insert some default data into the look up table
		// NOTE: whenever you are adding/removing any element in this array
		// please make sure to go to col.php to adjust for the size
		// in function named is_the_field_customized
		$wc_field_name = array(
			'圖型'    => 'thumb_url',
			'產品貨號'    => '_sku',
			'產品名稱' => 'post_title',
			'尺寸'    => 'product_size',
			'材質'    => 'product_texture',
			'價格'    => 'product_regular_price',
			'商品分類' => 'product_cat'
		);

		foreach ( $wc_field_name as $key => $value ) {
			$wpdb->insert(
				$look_up_table,
				array(
					'name'  => $key,
					'value' => $value
				)
			);
		}

		// insert some default data into the setting table
		$wpdb->insert(
			$setting_table,
			array(
				'column_header_line_number'  => 2,
				'column_header_field_in_order' => '"圖型,頁數編號,產品名稱,尺寸,材質,價格,產品貨號,商品分類"'
			)
		);
	}
}
