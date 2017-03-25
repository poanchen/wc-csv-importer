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

$listOfProductsInArray = array();
$fileData = fopen( $file, 'r' );
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
		'ID'    => $eachProduct[1],
		'post_title'    => $eachProduct[2],
		'post_content'    => $eachProduct[3],
		'post_texture'    => $eachProduct[4],
		'regular_price'    => $eachProduct[5],
		'post_excerpt'  => '',
		'post_type'     => 'product',
		'post_status'   => 'publish'
	);

	array_push( $listOfProductsInArray, $new_product_post );
}

fclose( $fileData );

?>

<div class="wrap">
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
				for ($i = 0; $i < count($listOfProductsInArray); $i++) {
			?>
					<th>N/A</th>
					<th><?php echo $listOfProductsInArray[$i]['ID']; ?></th>
					<th><?php echo $listOfProductsInArray[$i]['post_title']; ?></th>
					<th><?php echo $listOfProductsInArray[$i]['post_content']; ?></th>
					<th><?php echo $listOfProductsInArray[$i]['post_texture']; ?></th>
					<th><?php echo $listOfProductsInArray[$i]['regular_price']; ?></th>
		</tr>
			<?php
				}
			?>
	<table>
</div>