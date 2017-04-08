v1.0.11 (2017-04-07)
======

* Removed the Session variable where we store the number of columns as it is now deprecated.

v1.0.10 (2017-04-06)
======

* Instead of determining the number of columns in the preview table from the CSV file. Number of columns will be determined from the setting.

v1.0.9 (2017-04-02)
======

* Updated the README.md.
* Added screenshots of the plugin.

v1.0.8 (2017-04-01)
======

* Added the ability to allow user to add custom field in the setting page. (Those custom field will go to Addition Information in WooCommerce).
* Finally, the setting page is hooked up with the DB. Those settings will be saved. However, those setting will be take effect just yet.
* Necessary DB table will be created when user activate the plugin. DB table will be removed when user deactivate the plugin.

v1.0.7 (2017-03-30)
======

* Fixed the issue where we keep on uploading the duplicate image to the server. Only image with new name will be uploaded to the server.

v1.0.6 (2017-03-29)
======

* Everytime when user tries to create a product in the plugin. We first check if the product already exist by making sure the field _sku is unique. If it is, then we create a new one. Otherwise, we update the existing product.

v1.0.5 (2017-03-28)
======

* Added the ability when user swap one of their column header field, the other will alternate too. So that there won't be any duplicate column header in the table.

v1.0.4 (2017-03-27)
======

* Added the ability to swap the column order in preview page.
* Currently, all the column are grabbed from col.php. In the future release, they will be reside in DB.
* Now, it will not work when user swap their column header in preview page. For now, only the UI works.

v1.0.3 (2017-03-26)
======

* Added status message when user successfully/failed to create their products.
* Name changes from Upload to Load as we are not actually uploading the files.
* Added the ability to set the thumbnail for the product if they provide an URL.
* Fixed the pricing not show up issue.

v1.0.2 (2017-03-25)
======

* Added a place for user to upload their CSV file.
* Added appropriate error messages when user failed to upload their CSV file.
* Added a preview functionality where user will be able to check out their CSV file before they create their products in WooCommerce.
* Added the ability to actually create products in WooCommerce which include fields like _sku, post_title, product_size, product_texture, product_regular_price (product_size and product_texture are special attributes in WooCommerce product).

v1.0.1 (2017-03-23)
======

* Added wc-csv-importer's submenu to Admin panel.

v1.0.0 (2017-03-10)
======

* Use WordPress Plugin Boilerplate from [DevinVinson/WordPress-Plugin-Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) to start with.