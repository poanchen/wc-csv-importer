<?php

/**
 * WooCommerce helper functions
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/src
 */

class wc_helper {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-admin/admin.php' );
	}

	/**
	 *
	 * WooCommerce add new products helper function
	 *
	 * @param $new_product_post
	 * @return array product ID
	 *
	*/
	public function add_new_product( $new_product_post ) {
		$c = new col();
		$unvisited_field = array();

		// make sure all the columns are swap based on what user wants in the preview
		for ( $i = 0; $i < count($c->cols_default); $i++ ) {
			if ( $c->cols_default[$i]['name'] != $_COOKIE[ 'wc_field_' . $i ] && ! in_array( $c->cols_default[$i]['name'], $unvisited_field ) && ! in_array( $_COOKIE[ 'wc_field_' . $i ], $unvisited_field ) ) {
				$old_value = $new_product_post[$c->get_wc_field_name_by_name($c->cols_default[$i]['name'])];
				$new_value = $new_product_post[$c->get_wc_field_name_by_name($_COOKIE[ 'wc_field_' . $i ])];
				$new_product_post[$c->get_wc_field_name_by_name($c->cols_default[$i]['name'])] = $new_value;
				$new_product_post[$c->get_wc_field_name_by_name($_COOKIE[ 'wc_field_' . $i ])] = $old_value;
				array_push( $unvisited_field, $c->cols_default[$i]['name'], $_COOKIE[ 'wc_field_' . $i ] );
			}
		}

		$product_attributes = $this->process_product_attribute( $new_product_post );

		$thumb_url = $new_product_post['thumb_url'];
		unset($new_product_post['thumb_url']);

		if ( null == ( $new_product_id = $this->get_product_id_by_sku( $new_product_post['_sku'] ) ) ) {
			// it is a new product, let's do a create
			$new_product_id = wp_insert_post( $new_product_post );
		} else {
			// product already existed, let's do an update
			$new_product_post['ID'] = $new_product_id;
			wp_update_post( $new_product_post );
		}

		update_post_meta( $new_product_id, '_product_attributes', $product_attributes);
		update_post_meta( $new_product_id, '_sku', $new_product_post['_sku']);

		$regular_price = $this->calculate_regular_price( $product_attributes );

		update_post_meta( $new_product_id, '_price', $regular_price );
		update_post_meta( $new_product_id, '_regular_price', $regular_price );
		update_post_meta( $new_product_id, '_visibility', 'visible' );
		update_post_meta( $new_product_id, '_stock_status', 'instock' );

		$thumb_id = $this->get_thumbnail_id( $thumb_url, $new_product_id, $new_product_post['post_title'] );
		set_post_thumbnail( $new_product_id, $thumb_id );

		return $new_product_id;
	}

	/**
	 *
	 * Retrieve the thumnnail from the internet and return the thumbnail ID
	 * Thanks to https://codex.wordpress.org/Function_Reference/media_handle_sideload
	 *
	 * @param $thumb_url
	 * @param $new_product_id
	 * @param $product_title
	 * @return int $thumb_id ID
	 *
	*/
	public function get_thumbnail_id( $thumb_url, $new_product_id, $product_title ) {
		$tmp = download_url( $thumb_url );
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		if ( null == ( $thumb_id = $this->file_exists( $file_array['name'] ) ) ) {
			if ( is_wp_error( $tmp ) ) {
				@unlink( $file_array['tmp_name'] );
				$file_array['tmp_name'] = '';
			}

			$thumb_id = media_handle_sideload( $file_array, $new_product_id, $product_title );

			if ( is_wp_error($thumb_id) ) {
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
			}
		} else {
			// make sure to delete the downloaded file in temp folder to save spaces
			@unlink( $file_array['tmp_name'] );
		}

		return $thumb_id;
	}

	/**
	 *
	 * Check if the file already exist in WordPress upload folders
	 *
	 * @param $filename
	 * @return int $post_id if it already exist, null otherwise
	 *
	*/
	function file_exists($filename) {
		global $wpdb;

		return intval( $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'" ) );
	}

	/**
	 *
	 * Retrieve the WooCommerce product ID by sku
	 * Thanks to https://www.skyverge.com/blog/find-product-sku-woocommerce/
	 *
	 * @param $sku
	 * @return int $product_id if it found it, null otherwise
	 *
	*/
	public function get_product_id_by_sku($sku) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	}

	/**
	 *
	 * WooCommerce add product attribute helper function
	 *
	 * @param $new_product_post
	 * @return array product_attributes
	 *
	*/
	public function process_product_attribute( $new_product_post ) {
		$product_attributes = array();

		// 尺寸
		if ( isset( $new_product_post['product_size'] ) ) {
			array_push( $product_attributes, array (
					'name' => '尺寸',
					'value' => $new_product_post['product_size'],
					'position' => 1,
					'is_visible' => 1,
					'is_variation' => 1,
					'is_taxonomy' => 0
				)
			);
			unset($new_product_post['product_size']);
		}

		// 材質
		if ( isset( $new_product_post['product_texture'] ) ) {
			array_push( $product_attributes, array (
					'name' => '材質',
					'value' => $new_product_post['product_texture'],
					'position' => 1,
					'is_visible' => 1,
					'is_variation' => 1,
					'is_taxonomy' => 0
				)
			);
			unset($new_product_post['product_texture']);
		}

		// 價格
		if ( isset( $new_product_post['product_regular_price'] ) ) {
			array_push( $product_attributes, array (
					'name' => '價格',
					'value' => $new_product_post['product_regular_price'],
					'position' => 1,
					'is_visible' => 1,
					'is_variation' => 1,
					'is_taxonomy' => 0
				)
			);
			unset($new_product_post['product_regular_price']);
		}

		$c = new col();

		foreach ( array_keys( $new_product_post ) as $key ) {
			if ( $c->is_the_field_name_customized( $key ) ) {
				array_push( $product_attributes, array (
						'name' => $key,
						'value' => $new_product_post[$key],
						'position' => 1,
						'is_visible' => 1,
						'is_variation' => 1,
						'is_taxonomy' => 0
					)
				);
				unset($new_product_post[$key]);
			}
		}

		return $product_attributes;
	}

	/**
	 *
	 * WooCommerce add product attribute helper function
	 *
	 * @param $product_attributes
	 * @return string grand total
	 *
	*/
	public function calculate_regular_price( $product_attributes ) {
		foreach ( $product_attributes as $attribute ) {
			if ( $attribute['name'] === '價格' ) {
				preg_match_all( '!\d+!', $attribute['value'], $individual_prices );
				break;
			}
		}

		$sum = 0;

		foreach ( $individual_prices[0] as $price ) {
			$sum += $price;
		}

		return (string) $sum;
	}
}

?>
