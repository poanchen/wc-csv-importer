<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/includes
 * @author     PoAn (Baron) Chen <chen.baron@hotmail.com>
 */
class wc_csv_importer_Deactivator {

	/**
	 * Delete tables in the database.
	 *
	 * Delete look up and setting tables.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;

		$look_up_table = $wpdb->prefix . 'wc_csv_importer_header_look_up';
		$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';

		$drop_look_up_table_sql = "DROP TABLE IF EXISTS $look_up_table";
		$drop_setting_table_sql = "DROP TABLE IF EXISTS $setting_table";

		$wpdb->query( $drop_look_up_table_sql );
		$wpdb->query( $drop_setting_table_sql );
	}
}
