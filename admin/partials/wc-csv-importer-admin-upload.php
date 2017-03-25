<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the upload admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wc-csv-importer
 * @subpackage wc-csv-importer/admin/partials
 */
?>

<div class="wrap">
	<label>Select a CSV file to upload:</label>
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="fileToUpload" accept=".csv">
		<input type="submit" value="Upload file" name="submit">
	</form>
</div>
