<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the upload preview admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/admin/partials
 */

$listOfProducts = array();
$fileData = fopen( $_SESSION["file"], 'r' );
$i = 0;
$lineNumberForHeader = 1;
$colName;

for ( $i = 0; !feof( $fileData ); $i++ ) { 
	$eachProduct = fgetcsv( $fileData );

	if ( $i == $lineNumberForHeader ) {
		$colName = $eachProduct;
		continue;
	}

	if ( $i < $lineNumberForHeader ) {
		continue;
	}

	$new_product_post = array(
		'_sku'    => $eachProduct[1],
		'post_title'    => $eachProduct[2],
		'product_size'    => $eachProduct[3],
		'product_texture'    => $eachProduct[4],
		'product_regular_price'    => $eachProduct[5],
		'post_excerpt'  => '',
		'post_type'     => 'product',
		'post_status'   => 'publish'
	);

	array_push( $listOfProducts, $new_product_post );
}

fclose( $fileData );

$_SESSION["listOfProducts"] = $listOfProducts;

?>

<div class="wrap">
	<form method="post">
		<input type="submit" value="Create" name="create">
	</form>
</div>

<div class="wrap import">
	<table>
		<tr>
			<?php
				for ($i = 0; $i < count($colName); $i++) {
			?>
					<th><?php echo $colName[$i]; ?></th>
			<?php
				}
			?>
		</tr>
		<tr>
			<?php
				for ($i = 0; $i < count($listOfProducts); $i++) {
			?>
					<th>N/A</th>
					<th><?php echo $listOfProducts[$i]['_sku']; ?></th>
					<th><?php echo $listOfProducts[$i]['post_title']; ?></th>
					<th><?php echo $listOfProducts[$i]['product_size']; ?></th>
					<th><?php echo $listOfProducts[$i]['product_texture']; ?></th>
					<th><?php echo $listOfProducts[$i]['product_regular_price']; ?></th>
		</tr>
			<?php
				}
			?>
	<table>
</div>
