<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 16.07.2020
 * Time: 17:37
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
    if (!function_exists('mits_imageslider_add_db_field_column_exists')) {
        function mits_imageslider_add_db_field_column_exists($table, $column)
        {
            $check_query = xtc_db_query('SHOW COLUMNS FROM ' . $table . " LIKE '" . xtc_db_input($column) . "'");
            return xtc_db_num_rows($check_query) > 0;
        }
    }

    if (defined('TABLE_PRODUCTS') && !mits_imageslider_add_db_field_column_exists(TABLE_PRODUCTS, 'imagesliders_group')) {
        xtc_db_query('ALTER TABLE ' . TABLE_PRODUCTS . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
    }
    $add_products_fields[] = 'imagesliders_group';

    if (defined('TABLE_CATEGORIES') && !mits_imageslider_add_db_field_column_exists(TABLE_CATEGORIES, 'imagesliders_group')) {
        xtc_db_query('ALTER TABLE ' . TABLE_CATEGORIES . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
    }
    $add_categories_fields[] = 'imagesliders_group';

    if (defined('TABLE_CONTENT_MANAGER') && !mits_imageslider_add_db_field_column_exists(TABLE_CONTENT_MANAGER, 'imagesliders_group')) {
        xtc_db_query('ALTER TABLE ' . TABLE_CONTENT_MANAGER . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
    }
    $add_content_fields[] = 'imagesliders_group';
}
