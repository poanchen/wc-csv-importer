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

$c = new col();
$listOfProducts = array();
$fileData = fopen( $_SESSION["file"], 'r' );

for ( $i = 0; !feof( $fileData ); $i++ ) { 
	$eachProduct = fgetcsv( $fileData );

	if ( $i < $c->line_number_for_cols ) {
		continue;
	}

	$new_product_post = array();

	for ( $j = 0; $j < count($c->cols_default); $j++ ) {
		$new_product_post[$c->cols_default[$j]['wc_field_name']] = $eachProduct[$j];
	}

	$new_product_post['post_type'] = 'product';
	$new_product_post['post_status'] = 'publish';

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
				for ( $i = 0; $i < count($c->cols_default); $i++ ) {
			?>
					<th>
						<select id="<?php echo 'wc_field_' . $i; ?>">
			<?php
							for ( $j = 0; $j < count($c->cols_default); $j++ ) {
								$value = $c->cols_default[$j]['name'];
								$selected = $i == $j ? 'selected' : '';
			?>
								<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
			<?php
							}
			?>
						</select>
					</th>
			<?php
				}
			?>
		</tr>

		<?php
			for ( $i = 0; $i < count($listOfProducts); $i++ ) {
		?>
				<tr>
				<?php
					for ( $j = 0; $j < count($c->cols_default); $j++ ) {
						$wc_field_name = $c->cols_default[$j]['wc_field_name'];
				?>
						<th><?php echo $listOfProducts[$i][$wc_field_name]; ?></th>
				<?php
					}
				?>
				</tr>
		<?php
			}
		?>
	<table>
</div>

<script>
	// thanks to https://www.w3schools.com/js/js_cookies.asp
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function wcFieldHasBeenClick(event) {
		var e = event.target;
		setCookie( e.id, getOptionNameByIndex(e.selectedIndex) );
	}

	function getOptionNameByIndex(index) {
		return document.getElementsByTagName("option")[index].value;
	}

	for ( var i = 0; i < 6; i++ ) {
		var x = document.getElementById( "wc_field_" + i ).selectedIndex;

		setCookie( "wc_field_" + i, getOptionNameByIndex(x) );
		document.getElementById( "wc_field_" + i ).addEventListener( "click", wcFieldHasBeenClick );
	}
</script>
