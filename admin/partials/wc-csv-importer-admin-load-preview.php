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
$_SESSION["numberOfCols"] = 0;
$listOfProducts = array();
$fileData = fopen( $_SESSION["file"], 'r' );

for ( $i = 0; !feof( $fileData ); $i++ ) { 
	$eachProduct = fgetcsv( $fileData );

	if ( $i < $c->line_number_for_cols ) {
		continue;
	}

	if ( count($eachProduct) > $_SESSION["numberOfCols"] ) {
		$_SESSION["numberOfCols"] = count($eachProduct);
	}

	$new_product_post = array();

	for ( $j = 0; $j < count($eachProduct); $j++ ) {
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
				for ( $i = 0; $i < $_SESSION["numberOfCols"]; $i++ ) {
			?>
					<th>
						<select id="<?php echo 'wc_field_' . $i; ?>">
							<option value="" selected></option>
			<?php
							for ( $j = 0; $j < count( $c->cols_default ); $j++ ) {
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
			for ( $i = 0; $i < count( $listOfProducts ); $i++ ) {
		?>
				<tr>
				<?php
					for ( $j = 0; $j < $_SESSION["numberOfCols"]; $j++ ) {
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

<script type="text/javascript">
	// thanks to https://www.w3schools.com/js/js_cookies.asp
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();

		d.setTime( d.getTime() + ( exdays*24*60*60*1000 ) );

		var expires = "expires="+ d.toUTCString();

		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	// thanks to https://www.w3schools.com/js/js_cookies.asp
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent( document.cookie );
		var ca = decodedCookie.split( ';' );

		for( var i = 0; i < ca.length; i++ ) {
			var c = ca[i];

			while ( c.charAt( 0 ) == ' ' ) {
				c = c.substring( 1 );
			}
			if ( c.indexOf( name ) == 0 ) {
				return c.substring( name.length, c.length );
			}
		}

		return "";
	}

	function swapTheOptionsIfNeeded(currentOptionId, currentOptionName) {
		for ( var i = 0; i < <?php echo $_SESSION["numberOfCols"]; ?>; i++ ) {
			if ( i == currentOptionId ) {
				continue;
			}
			if ( currentOptionName == getOptionNameByIndex( document.getElementById( "wc_field_" + i ).selectedIndex) ) {
				document.getElementById( "wc_field_" + i ).value = getCookie( 'previousSelection' );
			}
		}
	}

	function getId(fieldId) {
		return parseInt( fieldId.replace( /[^\d.]/g, '' ) );
	}

	function wcFieldHasBeenClick(event) {
		var e = event.target;

		swapTheOptionsIfNeeded( getId( e.id ), getOptionNameByIndex( e.selectedIndex ) );

		setCookie( e.id, getOptionNameByIndex( e.selectedIndex ) );
		setCookie( 'previousSelection', getOptionNameByIndex( e.selectedIndex ) );
	}

	function getOptionNameByIndex(index) {
		return document.getElementsByTagName("option")[index].value;
	}

	for ( var i = 0; i < <?php echo $_SESSION["numberOfCols"]; ?>; i++ ) {
		setCookie( "wc_field_" + i, getOptionNameByIndex( document.getElementById( "wc_field_" + i ).selectedIndex) );
		document.getElementById( "wc_field_" + i ).addEventListener( "click", wcFieldHasBeenClick );
	}
</script>
