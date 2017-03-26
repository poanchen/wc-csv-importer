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
		$product_attributes = $this->process_product_attribute( $new_product_post );

		$thumb_url = $new_product_post['thumb_url'];
		unset($new_product_post['thumb_url']);

		if ( 0 != ( $new_product_id = wp_insert_post( $new_product_post ) ) ) {
			update_post_meta( $new_product_id, '_product_attributes', $product_attributes);
			update_post_meta( $new_product_id, '_sku', $new_product_post['_sku']);
			update_post_meta( $new_product_id, '_price', $this->calculate_regular_price( $product_attributes ));
			update_post_meta( $new_product_id, '_regular_price', $this->calculate_regular_price( $product_attributes ));
			update_post_meta( $new_product_id, '_visibility', 'visible' );
			update_post_meta( $new_product_id, '_stock_status', 'instock' );

			$thumb_id = $this->get_thumbnail_id( $thumb_url, $new_product_id, $new_product_post['post_title'] );
			set_post_thumbnail( $new_product_id, $thumb_id );
		}

		return $new_product_id;
	}

	/**
	 *
	 * Retrieve the thumnnail from the internet and return the thumbnail ID
	 * Thanks to http://jafty.com/blog/programatically-create-woocommerce-products-with-variations/
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

		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}

		$thumb_id = media_handle_sideload( $file_array, $new_product_id, $product_title );

		if ( is_wp_error($thumb_id) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		return $thumb_id;
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
