<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/admin
 * @author     PoAn (Baron) Chen <chen.baron@hotmail.com>
 */
class wc_csv_importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add menus
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}

	/**
	 * Add menu items.
	 * For more information, please go to https://developer.wordpress.org/reference/functions/add_submenu_page/
	 */
	public function admin_menu() {

		global $menu;

		if ( ! is_admin() || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		add_menu_page( $this->plugin_name, $this->plugin_name, 'manage_woocommerce', 'wc_csv_importer_upload', array($this, 'upload'), 'dashicons-randomize', '56' );

		add_submenu_page( 'wc_csv_importer_upload', 'Setting', 'Setting', 'manage_woocommerce', 'wc_csv_importer_setting', array($this, 'setting') );

	}

	public function upload () {

		// check if there were any problem with the uploaded file
		if ( isset ( $_FILES['fileToUpload'] ) && $_FILES['fileToUpload']['error'] != 0 ) {
			$e = new UploadErrorMessages();
			$error_message = $e->convertErrorToMessage( $_FILES['fileToUpload']['error'] );

			echo '<div class="wrap">';
			echo '	<div class="error">';
			echo '		<p>';
			echo 			$error_message . '.';
			echo '		</p>';
			echo '	</div>';
			echo '</div>';
			return;
		}

		if ( isset ( $_FILES['fileToUpload'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST["submit"] ) ) {
			// user just uploaded their CSV file
			$_SESSION["file"] = $_FILES['fileToUpload']['tmp_name'];
			include dirname( __FILE__ ) . '/partials/wc-csv-importer-admin-upload-preview.php';
		} else if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST["create"] ) ) {
			// user just clicked the create button
			$wc_helper = new wc_helper();
			for ($i = 0; $i < count($_SESSION["listOfProducts"]); $i++) {
				var_dump($wc_helper->add_new_product($_SESSION["listOfProducts"][$i]));
				// echo '<pre>';
				// var_dump(get_post_custom(147));
				// echo '</pre>';
			}
		} else {
			// simply show the import file
			include dirname( __FILE__ ) . '/partials/wc-csv-importer-admin-upload.php';
		}

	}

	public function setting () {

		include dirname( __FILE__ ) . '/partials/wc-csv-importer-admin-setting.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wc_csv_importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wc_csv_importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-csv-importer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wc_csv_importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wc_csv_importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-csv-importer-admin.js', array( 'jquery' ), $this->version, false );

	}

}
