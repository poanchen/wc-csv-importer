<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           wc-csv-importer
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce CSV Importer
 * Plugin URI:        http://example.com/
 * Description:       User may simply add multiple products in WooCommerce with CSV file.
 * Version:           1.0.0
 * Author:            PoAn (Baron) Chen
 * Author URI:        https://github.com/poanchen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-csv-importer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-csv-importer-activator.php
 */
function activate_wc_csv_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-csv-importer-activator.php';
	wc_csv_importer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-csv-importer-deactivator.php
 */
function deactivate_wc_csv_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-csv-importer-deactivator.php';
	wc_csv_importer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_csv_importer' );
register_deactivation_hook( __FILE__, 'deactivate_wc_csv_importer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-csv-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_csv_importer() {
	$plugin = new wc_csv_importer();
	$plugin->run();
}
run_wc_csv_importer();

/**
 * Include the error message library so that we can give user better error message instead of error code
 */
require plugin_dir_path( __FILE__ ) . 'src/upload-error-message.php';

/**
 * Include the WooCommerce helper functions library so that we can use the WooCommerce API to create WooCommerce products
 */
require plugin_dir_path( __FILE__ ) . 'src/wc-helper.php';

session_start();
