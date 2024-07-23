<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 20.07.2020
 * Time: 11:47
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
  if (strstr($PHP_SELF, FILENAME_CONTENT_MANAGER )) {
    if (isset($_GET['action']) && !empty($_GET['action']) && isset($_GET['coID']) && !empty($_GET['coID'])) {
      if (isset($_GET['id']) && $_GET['id'] == 'update') {
        if (isset($_POST['imagesliders_group'])) {
          $imagesliders_group = xtc_db_prepare_input($_POST['imagesliders_group']);
          xtc_db_query("UPDATE " . TABLE_CONTENT_MANAGER . " 
                           SET imagesliders_group = '" . $imagesliders_group . "' 
                         WHERE content_group = " . (int)$_GET['coID']);
        }
      }
    }
  }
}