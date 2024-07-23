<?php
/**
 * --------------------------------------------------------------
 * File: general.php
 * Date: 16.12.2020
 * Time: 08:27
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

function xtc_get_imageslider_image($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_image'];
  }
}

function xtc_get_imageslider_tablet_image($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_tablet_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_tablet_image'];
  }
}

function xtc_get_imageslider_mobile_image($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_mobile_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_mobile_image'];
  }
}

function xtc_get_imageslider_url($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_url FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_url'];
  }
}

function xtc_get_imageslider_url_target($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_url_target FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_url_target'];
  }
}

function xtc_get_imageslider_url_typ($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_url_typ FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_url_typ'];
  }
}

function xtc_get_imageslider_linktitle($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_linktitle FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_linktitle'];
  }
}

function xtc_get_imageslider_title($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_title FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_title'];
  }
}

function xtc_get_imageslider_alt($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_alt FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_alt'];
  }
}

function xtc_get_imageslider_description($imageslider_id, $language_id = '') {
  $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
  $imageslider_query = xtc_db_query("SELECT imagesliders_description FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
  if (xtc_db_num_rows($imageslider_query) > 0) {
    $imageslider = xtc_db_fetch_array($imageslider_query);
    return $imageslider['imagesliders_description'];
  }
}

if (!function_exists('xtc_get_default_table_data')) {
  function xtc_get_default_table_data($table) {
    $default_array = array();
    $default_query = xtc_db_query("SHOW COLUMNS FROM " . $table . "");
    while ($default = xtc_db_fetch_array($default_query)) {
      $value = '';
      if ($default['Default'] != '') {
        $value = $default['Default'];
      } elseif (strtolower($default['Null']) == 'no'
        && (strpos(strtolower($default['Type']), 'int') !== false
          || strpos(strtolower($default['Type']), 'decimal') !== false)
      ) {
        $value = 0;
      }
      $default_array[$default['Field']] = $value;
    }
    return $default_array;
  }
}
