<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the setting admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/admin/partials
 */
?>

<?php
	global $wpdb;

	$setting_table = $wpdb->prefix . 'wc_csv_importer_header_setting';
	$setting = $wpdb->get_row( "SELECT * FROM $setting_table WHERE id = 1" );
	$column_header_field_in_order = str_replace('"', "", $setting->column_header_field_in_order);
?>

<div class="wrap">
	<form method="post">
		Column header line number in CSV: <input type="text" name="column_header_line_number" value="<?php echo $setting->column_header_line_number; ?>"><br>
		Column header order in CSV: <input type="text" id="column_header_field_in_order" name="column_header_field_in_order" value="<?php echo $column_header_field_in_order; ?>" style="width: 800px;"><br>
		<input type="submit" value="Save" name="save">
	</form>
</div>
