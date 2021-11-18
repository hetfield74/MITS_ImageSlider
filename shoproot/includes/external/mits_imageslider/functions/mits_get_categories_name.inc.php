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

function mits_get_categories_name($categories_id) {

  $group_check = (defined('GROUP_CHECK') && GROUP_CHECK == 'true') ? " AND c.group_permission_" . (int)$_SESSION['customers_status']['customers_status_id'] . " = 1 " : "";

  $categories_name_query = xtDBquery("SELECT cd.categories_name FROM " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c WHERE cd.categories_id = " . (int)$categories_id . " AND c.categories_id = cd.categories_id " . $group_check . " AND cd.language_id = " . (int)$_SESSION['languages_id']);
  $categories_name = xtc_db_fetch_array($categories_name_query, true);
  return $categories_name['categories_name'];

}