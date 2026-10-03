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

require_once('includes/application_top.php');

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
  // include needed function
  require_once(DIR_FS_INC . 'xtc_wysiwyg.inc.php');

  $action = ($_GET['action'] ?? '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);

  // languages
  $languages = xtc_get_languages();

  //display per page
  $cfg_max_display_results_key = defined('MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS') ? 'MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS' : 20;
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/general.php');
  require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php');

  if (!function_exists('mits_imageslider_admin_text')) {
    function mits_imageslider_admin_text($constant, $fallback) {
      return defined($constant) ? constant($constant) : $fallback;
    }
  }

  if (!function_exists('mits_imageslider_admin_charset')) {
    function mits_imageslider_admin_charset() {
      $charset = !empty($_SESSION['language_charset']) ? $_SESSION['language_charset'] : (defined('CHARSET') ? CHARSET : 'UTF-8');
      $charset_lc = strtolower((string)$charset);
      if ($charset_lc == 'utf8' || $charset_lc == 'utf-8') {
        return 'UTF-8';
      }
      return (string)$charset;
    }
  }

  if (!function_exists('mits_imageslider_admin_h')) {
    function mits_imageslider_admin_h($value) {
      return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, mits_imageslider_admin_charset());
    }
  }

  if (!function_exists('mits_imageslider_admin_date')) {
    function mits_imageslider_admin_date($value) {
      $value = (string)$value;
      if ($value == '' || $value == '0000-00-00 00:00:00' || $value == '0000-00-00') {
        return '-';
      }
      return xtc_date_short($value);
    }
  }

  switch ($action) {
    case 'save_sorting':
      $is_ajax_sorting = (isset($_POST['ajax']) && $_POST['ajax'] == '1') || (isset($_GET['ajax']) && $_GET['ajax'] == '1');
      $sorting_saved = false;
      $sorting_counter = array();
      $order_ids = array();
      $order_id_lookup = array();

      if (isset($_POST['imagesliders_order']) && is_array($_POST['imagesliders_order'])) {
        foreach ($_POST['imagesliders_order'] as $imagesliders_id) {
          $imagesliders_id = (int)$imagesliders_id;
          if ($imagesliders_id <= 0 || isset($order_id_lookup[$imagesliders_id])) {
            continue;
          }
          $order_ids[] = $imagesliders_id;
          $order_id_lookup[$imagesliders_id] = true;
        }
      }

      if (count($order_ids) > 0) {
        // Die Gruppe und die bisherige Position werden bewusst aus der Datenbank gelesen
        // und nicht aus POST-Daten uebernommen. Das ist wichtig fuer die paginierte
        // Listenansicht: Beim Sortieren auf Seite 2 darf nicht wieder bei Position 1
        // begonnen werden, sondern nur der sichtbare Ausschnitt der Gruppe wird neu
        // durchnummeriert.
        $row_data = array();
        $group_min = array();
        $order_ids_sql = implode(',', array_map('intval', $order_ids));
        $sorting_rows_query = xtc_db_query("SELECT imagesliders_id, imagesliders_group, sorting FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_id IN (" . $order_ids_sql . ")");
        while ($sorting_row = xtc_db_fetch_array($sorting_rows_query)) {
          $row_id = (int)$sorting_row['imagesliders_id'];
          $row_group = ($sorting_row['imagesliders_group'] != '') ? $sorting_row['imagesliders_group'] : 'mits_imageslider';
          $row_sorting = (int)$sorting_row['sorting'];

          $row_data[$row_id] = array(
            'group' => $row_group,
            'sorting' => $row_sorting
          );

          if (!isset($group_min[$row_group])
              || $row_sorting < $group_min[$row_group]['sorting']
              || ($row_sorting == $group_min[$row_group]['sorting'] && $row_id < $group_min[$row_group]['id'])
          ) {
            $group_min[$row_group] = array(
              'id' => $row_id,
              'sorting' => $row_sorting
            );
          }
        }

        foreach ($group_min as $group_name => $min_data) {
          $offset_query = xtc_db_query("SELECT COUNT(*) AS total
                                           FROM " . TABLE_MITS_IMAGESLIDER . "
                                          WHERE imagesliders_group = '" . xtc_db_input($group_name) . "'
                                            AND (sorting < " . (int)$min_data['sorting'] . "
                                             OR (sorting = " . (int)$min_data['sorting'] . " AND imagesliders_id < " . (int)$min_data['id'] . "))");
          $offset_data = xtc_db_fetch_array($offset_query);
          $sorting_counter[$group_name] = (int)$offset_data['total'];
        }

        foreach ($order_ids as $imagesliders_id) {
          if (!isset($row_data[$imagesliders_id])) {
            continue;
          }

          $imagesliders_group = $row_data[$imagesliders_id]['group'];
          if (!isset($sorting_counter[$imagesliders_group])) {
            $sorting_counter[$imagesliders_group] = 0;
          }
          $sorting_counter[$imagesliders_group]++;

          xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER . "
                           SET sorting = " . (int)$sorting_counter[$imagesliders_group] . "
                         WHERE imagesliders_id = " . (int)$imagesliders_id . "
                           AND imagesliders_group = '" . xtc_db_input($imagesliders_group) . "'");
          $sorting_saved = true;
        }
      }

      if ($is_ajax_sorting) {
        header('Content-Type: application/json; charset=' . mits_imageslider_admin_charset());
        echo json_encode(array(
          'success' => $sorting_saved,
          'message' => $sorting_saved ? mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_SAVED', 'Sortierung gespeichert.') : mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_NO_ITEMS', 'Es wurden keine sortierbaren Eintraege uebergeben.')
        ));
        exit;
      }

      $redirect_params = '';
      if (isset($_GET['page'])) {
        $redirect_params .= 'page=' . (int)$_GET['page'];
      }
      if (isset($_GET['groupfilter']) && $_GET['groupfilter'] != '') {
        $redirect_params .= ($redirect_params != '' ? '&' : '') . 'groupfilter=' . urlencode($_GET['groupfilter']);
      }
      xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER, $redirect_params));
      break;

    case 'insert':
    case 'save':
      $imagesliders_id = (isset($_GET['iID'])) ? (int)$_GET['iID'] : null;
      $imagesliders_name = (isset($_POST['imagesliders_name']) ? xtc_db_prepare_input($_POST['imagesliders_name']) : '');
      $imagesliders_status = (isset($_POST['imagesliders_status']) ? xtc_db_prepare_input($_POST['imagesliders_status']) : '');
      $imagesliders_sorting = (isset($_POST['imagesliders_sorting']) ? xtc_db_prepare_input($_POST['imagesliders_sorting']) : '');
      $new_imagesliders_group = (isset($_POST['new_imagesliders_group']) ? xtc_db_prepare_input(strtolower($_POST['new_imagesliders_group'])) : '');
      $imagesliders_group = ((empty($new_imagesliders_group)) ? (isset($_POST['imagesliders_group']) ? xtc_db_prepare_input($_POST['imagesliders_group']) : 'mits_imageslider') : $new_imagesliders_group);
      $imagesliders_recurring = (isset($_POST['imagesliders_recurring']) && $_POST['imagesliders_recurring'] == '1') ? 1 : 0;

      $date_scheduled = 'null';
      $expires_date = 'null';
      $recurring_start_md = 'null';
      $recurring_end_md = 'null';

      if (isset($_POST['date_scheduled']) && $_POST['date_scheduled'] != '' && $_POST['date_scheduled'] != '0000-00-00 00:00:00') {
        $date_scheduled_timestamp = strtotime($_POST['date_scheduled']);
        if ($date_scheduled_timestamp !== false) {
          $date_scheduled = date('Y-m-d 00:00:00', $date_scheduled_timestamp);
          $recurring_start_md = date('m-d', $date_scheduled_timestamp);
        }
      }
      if (isset($_POST['expires_date']) && $_POST['expires_date'] != '' && $_POST['expires_date'] != '0000-00-00 00:00:00') {
        $expires_date_timestamp = strtotime($_POST['expires_date']);
        if ($expires_date_timestamp !== false) {
          $expires_date = date('Y-m-d 23:59:59', $expires_date_timestamp);
          $recurring_end_md = date('m-d', $expires_date_timestamp);
        }
      }
      if ($imagesliders_recurring != 1) {
        $recurring_start_md = 'null';
        $recurring_end_md = 'null';
      }

      $imageslider_error = false;
      if (empty($imagesliders_name)) {
        $messageStack->add(ERROR_IMAGESLIDER_NAME_REQUIRED, 'error');
        $imageslider_error = true;
      }
      if (empty($imagesliders_group)) {
        $messageStack->add(ERROR_IMAGESLIDER_GROUP_REQUIRED, 'error');
        $imageslider_error = true;
      }

      $sql_data_array = array(
        'imagesliders_name'    => $imagesliders_name,
        'status'               => $imagesliders_status,
        'sorting'              => $imagesliders_sorting,
        'imagesliders_group'   => $imagesliders_group,
        'date_scheduled'       => $date_scheduled,
        'expires_date'         => $expires_date,
        'recurring'            => $imagesliders_recurring,
        'recurring_start_md'   => $recurring_start_md,
        'recurring_end_md'     => $recurring_end_md
      );
      if ($imageslider_error !== true) {
        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          xtc_db_perform(TABLE_MITS_IMAGESLIDER, $sql_data_array);
          $imagesliders_id = xtc_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
          xtc_db_perform(TABLE_MITS_IMAGESLIDER, $sql_data_array, 'update', "imagesliders_id = " . (int)$imagesliders_id);
        }
      }

      $languages = xtc_get_languages();
      $accepted_imagesliders_image_files_extensions = array("jpg", "jpeg", "jpe", "gif", "png", "webp", "avif", "bmp", "tiff", "tif", "bmp");
      $accepted_imagesliders_image_files_mime_types = array("image/jpeg", "image/gif", "image/png", "image/webp", "image/avif", "image/bmp");
      if (defined('MODULE_MITS_IMAGESLIDER_ALLOW_SVG') && MODULE_MITS_IMAGESLIDER_ALLOW_SVG == 'true') {
        $accepted_imagesliders_image_files_extensions[] = "svg";
        $accepted_imagesliders_image_files_mime_types[] = "image/svg+xml";
      }
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $old_imagepfad = xtc_get_imageslider_image($imagesliders_id, $languages[$i]['id']);
        if (isset($_POST['imagesliders_image_delete' . $i]) && $_POST['imagesliders_image_delete' . $i] == 'imagesliders_image_delete' . $i) {
          $rel = $old_imagepfad;
          if (!empty($rel)) {
            mits_imageslider_delete_variants_from_relative($rel);
            if (function_exists('mits_imageslider_delete_auto_fallbacks_from_relative')) {
              mits_imageslider_delete_auto_fallbacks_from_relative($rel);
            }
          }
          $imagepfad = '';
        }
        if ($image = xtc_try_upload('imagesliders_image' . $i, DIR_FS_CATALOG_IMAGES . 'imagesliders/' . $languages[$i]['directory'] . '/', '644', $accepted_imagesliders_image_files_extensions, $accepted_imagesliders_image_files_mime_types)) {
          $imagepfad = 'imagesliders/' . $languages[$i]['directory'] . '/' . $image->filename;
          $imagepfad = mits_imageslider_generate_variants_from_relative($imagepfad, 'desktop');
        } else {
          if (!isset($_POST['imagesliders_image_delete' . $i])) {
            $imagepfad = $old_imagepfad;
          }
        }
        if ($imagepfad != '' && $old_imagepfad != '' && $imagepfad != $old_imagepfad && function_exists('mits_imageslider_delete_auto_fallbacks_from_relative')) {
          mits_imageslider_delete_auto_fallbacks_from_relative($old_imagepfad);
        }
        if ($image === false && $imagepfad === false) {
          $messageStack->add(xtc_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/admin/images/' . $languages[$i]['image'], $languages[$i]['name']) . ERROR_IMAGESLIDER_IMAGE_REQUIRED, 'error');
          $imageslider_error = true;
        }

        if (isset($_POST['imagesliders_tablet_image_delete' . $i]) && $_POST['imagesliders_tablet_image_delete' . $i] == 'imagesliders_tablet_image_delete' . $i) {
          $rel = xtc_get_imageslider_tablet_image($imagesliders_id, $languages[$i]['id']);
          if (!empty($rel)) {
            mits_imageslider_delete_variants_from_relative($rel);
          }
          $tablet_imagepfad = '';
        }
        if ($tablet_image = xtc_try_upload('imagesliders_tablet_image' . $i, DIR_FS_CATALOG_IMAGES . 'imagesliders/' . $languages[$i]['directory'] . '/tablet/', '644', $accepted_imagesliders_image_files_extensions, $accepted_imagesliders_image_files_mime_types)) {
          $tablet_imagepfad = 'imagesliders/' . $languages[$i]['directory'] . '/tablet/' . $tablet_image->filename;
          $tablet_imagepfad = mits_imageslider_generate_variants_from_relative($tablet_imagepfad, 'tablet');
        } else {
          if (!isset($_POST['imagesliders_tablet_image_delete' . $i])) {
            $tablet_imagepfad = xtc_get_imageslider_tablet_image($imagesliders_id, $languages[$i]['id']);
          }
        }

        if (isset($_POST['imagesliders_mobile_image_delete' . $i]) && $_POST['imagesliders_mobile_image_delete' . $i] == 'imagesliders_mobile_image_delete' . $i) {
          $rel = xtc_get_imageslider_mobile_image($imagesliders_id, $languages[$i]['id']);
          if (!empty($rel)) {
            mits_imageslider_delete_variants_from_relative($rel);
          }
          $mobile_imagepfad = '';
        }
        if ($mobile_image = xtc_try_upload('imagesliders_mobile_image' . $i, DIR_FS_CATALOG_IMAGES . 'imagesliders/' . $languages[$i]['directory'] . '/mobile/', '644', $accepted_imagesliders_image_files_extensions, $accepted_imagesliders_image_files_mime_types)) {
          $mobile_imagepfad = 'imagesliders/' . $languages[$i]['directory'] . '/mobile/' . $mobile_image->filename;
          $mobile_imagepfad = mits_imageslider_generate_variants_from_relative($mobile_imagepfad, 'mobile');
        } else {
          if (!isset($_POST['imagesliders_mobile_image_delete' . $i])) {
            $mobile_imagepfad = xtc_get_imageslider_mobile_image($imagesliders_id, $languages[$i]['id']);
          }
        }

        // Generate physical tablet/mobile fallback variants from the main image if no dedicated image exists.
        // The DB fields stay empty, so manually uploaded tablet/mobile images remain clearly distinguishable.
        if ($imagepfad != '' && function_exists('mits_imageslider_generate_auto_fallback_from_relative')) {
          if ($tablet_imagepfad == '') {
            mits_imageslider_generate_auto_fallback_from_relative($imagepfad, 'tablet');
          }
          if ($mobile_imagepfad == '') {
            mits_imageslider_generate_auto_fallback_from_relative($imagepfad, 'mobile');
          }
        }

        $image_width = $image_height = $tablet_image_width = $tablet_image_height = $mobile_image_width = $mobile_image_height = 0;
        if ($imagepfad != '' && file_exists(DIR_FS_CATALOG_IMAGES . $imagepfad)) {
          list($image_width, $image_height, $image_type, $image_attr) = getimagesize(DIR_FS_CATALOG_IMAGES . $imagepfad);
        }
        if ($tablet_imagepfad != '' && file_exists(DIR_FS_CATALOG_IMAGES . $tablet_imagepfad)) {
          list($tablet_image_width, $tablet_image_height, $tablet_image_type, $tablet_image_attr) = getimagesize(DIR_FS_CATALOG_IMAGES . $tablet_imagepfad);
        }
        if ($mobile_imagepfad != '' && file_exists(DIR_FS_CATALOG_IMAGES . $mobile_imagepfad)) {
          list($mobile_image_width, $mobile_image_height, $mobile_image_type, $mobile_image_attr) = getimagesize(DIR_FS_CATALOG_IMAGES . $mobile_imagepfad);
        }

        if ($imageslider_error === false) {
          $imagesliders_url_array = (isset($_POST['imagesliders_url'])) ? $_POST['imagesliders_url'] : '';
          $imagesliders_url_target_array = (isset($_POST['imagesliders_url_target'])) ? $_POST['imagesliders_url_target'] : 0;
          $imagesliders_url_typ_array = (isset($_POST['imagesliders_url_typ'])) ? $_POST['imagesliders_url_typ'] : 1;
          $imagesliders_alt_array = (isset($_POST['imagesliders_alt'])) ? $_POST['imagesliders_alt'] : '';
          $imagesliders_title_array = (isset($_POST['imagesliders_title'])) ? $_POST['imagesliders_title'] : '';
          $imagesliders_linktitle_array = (isset($_POST['imagesliders_linktitle'])) ? $_POST['imagesliders_linktitle'] : '';
          $imagesliders_description_array = (isset($_POST['imagesliders_description'])) ? $_POST['imagesliders_description'] : '';
          $language_id = (int)$languages[$i]['id'];
          $imagesliders_url = isset($imagesliders_url_array[$language_id]) ? $imagesliders_url_array[$language_id] : '';
          $imagesliders_url_typ = isset($imagesliders_url_typ_array[$language_id]) ? $imagesliders_url_typ_array[$language_id] : 0;
          $imagesliders_url_prepared = mits_imageslider_prepare_url_for_save($imagesliders_url, $imagesliders_url_typ);
          $lang_data_array = array(
            'imagesliders_url'                 => xtc_db_prepare_input($imagesliders_url_prepared['url']),
            'imagesliders_url_target'          => xtc_db_prepare_input($imagesliders_url_target_array[$language_id]),
            'imagesliders_url_typ'             => xtc_db_prepare_input($imagesliders_url_prepared['typ']),
            'imagesliders_image'               => $imagepfad,
            'imagesliders_tablet_image'        => $tablet_imagepfad,
            'imagesliders_mobile_image'        => $mobile_imagepfad,
            'imagesliders_image_width'         => (float)$image_width,
            'imagesliders_image_height'        => (float)$image_height,
            'imagesliders_tablet_image_width'  => (float)$tablet_image_width,
            'imagesliders_tablet_image_height' => (float)$tablet_image_height,
            'imagesliders_mobile_image_width'  => (float)$mobile_image_width,
            'imagesliders_mobile_image_height' => (float)$mobile_image_height,
            'imagesliders_alt'                 => xtc_db_prepare_input($imagesliders_alt_array[$language_id]),
            'imagesliders_title'               => xtc_db_prepare_input($imagesliders_title_array[$language_id]),
            'imagesliders_linktitle'           => xtc_db_prepare_input($imagesliders_linktitle_array[$language_id]),
            'imagesliders_description'         => xtc_db_prepare_input($imagesliders_description_array[$language_id])
          );

          if ($action == 'insert') {
            $insert_lang_data = array(
              'imagesliders_id' => $imagesliders_id,
              'languages_id'    => $language_id
            );
            $lang_data_array = array_merge($lang_data_array, $insert_lang_data);
            xtc_db_perform(TABLE_MITS_IMAGESLIDER_INFO, $lang_data_array);
          } elseif ($action == 'save') {
            $imagesliders_query = xtc_db_query("SELECT * 
                                                   FROM " . TABLE_MITS_IMAGESLIDER_INFO . " 
                                                  WHERE languages_id = " . $language_id . " 
                                                    AND imagesliders_id = " . $imagesliders_id);
            if (!xtc_db_num_rows($imagesliders_query)) {
              xtc_db_perform(TABLE_MITS_IMAGESLIDER_INFO, array('imagesliders_id' => $imagesliders_id, 'languages_id' => $language_id));
            }
            xtc_db_perform(TABLE_MITS_IMAGESLIDER_INFO, $lang_data_array, 'update', "imagesliders_id = '" . $imagesliders_id . "' AND languages_id = " . $language_id);
          }


        }
      }

      if ($imageslider_error !== true) {
        $redirect_params = '';
        if (isset($_GET['page'])) {
          $redirect_params .= 'page=' . (int)$_GET['page'];
        }
        if (isset($_GET['groupfilter']) && $_GET['groupfilter'] != '') {
          $redirect_params .= ($redirect_params != '' ? '&' : '') . 'groupfilter=' . urlencode($_GET['groupfilter']);
        }

        if (isset($_POST['save_and_stay']) && $_POST['save_and_stay'] == '1' && (int)$imagesliders_id > 0) {
          $redirect_params .= ($redirect_params != '' ? '&' : '') . 'iID=' . (int)$imagesliders_id . '&action=edit';
        }

        xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER, $redirect_params));
      } else {
        $action = ($action == 'insert') ? 'new' : 'edit';
      }
      break;

    case 'deleteconfirm':
      $imagesliders_id = (int)$_GET['iID'];
      if (isset($_POST['delete_image'])) {
        $languages = xtc_get_languages();
        $delete_images_array = array();

        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $rel = trim((string)xtc_get_imageslider_image($imagesliders_id, $languages[$i]['id']));
          if ($rel !== '') {
            $delete_images_array[$rel] = $rel;
            if (function_exists('mits_imageslider_auto_fallback_candidates')) {
              foreach (array('tablet', 'mobile') as $profile) {
                foreach (mits_imageslider_auto_fallback_candidates($rel, $profile) as $auto_rel) {
                  if (is_file(mits_imageslider_abs_image_path($auto_rel))) {
                    $delete_images_array[$auto_rel] = $auto_rel;
                  }
                }
              }
            }
          }

          $rel = trim((string)xtc_get_imageslider_tablet_image($imagesliders_id, $languages[$i]['id']));
          if ($rel !== '') {
            $delete_images_array[$rel] = $rel;
          }

          $rel = trim((string)xtc_get_imageslider_mobile_image($imagesliders_id, $languages[$i]['id']));
          if ($rel !== '') {
            $delete_images_array[$rel] = $rel;
          }
        }

        foreach ($delete_images_array as $rel) {
          mits_imageslider_delete_variants_from_relative($rel);
        }
      }
      xtc_db_query("DELETE FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_id = " . $imagesliders_id);
      xtc_db_query("DELETE FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . $imagesliders_id);
      xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER, 'page=' . $page));
      break;

    case 'setflag':
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER));
      }
      $imagesliders_id = isset($_POST['iID']) ? (int)$_POST['iID'] : 0;
      $imagesliders_status = isset($_POST['flag']) ? (int)$_POST['flag'] : 0;
      $imagesliders_status = ($imagesliders_status == 1) ? 1 : 0;
      if ($imagesliders_id > 0) {
        xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER . " SET status = " . $imagesliders_status . " WHERE imagesliders_id = " . $imagesliders_id);
      }
      $redirect_params = '';
      if (isset($_GET['page'])) {
        $redirect_params .= 'page=' . (int)$_GET['page'];
      }
      if (isset($_GET['groupfilter']) && $_GET['groupfilter'] != '') {
        $redirect_params .= ($redirect_params != '' ? '&' : '') . 'groupfilter=' . urlencode($_GET['groupfilter']);
      }
      xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER, $redirect_params));
      break;
  }

  require_once(DIR_WS_INCLUDES . 'head.php');

  //jQueryDatepicker
  require(DIR_WS_INCLUDES . 'javascript/jQueryDateTimePicker/datepicker.js.php');
  ?>
  <script type="text/javascript" src="includes/general.js"></script>
  <style type="text/css">
    .mits-admin{
      box-sizing:border-box;
      display:block;
      min-width:0;
      --mits-ci-primary:#6a9;
      --mits-ci-primary-dark:#4f8e7e;
      --mits-ci-primary-soft:#edf7f4;
      --mits-ci-primary-soft-2:#f7fbfa;
      --mits-ci-line:#d4e7e0;
      --mits-ci-line-strong:#b9d6cb;
      --mits-ci-ink:#444;
      --mits-ci-heading:#30534b;
      --mits-ci-muted:#6d7b77;
      --mits-ci-shadow:rgba(76,110,101,.10);
      --mits-ci-danger-bg:#fdeeed;
      --mits-ci-danger-text:#a3483f;
      --mits-ci-success-bg:#ecf8ef;
      --mits-ci-success-text:#2e6e3d;
      padding:18px;
      color:var(--mits-ci-ink);
      width:100%;
      max-width:100%;
      overflow:visible;
    }
    .mits-admin,.mits-admin *,.mits-admin *:before,.mits-admin *:after{box-sizing:border-box}
    .mits-admin form{max-width:100%;min-width:0;margin:0}
    .mits-admin table{max-width:100%}
    .mits-admin input[type="text"],.mits-admin input[type="number"],.mits-admin input[type="file"],.mits-admin select,.mits-admin textarea{min-width:0}
    .mits-admin a{text-decoration:none}
    .mits-admin__hero{display:flex;justify-content:space-between;gap:18px;align-items:flex-start;flex-wrap:wrap;min-width:0;margin-bottom:18px;padding:20px 22px;border:1px solid var(--mits-ci-line);border-radius:18px;background:linear-gradient(135deg,var(--mits-ci-primary-soft),#fff);box-shadow:0 8px 24px var(--mits-ci-shadow)}
    .mits-admin__hero > div{min-width:0}
    .mits-admin__hero h1{margin:0 0 8px;color:var(--mits-ci-heading);font-size:24px;line-height:1.2;overflow-wrap:anywhere}
    .mits-admin__hero p{margin:0;line-height:1.55;color:var(--mits-ci-ink);overflow-wrap:anywhere}
    .mits-admin__hero-actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;align-items:center;min-width:0;max-width:100%}
    .mits-card{border:1px solid var(--mits-ci-line);border-radius:18px;background:#fff;box-shadow:0 8px 24px var(--mits-ci-shadow);overflow:visible;margin-bottom:18px;width:100%;max-width:100%;min-width:0;display:flow-root}
    .mits-card:after,.mits-card__body:after,.mits-language-panel:after{content:"";display:block;clear:both}
    .mits-card__header{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;flex-wrap:wrap;min-width:0;padding:18px 20px;border-bottom:1px solid var(--mits-ci-line);background:var(--mits-ci-primary-soft-2);border-radius:17px 17px 0 0}
    .mits-card__header > div{min-width:0;max-width:100%}
    .mits-card__title{margin:0;color:var(--mits-ci-heading);font-size:18px;line-height:1.3}
    .mits-card__subtitle{margin:6px 0 0;color:var(--mits-ci-muted);line-height:1.45}
    .mits-card__body{padding:18px 20px;min-width:0;max-width:100%;display:flow-root}
    .mits-button,.mits-button:link,.mits-button:visited,.mits-admin button.mits-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:38px;max-width:100%;padding:0 14px;border-radius:12px;border:1px solid var(--mits-ci-line-strong);background:#fff;color:var(--mits-ci-heading);font-weight:700;text-decoration:none;cursor:pointer;transition:all .15s ease;font-family:inherit;font-size:12px;line-height:1.2;white-space:normal}
    .mits-button:hover,.mits-admin button.mits-button:hover{border-color:var(--mits-ci-primary-dark);background:var(--mits-ci-primary-soft);color:var(--mits-ci-heading)}
    .mits-button--primary,.mits-button--primary:link,.mits-button--primary:visited,.mits-admin button.mits-button--primary{background:var(--mits-ci-primary-dark);border-color:var(--mits-ci-primary-dark);color:#fff}
    .mits-button--primary:hover,.mits-admin button.mits-button--primary:hover{background:#467d70;border-color:#467d70;color:#fff}
    .mits-button--soft{background:var(--mits-ci-primary-soft);border-color:var(--mits-ci-line);color:var(--mits-ci-heading)}
    .mits-button--danger{background:var(--mits-ci-danger-bg);border-color:#f1cec9;color:var(--mits-ci-danger-text)}
    .mits-button--ghost{background:#fff;border-color:transparent;color:var(--mits-ci-muted)}
    .mits-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700;background:#eef4f2;color:#536661;white-space:nowrap}
    .mits-badge.is-success{background:var(--mits-ci-success-bg);color:var(--mits-ci-success-text)}
    .mits-badge.is-danger{background:var(--mits-ci-danger-bg);color:var(--mits-ci-danger-text)}
    .mits-subtle{color:var(--mits-ci-muted);font-size:12px;line-height:1.45}
    .mits-empty{padding:14px 16px;border:1px dashed var(--mits-ci-line-strong);border-radius:14px;background:#fcfefd;color:var(--mits-ci-muted)}
    .mits-toolbar{display:flex;gap:10px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:14px;max-width:100%}
    .mits-toolbar form{margin:0;display:flex;gap:8px;align-items:center;flex-wrap:wrap}
    .mits-toolbar select{min-height:38px;border:1px solid var(--mits-ci-line-strong);border-radius:10px;background:#fff;padding:7px 10px;color:var(--mits-ci-ink)}
    .mits-pagination{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:14px;padding:12px 14px;border:1px solid var(--mits-ci-line);border-radius:14px;background:var(--mits-ci-primary-soft-2);max-width:100%;min-width:0;overflow:visible}
    .mits-pagination__count,.mits-pagination__links,.mits-pagination__per-page{min-width:0;max-width:100%;color:var(--mits-ci-muted);font-size:12px;line-height:1.45}
    .mits-pagination__links{display:flex;gap:4px;align-items:center;flex-wrap:wrap;justify-content:center}
    .mits-pagination__per-page form{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin:0}
    .mits-pagination a,.mits-pagination a:link,.mits-pagination a:visited{display:inline-flex;align-items:center;justify-content:center;min-width:30px;min-height:30px;padding:4px 8px;border:1px solid var(--mits-ci-line-strong);border-radius:10px;background:#fff;color:var(--mits-ci-heading);font-weight:700;text-decoration:none}
    .mits-pagination a:hover{background:var(--mits-ci-primary-soft);border-color:var(--mits-ci-primary-dark);color:var(--mits-ci-heading);text-decoration:none}
    .mits-table-wrap{max-width:100%;overflow-x:auto;overflow-y:visible}
    .mits-table{width:100%;border-collapse:separate;border-spacing:0;min-width:940px}
    .mits-table thead th{padding:12px 14px;background:var(--mits-ci-primary-soft);border-bottom:1px solid var(--mits-ci-line);color:var(--mits-ci-muted);font-size:12px;text-transform:uppercase;letter-spacing:.04em;text-align:left;white-space:nowrap}
    .mits-table tbody td{padding:12px 14px;border-bottom:1px solid #edf4f1;vertical-align:middle;color:var(--mits-ci-ink)}
    .mits-table tbody tr:hover{background:#fbfdfc}
    .mits-slider-row{background:#fff;transition:background .15s ease,opacity .15s ease}
    .mits-slider-row.is-dragging{opacity:.45;background:var(--mits-ci-primary-soft)}
    .mits-slider-row.is-dirty{background:#fffdf5}
    #mits-sort-hint.is-saving{color:#856404;font-weight:700}
    #mits-sort-hint.is-success{color:#2f7d32;font-weight:700}
    #mits-sort-hint.is-error{color:#b42318;font-weight:700}
    .mits-drag-handle{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:1px solid var(--mits-ci-line);border-radius:10px;background:#fff;font-weight:700;color:var(--mits-ci-muted);cursor:grab;user-select:none}
    .mits-drag-handle:active{cursor:grabbing}
    .mits-preview-list{display:flex;gap:8px;align-items:center;flex-wrap:wrap;min-width:140px}
    .mits-preview{display:flex;align-items:center;gap:4px;padding:5px;border:1px solid var(--mits-ci-line);border-radius:12px;background:#fff;min-height:54px}
    .mits-preview img:not(.language-flag){display:block;max-width:92px;max-height:46px;width:auto;height:auto}
    .mits-preview .language-flag{display:block;width:auto;height:auto;max-width:18px;margin-right:3px}
    .mits-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}
    .mits-admin a.mits-icon-action,
    .mits-admin button.mits-icon-action,
    .mits-admin a.mits-icon-action:link,
    .mits-admin a.mits-icon-action:visited,
    .mits-admin a.mits-icon-action:hover,
    .mits-admin a.mits-icon-action:active,
    .mits-admin button.mits-icon-action,
    .mits-admin span.mits-icon-action{display:inline-flex!important;align-items:center!important;justify-content:center!important;width:34px!important;height:34px!important;min-width:34px!important;border-radius:11px!important;border:1px solid var(--mits-ci-line-strong)!important;background:#fff!important;color:var(--mits-ci-heading)!important;text-decoration:none!important;box-shadow:none!important;line-height:1!important;padding:0!important;margin:0!important;font-size:0!important;overflow:hidden!important;vertical-align:middle!important;transition:background .15s ease,border-color .15s ease,transform .15s ease,opacity .15s ease!important}
    .mits-admin a.mits-icon-action:hover,.mits-admin button.mits-icon-action:hover{transform:translateY(-1px)!important;background:var(--mits-ci-primary-soft)!important;border-color:var(--mits-ci-primary-dark)!important;color:var(--mits-ci-heading)!important;text-decoration:none!important}
    .mits-admin .mits-icon-action svg{display:block!important;width:18px!important;height:18px!important;stroke:currentColor!important;fill:none!important;pointer-events:none!important}
    .mits-admin .mits-icon-action--status.is-active{background:var(--mits-ci-success-bg)!important;border-color:#8fcaa5!important;color:var(--mits-ci-success-text)!important}
    .mits-admin .mits-icon-action--status.is-inactive{background:var(--mits-ci-danger-bg)!important;border-color:#ebb7ae!important;color:var(--mits-ci-danger-text)!important}
    .mits-admin .mits-icon-action--status.is-muted{background:#fff!important;border-color:var(--mits-ci-line-strong)!important;color:#9aa8a4!important;opacity:.72!important}
    .mits-admin a.mits-icon-action--status.is-muted:hover,.mits-admin button.mits-icon-action--status.is-muted:hover{opacity:1!important;background:var(--mits-ci-primary-soft)!important;border-color:var(--mits-ci-primary-dark)!important;color:var(--mits-ci-heading)!important}
    .mits-admin .mits-icon-action--status.is-current{box-shadow:0 0 0 2px rgba(106,153,136,.16)!important;opacity:1!important;cursor:default!important}
    .mits-status-cell{display:flex;align-items:center;justify-content:center;gap:6px;min-width:78px;white-space:nowrap}
    .mits-status-form{display:inline-flex!important;margin:0!important;padding:0!important;border:0!important;background:transparent!important}
    .mits-order-index{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:32px;border-radius:10px;background:var(--mits-ci-primary-soft);color:var(--mits-ci-heading);font-weight:700}
    .mits-form-grid{display:grid;grid-template-columns:repeat(3,minmax(180px,1fr));gap:14px;align-items:start}
    .mits-field{display:flex;flex-direction:column;gap:6px;min-width:0}
    .mits-field label,.mits-label{font-weight:700;color:var(--mits-ci-heading);font-size:12px;text-transform:uppercase;letter-spacing:.03em}
    .mits-field input[type="text"],.mits-field input[type="number"],.mits-field select,.mits-field textarea,.mits-admin input[type="text"],.mits-admin select,.mits-admin textarea{max-width:100%;border:1px solid var(--mits-ci-line-strong);border-radius:10px;background:#fff;padding:8px 10px;color:var(--mits-ci-ink)}
    .mits-field input[type="text"],.mits-field select{width:100%;min-height:38px}
    .mits-field textarea{width:100%;min-height:160px;font-family:monospace}
    .mits-field.is-full{grid-column:1/-1}
    .mits-checks{display:flex;gap:12px;flex-wrap:wrap;align-items:center;padding-top:4px}
    .mits-checks label{font-weight:400;text-transform:none;letter-spacing:0;color:var(--mits-ci-ink)}
    .mits-language-panel{padding-top:12px;max-width:100%;overflow:visible;display:flow-root;clear:both}
    .mits-lang-tabs-wrap{width:100%;max-width:100%;min-width:0;overflow-x:auto;overflow-y:visible;padding-bottom:6px;margin-bottom:12px;display:flow-root;clear:both}
    .mits-lang-tabs-wrap table,.mits-lang-tabs-wrap ul,.mits-lang-tabs-wrap div{max-width:100%;min-width:0}
    .mits-lang-tabs-wrap ul{display:flex;flex-wrap:wrap;gap:6px;list-style:none;margin:0;padding:0}
    .mits-lang-tabs-wrap li{float:none!important;display:block;margin:0!important}
    .mits-lang-tabs-wrap a{display:inline-flex;align-items:center;max-width:100%;white-space:normal}
    .mits-lang-tabs-wrap img{max-width:none}
    .mits-image-grid{display:grid;grid-template-columns:repeat(3,minmax(180px,1fr));gap:14px;margin-bottom:14px}
    .mits-image-box{border:1px solid var(--mits-ci-line);border-radius:14px;background:#fcfefd;padding:12px;display:flex;flex-direction:column;gap:10px;min-width:0}
    .mits-image-box strong{color:var(--mits-ci-heading)}
    .mits-image-box img{max-width:100%;height:auto;max-height:180px;border-radius:8px}
    .mits-image-box input[type="file"]{width:100%;max-width:100%}
    .mits-link-grid{display:grid;grid-template-columns:minmax(170px,.45fr) minmax(250px,1fr) minmax(150px,.35fr);gap:14px;align-items:start}
    .mits-edit-actionbar{position:sticky;top:8px;z-index:20;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;padding:12px 14px;border:1px solid var(--mits-ci-line);border-radius:16px;background:rgba(247,251,250,.96);box-shadow:0 8px 22px var(--mits-ci-shadow);backdrop-filter:saturate(120%) blur(3px);width:100%;max-width:100%;min-width:0;overflow:visible}
    .mits-edit-actionbar__title{min-width:0;flex:1 1 220px;color:var(--mits-ci-heading);font-weight:700;line-height:1.35;overflow-wrap:anywhere}
    .mits-edit-actionbar__title span{display:block;color:var(--mits-ci-muted);font-size:12px;font-weight:400;word-break:break-all}
    .mits-edit-actionbar__actions{display:flex;gap:10px;flex:0 1 auto;flex-wrap:wrap;justify-content:flex-end;max-width:100%;min-width:0;margin-left:auto}
    .mits-delete-preview{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:12px 0}
    .mits-delete-preview .mits-preview{justify-content:center;min-height:120px}
    .mits-language-panel .cke,.mits-language-panel .cke_chrome,.mits-language-panel .cke_inner,.mits-language-panel .cke_contents{max-width:100%!important;box-sizing:border-box}
    @media (max-width:1450px){.mits-form-grid,.mits-image-grid{grid-template-columns:repeat(2,minmax(180px,1fr))}.mits-link-grid{grid-template-columns:1fr}}
    @media (max-width:980px){.mits-admin{padding:10px}.mits-admin__hero,.mits-card__header,.mits-edit-actionbar{flex-direction:column}.mits-admin__hero-actions,.mits-edit-actionbar__actions{justify-content:flex-start;margin-left:0}.mits-form-grid,.mits-image-grid{grid-template-columns:1fr}.mits-table{min-width:760px}.mits-edit-actionbar{position:static}}
  </style>
  <?php
  if (defined('USE_WYSIWYG') && USE_WYSIWYG == 'true') {
    $query = xtc_db_query("SELECT code FROM " . TABLE_LANGUAGES . " WHERE languages_id = " . (int)$_SESSION['languages_id']);
    $data = xtc_db_fetch_array($query);
    echo PHP_EOL . (!function_exists('editorJSLink') ? '<script type="text/javascript" src="includes/modules/ckeditor/ckeditor.js"></script>' : '') . PHP_EOL;
    if ($action == 'edit' || $action == 'new') {
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        echo xtc_wysiwyg('imagesliders_description', $data['code'], $languages[$i]['id']);
      }
    }
  }

  $slidergroups_array = array(
    array('id' => 'mits_imageslider', 'text' => 'MITS_IMAGESLIDER'),
    array('id' => 'mits_imageslider_top', 'text' => 'MITS_IMAGESLIDER_TOP'),
  );
  $slidergroups_seen = array('mits_imageslider' => true, 'mits_imageslider_top' => true);
  $slidergroups_query = xtc_db_query("SELECT DISTINCT imagesliders_group FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_group != 'mits_imageslider' ORDER BY imagesliders_group");
  while ($slidergroups = xtc_db_fetch_array($slidergroups_query)) {
    if (!isset($slidergroups_seen[$slidergroups['imagesliders_group']])) {
      $slidergroups_array[] = array('id' => $slidergroups['imagesliders_group'], 'text' => strtoupper($slidergroups['imagesliders_group']));
      $slidergroups_seen[$slidergroups['imagesliders_group']] = true;
    }
  }
  ?>
  </head>
  <body>
  <?php require_once(DIR_WS_INCLUDES . 'header.php'); ?>
  <table class="tableBody">
    <tr>
      <?php
      if (defined('USE_ADMIN_TOP_MENU') && USE_ADMIN_TOP_MENU == 'false') {
        echo '<td class="columnLeft2">' . PHP_EOL;
        require_once(DIR_WS_INCLUDES . 'column_left.php');
        echo '</td>' . PHP_EOL;
      }
      ?>
      <td class="boxCenter" width="100%" valign="top">
        <div class="mits-admin">
          <div class="mits-admin__hero">
            <div>
              <h1><?php echo HEADING_TITLE_IMAGESLIDERS . '<small style="font-weight:normal;font-size:0.6em;">' . (defined('MODULE_MITS_IMAGESLIDER_VERSION') ? ' - v' . MODULE_MITS_IMAGESLIDER_VERSION : '') . '</small>'; ?></h1>
              <p><?php echo HEADING_SUBTITLE_IMAGESLIDERS; ?></p>
            </div>
            <div class="mits-admin__hero-actions">
              <?php if ($action != 'new' && $action != 'edit') { ?>
                <a class="mits-button mits-button--primary" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'page', 'action')) . 'action=new'); ?>">+ <?php echo BUTTON_INSERT; ?></a>
              <?php } ?>
            </div>
          </div>

          <?php
          if ($action == 'edit' || $action == 'new') {
            if ($action == 'new') {
              unset($_GET['iID']);
              $imageslider = xtc_get_default_table_data(TABLE_MITS_IMAGESLIDER);
              $imageslider['status'] = 0;
              if (!isset($imageslider['imagesliders_id'])) {
                $imageslider['imagesliders_id'] = 0;
              }
              if (empty($imageslider['imagesliders_group'])) {
                $imageslider['imagesliders_group'] = 'mits_imageslider';
              }
              if (empty($imageslider['sorting'])) {
                $sorting_query = xtc_db_query("SELECT MAX(sorting) AS max_sorting FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_group = '" . xtc_db_input($imageslider['imagesliders_group']) . "'");
                $sorting_data = xtc_db_fetch_array($sorting_query);
                $imageslider['sorting'] = (int)$sorting_data['max_sorting'] + 1;
              }
            } else {
              $imageslider_query = xtc_db_query("SELECT * FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_id = " . (int)$_GET['iID']);
              $imageslider = xtc_db_fetch_array($imageslider_query);
              if (!isset($imageslider['imagesliders_id'])) {
                $imageslider['imagesliders_id'] = 0;
              }
            }

            echo xtc_draw_form('imagesliders', FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action', 'page')) . ((isset($_GET['page'])) ? 'page=' . (int)$_GET['page'] . '&' : '') . ((isset($_GET['iID'])) ? 'iID=' . (int)$_GET['iID'] . '&' : '') . 'action=' . (($action == 'new') ? 'insert' : 'save'), 'post', 'enctype="multipart/form-data"');
            ?>
            <div class="mits-edit-actionbar">
              <div class="mits-edit-actionbar__title">
                <?php echo ($action == 'new') ? mits_imageslider_admin_text('BUTTON_INSERT', 'Neuen Slider anlegen') : mits_imageslider_admin_h($imageslider['imagesliders_name']); ?>
                <span><?php echo ($action == 'new') ? mits_imageslider_admin_text('TEXT_IMAGESLIDERS_NEW_GROUP', 'Slidergruppe') : mits_imageslider_admin_h($imageslider['imagesliders_group']); ?></span>
              </div>
              <div class="mits-edit-actionbar__actions">
                <button type="submit" class="mits-button mits-button--primary"><?php echo BUTTON_SAVE; ?></button>
                <button type="submit" name="save_and_stay" value="1" class="mits-button mits-button--soft"><?php echo mits_imageslider_admin_text('BUTTON_UPDATE', 'Aktualisieren'); ?></button>
                <a class="mits-button" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'page', 'action')) . (isset($_GET['page']) ? 'page=' . (int)$_GET['page'] . '&' : '') . (isset($_GET['groupfilter']) ? 'groupfilter=' . urlencode($_GET['groupfilter']) : '')); ?>"><?php echo BUTTON_CANCEL; ?></a>
              </div>
            </div>

            <div class="mits-card">
              <div class="mits-card__header">
                <div>
                  <h2 class="mits-card__title"><?php echo mits_imageslider_admin_text('TEXT_HEADING_EDIT_IMAGESLIDER', 'Grunddaten'); ?></h2>
                  <p class="mits-card__subtitle"><?php echo mits_imageslider_admin_text('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Name, Gruppe, Status und Zeitraum konfigurieren.'); ?></p>
                </div>
              </div>
              <div class="mits-card__body">
                <div class="mits-form-grid">
                  <div class="mits-field is-full">
                    <label><?php echo TEXT_IMAGESLIDERS_NAME; ?></label>
                    <?php echo xtc_draw_input_field('imagesliders_name', $imageslider['imagesliders_name']); ?>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TABLE_HEADING_SORTING; ?></label>
                    <?php echo xtc_draw_input_field('imagesliders_sorting', $imageslider['sorting']); ?>
                    <span class="mits-subtle"><?php echo mits_imageslider_admin_text('TABLE_HEADING_SORTING', 'Sortierung'); ?> kann auch in der Liste per Drag-&amp;-Drop gepflegt werden.</span>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TABLE_HEADING_STATUS; ?></label>
                    <div class="mits-checks">
                      <label><?php echo xtc_draw_selection_field('imagesliders_status', 'radio', '0', $imageslider['status'] == 0 ? true : false) . ' ' . mits_imageslider_admin_text('MITS_ACTIVE', 'aktiv'); ?></label>
                      <label><?php echo xtc_draw_selection_field('imagesliders_status', 'radio', '1', $imageslider['status'] == 1 ? true : false) . ' ' . mits_imageslider_admin_text('MITS_NOTACTIVE', 'inaktiv'); ?></label>
                    </div>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TABLE_HEADING_SLIDERGROUP; ?></label>
                    <?php echo xtc_draw_pull_down_menu('imagesliders_group', $slidergroups_array, $imageslider['imagesliders_group']); ?>
                  </div>
                  <div class="mits-field is-full">
                    <label><?php echo TEXT_IMAGESLIDERS_NEW_GROUP; ?></label>
                    <?php echo xtc_draw_input_field('new_imagesliders_group', ''); ?>
                    <span class="mits-subtle"><?php echo TEXT_IMAGESLIDERS_NEW_GROUP_NOTE; ?></span>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TEXT_IMAGESLIDERS_SCHEDULED_AT; ?></label>
                    <?php echo xtc_draw_input_field('date_scheduled', $imageslider['date_scheduled'], 'id="Datepicker1"'); ?>
                    <span class="mits-subtle"><?php echo TEXT_IMAGESLIDERS_DATE_FORMAT; ?></span>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TEXT_IMAGESLIDERS_EXPIRES_ON; ?></label>
                    <?php echo xtc_draw_input_field('expires_date', $imageslider['expires_date'], 'id="Datepicker2"'); ?>
                    <span class="mits-subtle"><?php echo TEXT_IMAGESLIDERS_DATE_FORMAT; ?></span>
                  </div>
                  <div class="mits-field">
                    <label><?php echo TEXT_IMAGESLIDERS_RECURRING; ?></label>
                    <div class="mits-checks">
                      <label><?php echo xtc_draw_selection_field('imagesliders_recurring', 'checkbox', '1', ((isset($imageslider['recurring']) && $imageslider['recurring'] == 1) ? true : false)) . ' ' . TEXT_IMAGESLIDERS_RECURRING_YEARLY; ?></label>
                    </div>
                    <span class="mits-subtle"><?php echo TEXT_IMAGESLIDERS_RECURRING_NOTE; ?></span>
                  </div>
                </div>
              </div>
            </div>

            <?php
            $url_target_array = array();
            $url_target_array[] = array('id' => '0', 'text' => NONE_TARGET);
            $url_target_array[] = array('id' => '1', 'text' => TARGET_BLANK);
            $url_target_array[] = array('id' => '2', 'text' => TARGET_TOP);
            $url_target_array[] = array('id' => '3', 'text' => TARGET_SELF);
            $url_target_array[] = array('id' => '4', 'text' => TARGET_PARENT);
            ?>
            <div class="mits-card">
              <div class="mits-card__header">
                <div>
                  <h2 class="mits-card__title"><?php echo mits_imageslider_admin_text('TEXT_IMAGESLIDERS_IMAGE', 'Bilder und Sprachdaten'); ?></h2>
                  <p class="mits-card__subtitle"><?php echo mits_imageslider_admin_text('TEXT_IMAGESLIDERS_ALT', 'Bilder, Links, Alt-/Title-Texte und Beschreibung je Sprache pflegen.'); ?></p>
                </div>
              </div>
              <div class="mits-card__body">
                <?php
                echo '<div class="mits-lang-tabs-wrap">';
                include('includes/lang_tabs.php');
                echo '</div>';
                for ($i = 0; $i < sizeof($languages); $i++) {
                  if ($action == 'new') {
                    $imagesliders = xtc_get_default_table_data(TABLE_MITS_IMAGESLIDER_INFO);
                  } else {
                    $imagesliders_query = xtc_db_query("SELECT * FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$_GET['iID'] . " AND languages_id = " . (int)$languages[$i]['id']);
                    $imagesliders = xtc_db_fetch_array($imagesliders_query);
                  }
                  $current_url_typ = (isset($imagesliders['imagesliders_url_typ']) && $imagesliders['imagesliders_url_typ'] !== '') ? (int)$imagesliders['imagesliders_url_typ'] : 1;
                  $current_url = isset($imagesliders['imagesliders_url']) ? $imagesliders['imagesliders_url'] : '';
                  $current_url_target = isset($imagesliders['imagesliders_url_target']) ? $imagesliders['imagesliders_url_target'] : 0;
                  $current_linktitle = isset($imagesliders['imagesliders_linktitle']) ? $imagesliders['imagesliders_linktitle'] : '';
                  $current_alt = isset($imagesliders['imagesliders_alt']) ? $imagesliders['imagesliders_alt'] : '';
                  $current_title = isset($imagesliders['imagesliders_title']) ? $imagesliders['imagesliders_title'] : '';
                  echo '<div id="tab_lang_' . (int)$i . '" class="mits-language-panel">';
                  ?>
                  <div class="mits-image-grid">
                    <div class="mits-image-box">
                      <strong><?php echo TEXT_IMAGESLIDERS_IMAGE; ?></strong>
                      <?php echo xtc_draw_file_field('imagesliders_image' . $i); ?>
                      <div><?php echo xtc_info_image(xtc_get_imageslider_image($imageslider['imagesliders_id'], $languages[$i]['id']), $imageslider['imagesliders_name'], '', '', 'style="max-width:100%;height:auto;max-height:180px;"'); ?></div>
                      <label class="mits-subtle"><?php echo xtc_draw_selection_field('imagesliders_image_delete' . $i, 'checkbox', 'imagesliders_image' . $i) . ' ' . TEXT_HEADING_DELETE_IMAGESLIDER; ?></label>
                    </div>
                    <div class="mits-image-box">
                      <strong><?php echo TEXT_IMAGESLIDERS_TABLET_IMAGE; ?></strong>
                      <?php echo xtc_draw_file_field('imagesliders_tablet_image' . $i); ?>
                      <div><?php echo xtc_info_image(xtc_get_imageslider_tablet_image($imageslider['imagesliders_id'], $languages[$i]['id']), $imageslider['imagesliders_name'], '', '', 'style="max-width:100%;height:auto;max-height:180px;"'); ?></div>
                      <label class="mits-subtle"><?php echo xtc_draw_selection_field('imagesliders_tablet_image_delete' . $i, 'checkbox', 'imagesliders_tablet_image' . $i) . ' ' . TEXT_HEADING_DELETE_IMAGESLIDER; ?></label>
                    </div>
                    <div class="mits-image-box">
                      <strong><?php echo TEXT_IMAGESLIDERS_MOBILE_IMAGE; ?></strong>
                      <?php echo xtc_draw_file_field('imagesliders_mobile_image' . $i); ?>
                      <div><?php echo xtc_info_image(xtc_get_imageslider_mobile_image($imageslider['imagesliders_id'], $languages[$i]['id']), $imageslider['imagesliders_name'], '', '', 'style="max-width:100%;height:auto;max-height:180px;"'); ?></div>
                      <label class="mits-subtle"><?php echo xtc_draw_selection_field('imagesliders_mobile_image_delete' . $i, 'checkbox', 'imagesliders_mobile_image' . $i) . ' ' . TEXT_HEADING_DELETE_IMAGESLIDER; ?></label>
                    </div>
                  </div>

                  <div class="mits-card" style="box-shadow:none;margin-bottom:14px;">
                    <div class="mits-card__body">
                      <div class="mits-link-grid">
                        <div class="mits-field">
                          <label><?php echo TEXT_TYP; ?></label>
                          <div class="mits-checks" style="display:block;line-height:1.9;">
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '0', $current_url_typ === 0) . ' ' . TYP_EXTERN; ?></label><br>
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '1', $current_url_typ === 1) . ' ' . TYP_INTERN; ?></label><br>
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '2', $current_url_typ === 2) . ' ' . TYP_PRODUCT; ?></label><br>
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '3', $current_url_typ === 3) . ' ' . TYP_CATEGORIE; ?></label><br>
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '4', $current_url_typ === 4) . ' ' . TYP_CONTENT; ?></label><br>
                            <label><?php echo xtc_draw_selection_field('imagesliders_url_typ[' . $languages[$i]['id'] . ']', 'radio', '5', $current_url_typ === 5) . ' ' . TYP_MANUFACTURER; ?></label>
                          </div>
                        </div>
                        <div class="mits-field">
                          <label><?php echo TEXT_URL; ?></label>
                          <?php echo xtc_draw_input_field('imagesliders_url[' . $languages[$i]['id'] . ']', $current_url); ?>
                        </div>
                        <div class="mits-field">
                          <label><?php echo TEXT_TARGET; ?></label>
                          <?php echo xtc_draw_pull_down_menu('imagesliders_url_target[' . $languages[$i]['id'] . ']', $url_target_array, $current_url_target); ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mits-form-grid">
                    <div class="mits-field">
                      <label><?php echo TEXT_IMAGESLIDERS_LINKTITLE; ?></label>
                      <?php echo xtc_draw_input_field('imagesliders_linktitle[' . $languages[$i]['id'] . ']', $current_linktitle); ?>
                    </div>
                    <div class="mits-field">
                      <label><?php echo TEXT_IMAGESLIDERS_ALT; ?></label>
                      <?php echo xtc_draw_input_field('imagesliders_alt[' . $languages[$i]['id'] . ']', $current_alt); ?>
                    </div>
                    <div class="mits-field">
                      <label><?php echo TEXT_IMAGESLIDERS_TITLE; ?></label>
                      <?php echo xtc_draw_input_field('imagesliders_title[' . $languages[$i]['id'] . ']', $current_title); ?>
                    </div>
                    <div class="mits-field is-full">
                      <label><?php echo TEXT_IMAGESLIDERS_DESCRIPTION; ?></label>
                      <?php echo xtc_draw_textarea_field('imagesliders_description[' . $languages[$i]['id'] . ']', 'soft', '70', '25', (isset($imagesliders_description[$languages[$i]['id']]) ? stripslashes($imagesliders_description[$languages[$i]['id']]) : xtc_get_imageslider_description($imageslider['imagesliders_id'], $languages[$i]['id']))); ?>
                    </div>
                  </div>
                  <?php
                  echo '</div>';
                }
                ?>
              </div>
            </div>

            <div class="mits-edit-actionbar">
              <div class="mits-edit-actionbar__title"><?php echo BUTTON_SAVE; ?><span><?php echo mits_imageslider_admin_text('HEADING_TITLE_IMAGESLIDERS', 'MITS ImageSlider'); ?></span></div>
              <div class="mits-edit-actionbar__actions">
                <button type="submit" class="mits-button mits-button--primary"><?php echo BUTTON_SAVE; ?></button>
                <button type="submit" name="save_and_stay" value="1" class="mits-button mits-button--soft"><?php echo mits_imageslider_admin_text('BUTTON_UPDATE', 'Aktualisieren'); ?></button>
                <a class="mits-button" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'page', 'action')) . (isset($_GET['page']) ? 'page=' . (int)$_GET['page'] . '&' : '') . (isset($_GET['groupfilter']) ? 'groupfilter=' . urlencode($_GET['groupfilter']) : '')); ?>"><?php echo BUTTON_CANCEL; ?></a>
              </div>
            </div>
            </form>
            <?php
          } elseif ($action == 'delete' && isset($_GET['iID'])) {
            $imageslider_query = xtc_db_query("SELECT * FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_id = " . (int)$_GET['iID']);
            $imageslider = xtc_db_fetch_array($imageslider_query);
            ?>
            <div class="mits-card">
              <div class="mits-card__header">
                <div>
                  <h2 class="mits-card__title"><?php echo TEXT_HEADING_DELETE_IMAGESLIDER; ?></h2>
                  <p class="mits-card__subtitle"><?php echo TEXT_DELETE_INTRO; ?></p>
                </div>
              </div>
              <div class="mits-card__body">
                <strong><?php echo mits_imageslider_admin_h($imageslider['imagesliders_name']); ?></strong>
                <div class="mits-delete-preview">
                  <?php
                  for ($i = 0; $i < sizeof($languages); $i++) {
                    echo '<div class="mits-preview">' . xtc_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/admin/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'class="language-flag"') . xtc_info_image(xtc_get_imageslider_image($imageslider['imagesliders_id'], $languages[$i]['id']), $imageslider['imagesliders_name'], '', '', 'style="max-width:100%;height:auto;max-height:110px;"') . '</div>';
                  }
                  ?>
                </div>
                <?php echo xtc_draw_form('imagesliders', FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('action')) . 'action=deleteconfirm'); ?>
                  <label><?php echo xtc_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE; ?></label>
                  <div class="mits-actions" style="justify-content:flex-start;margin-top:14px;">
                    <button type="submit" class="mits-button mits-button--danger"><?php echo BUTTON_DELETE; ?></button>
                    <a class="mits-button" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action'))); ?>"><?php echo BUTTON_CANCEL; ?></a>
                  </div>
                </form>
              </div>
            </div>
            <?php
          } else {
            $selected_group = (isset($_GET['groupfilter']) && $_GET['groupfilter'] != '') ? $_GET['groupfilter'] : '';
            $select_data = array(
              array('id' => '', 'text' => ($selected_group == '' ? TEXT_SELECT : CFG_TXT_ALL)),
            );
            $group_filter = ($selected_group != '') ? " WHERE imagesliders_group = '" . xtc_db_input($selected_group) . "'" : "";
            $imagesliders_query_raw = "SELECT * FROM " . TABLE_MITS_IMAGESLIDER . $group_filter . " ORDER BY imagesliders_group, sorting, imagesliders_id";
            $imagesliders_split = new splitPageResults($page, $page_max_display_results, $imagesliders_query_raw, $imagesliders_query_numrows);
            $imagesliders_query = xtc_db_query($imagesliders_query_raw);
            $imagesliders_rows = array();
            while ($imagesliders = xtc_db_fetch_array($imagesliders_query)) {
              $imagesliders_rows[] = $imagesliders;
            }
            ?>
            <div class="mits-card">
              <div class="mits-card__header">
                <div>
                  <h2 class="mits-card__title"><?php echo mits_imageslider_admin_text('HEADING_TITLE_IMAGESLIDERS', 'Sliderbilder'); ?></h2>
                  <p class="mits-card__subtitle"><?php echo mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_PAGE_NOTE', 'Reihenfolge per Drag-&amp;-Drop innerhalb der aktuellen Seite &auml;ndern. Die Sortierung wird beim Loslassen automatisch je Slidergruppe gespeichert; Eintr&auml;ge k&ouml;nnen nicht zwischen Gruppen verschoben werden.'); ?></p>
                </div>
                <div class="mits-admin__hero-actions">
                  <form method="get" action="<?php echo FILENAME_MITS_IMAGESLIDER; ?>">
                    <label class="mits-subtle"><?php echo TABLE_HEADING_SLIDERGROUP; ?></label>
                    <?php echo xtc_draw_pull_down_menu('groupfilter', array_merge($select_data, $slidergroups_array), $selected_group, 'onChange="this.form.submit();"'); ?>
                  </form>
                </div>
              </div>
              <div class="mits-card__body">
                <?php if (count($imagesliders_rows) === 0) { ?>
                  <div class="mits-empty">Es sind noch keine Sliderbilder vorhanden.</div>
                <?php } else { ?>
                  <form method="post" action="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('action', 'iID', 'flag')) . 'action=save_sorting'); ?>" id="mits-imageslider-sort-form">
                    <div class="mits-toolbar">
                      <span class="mits-subtle" id="mits-sort-hint"><?php echo mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_HINT', 'Ziehen Sie die Zeilen am Griffsymbol an die gew&uuml;nschte Position auf dieser Seite. Die Sortierung wird automatisch gespeichert.'); ?></span>
                    </div>
                    <div class="mits-table-wrap">
                      <table class="mits-table">
                        <thead>
                          <tr>
                            <th>&nbsp;</th>
                            <th><?php echo TABLE_HEADING_SORTING; ?></th>
                            <th><?php echo TABLE_HEADING_IMAGESLIDERS_IMAGE; ?></th>
                            <th><?php echo TABLE_HEADING_IMAGESLIDERS_NAME; ?></th>
                            <th><?php echo TABLE_HEADING_SLIDERGROUP; ?></th>
                            <th><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th><?php echo TEXT_IMAGESLIDERS_SCHEDULED_AT; ?></th>
                            <th><?php echo TEXT_IMAGESLIDERS_EXPIRES_ON; ?></th>
                            <th style="text-align:right;"><?php echo TABLE_HEADING_ACTION; ?></th>
                          </tr>
                        </thead>
                        <tbody id="mits-imageslider-sortable">
                        <?php foreach ($imagesliders_rows as $imagesliders) { ?>
                          <tr class="mits-slider-row" draggable="true" data-slider-id="<?php echo (int)$imagesliders['imagesliders_id']; ?>" data-slider-group="<?php echo mits_imageslider_admin_h($imagesliders['imagesliders_group']); ?>">
                            <td>
                              <span class="mits-drag-handle" title="Ziehen">&#9776;</span>
                              <input type="hidden" name="imagesliders_order[]" value="<?php echo (int)$imagesliders['imagesliders_id']; ?>">
                              <input type="hidden" name="imagesliders_order_group[<?php echo (int)$imagesliders['imagesliders_id']; ?>]" value="<?php echo mits_imageslider_admin_h($imagesliders['imagesliders_group']); ?>">
                            </td>
                            <td><span class="mits-order-index"><?php echo (int)$imagesliders['sorting']; ?></span></td>
                            <td>
                              <div class="mits-preview-list">
                                <?php
                                for ($i = 0; $i < sizeof($languages); $i++) {
                                  $image_src = xtc_get_imageslider_image($imagesliders['imagesliders_id'], $languages[$i]['id']);
                                  if ($image_src != '') {
                                    echo '<div class="mits-preview">' . xtc_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/admin/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'class="language-flag"') . xtc_info_image($image_src, $imagesliders['imagesliders_name'], '', '', 'style="max-width:92px;height:auto;max-height:46px;"') . '</div>';
                                  }
                                }
                                ?>
                              </div>
                            </td>
                            <td><strong><?php echo mits_imageslider_admin_h($imagesliders['imagesliders_name']); ?></strong><br><span class="mits-subtle">ID <?php echo (int)$imagesliders['imagesliders_id']; ?></span></td>
                            <td><span class="mits-badge"><?php echo mits_imageslider_admin_h(strtoupper($imagesliders['imagesliders_group'])); ?></span></td>
                            <td>
                              <div class="mits-status-cell">
                                <?php if ((int)$imagesliders['status'] == 0) { ?>
                                  <span class="mits-icon-action mits-icon-action--status is-active is-current" title="<?php echo mits_imageslider_admin_text('MITS_ACTIVE', 'aktiv'); ?>" aria-label="<?php echo mits_imageslider_admin_text('MITS_ACTIVE', 'aktiv'); ?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 6L9 17l-5-5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                  </span>
                                  <form class="mits-status-form" method="post" action="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action', 'flag')) . 'action=setflag'); ?>">
                                    <input type="hidden" name="iID" value="<?php echo (int)$imagesliders['imagesliders_id']; ?>">
                                    <input type="hidden" name="flag" value="1">
                                    <button type="submit" class="mits-icon-action mits-icon-action--status is-inactive is-muted" title="<?php echo mits_imageslider_admin_text('MITS_NOTACTIVE', 'deaktivieren'); ?>" aria-label="<?php echo mits_imageslider_admin_text('MITS_NOTACTIVE', 'deaktivieren'); ?>">
                                      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18 6L6 18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </button>
                                  </form>
                                <?php } else { ?>
                                  <form class="mits-status-form" method="post" action="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action', 'flag')) . 'action=setflag'); ?>">
                                    <input type="hidden" name="iID" value="<?php echo (int)$imagesliders['imagesliders_id']; ?>">
                                    <input type="hidden" name="flag" value="0">
                                    <button type="submit" class="mits-icon-action mits-icon-action--status is-active is-muted" title="<?php echo mits_imageslider_admin_text('MITS_ACTIVE', 'aktivieren'); ?>" aria-label="<?php echo mits_imageslider_admin_text('MITS_ACTIVE', 'aktivieren'); ?>">
                                      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 6L9 17l-5-5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </button>
                                  </form>
                                  <span class="mits-icon-action mits-icon-action--status is-inactive is-current" title="<?php echo mits_imageslider_admin_text('MITS_NOTACTIVE', 'inaktiv'); ?>" aria-label="<?php echo mits_imageslider_admin_text('MITS_NOTACTIVE', 'inaktiv'); ?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18 6L6 18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                  </span>
                                <?php } ?>
                              </div>
                            </td>
                            <td><?php echo mits_imageslider_admin_date($imagesliders['date_scheduled']); ?></td>
                            <td><?php echo mits_imageslider_admin_date($imagesliders['expires_date']); ?></td>
                            <td>
                              <div class="mits-actions">
                                <a class="mits-button mits-button--soft" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action')) . 'iID=' . (int)$imagesliders['imagesliders_id'] . '&action=edit'); ?>"><?php echo BUTTON_EDIT; ?></a>
                                <a class="mits-button mits-button--danger" href="<?php echo xtc_href_link(FILENAME_MITS_IMAGESLIDER, xtc_get_all_get_params(array('iID', 'action')) . 'iID=' . (int)$imagesliders['imagesliders_id'] . '&action=delete'); ?>"><?php echo BUTTON_DELETE; ?></a>
                              </div>
                            </td>
                          </tr>
                        <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </form>
                  <div class="mits-pagination">
                    <div class="mits-pagination__count"><?php echo $imagesliders_split->display_count($imagesliders_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS); ?></div>
                    <div class="mits-pagination__links"><?php echo $imagesliders_split->display_links($imagesliders_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page); ?></div>
                    <div class="mits-pagination__per-page"><?php echo draw_input_per_page($PHP_SELF, $cfg_max_display_results_key, $page_max_display_results); ?></div>
                  </div>
                <?php } ?>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </td>
    </tr>
  </table>
  <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('mits-imageslider-sortable');
    var form = document.getElementById('mits-imageslider-sort-form');
    var hint = document.getElementById('mits-sort-hint');
    if (!container || !form) return;

    var dragged = null;
    var isSaving = false;
    var pendingSave = false;

    function setHint(message, state) {
      if (!hint) return;
      hint.innerHTML = message;
      hint.classList.remove('is-saving', 'is-success', 'is-error');
      if (state) {
        hint.classList.add(state);
      }
    }

    function getRowGroup(row) {
      return row && row.getAttribute('data-slider-group') ? row.getAttribute('data-slider-group') : 'mits_imageslider';
    }

    function refreshSorting() {
      var counters = {};
      var rows = container.querySelectorAll('.mits-slider-row');
      rows.forEach(function (row) {
        var group = getRowGroup(row);
        if (!counters[group]) counters[group] = 0;
        counters[group]++;
        var index = row.querySelector('.mits-order-index');
        if (index) index.textContent = counters[group];
        row.classList.add('is-dirty');
      });
    }

    function markSaved() {
      var rows = container.querySelectorAll('.mits-slider-row');
      rows.forEach(function (row) {
        row.classList.remove('is-dirty');
      });
    }

    function saveSorting() {
      if (isSaving) {
        pendingSave = true;
        return;
      }

      isSaving = true;
      pendingSave = false;
      setHint(<?php echo json_encode(mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_SAVING', 'Sortierung wird gespeichert...')); ?>, 'is-saving');

      var request = new XMLHttpRequest();
      var data = new FormData(form);
      data.append('ajax', '1');

      request.open('POST', form.getAttribute('action'), true);
      request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      request.onreadystatechange = function () {
        if (request.readyState !== 4) {
          return;
        }

        isSaving = false;

        if (request.status >= 200 && request.status < 300) {
          var response = null;
          try {
            response = JSON.parse(request.responseText);
          } catch (e) {}

          if (response && response.success) {
            markSaved();
            setHint(<?php echo json_encode(mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_SAVED', 'Sortierung gespeichert.')); ?>, 'is-success');
          } else {
            setHint(<?php echo json_encode(mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_ERROR', 'Sortierung konnte nicht gespeichert werden. Bitte Seite neu laden und erneut versuchen.')); ?>, 'is-error');
          }
        } else {
          setHint(<?php echo json_encode(mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_ERROR', 'Sortierung konnte nicht gespeichert werden. Bitte Seite neu laden und erneut versuchen.')); ?>, 'is-error');
        }

        if (pendingSave) {
          saveSorting();
        }
      };

      request.send(data);
    }

    function bindRow(row) {
      row.addEventListener('dragstart', function () {
        dragged = row;
        row.classList.add('is-dragging');
      });
      row.addEventListener('dragend', function () {
        row.classList.remove('is-dragging');
      });
      row.addEventListener('dragover', function (event) {
        event.preventDefault();
      });
      row.addEventListener('drop', function (event) {
        event.preventDefault();
        if (!dragged || dragged === row) return;
        if (getRowGroup(dragged) !== getRowGroup(row)) {
          setHint(<?php echo json_encode(mits_imageslider_admin_text('TEXT_IMAGESLIDER_SORT_SAME_GROUP_ONLY', 'Eintr&auml;ge k&ouml;nnen nur innerhalb derselben Slidergruppe sortiert werden.')); ?>, 'is-error');
          return;
        }
        var rows = Array.prototype.slice.call(container.querySelectorAll('.mits-slider-row'));
        var draggedIndex = rows.indexOf(dragged);
        var targetIndex = rows.indexOf(row);
        if (draggedIndex < targetIndex) {
          row.after(dragged);
        } else {
          row.before(dragged);
        }
        refreshSorting();
        saveSorting();
      });
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      refreshSorting();
      saveSorting();
    });

    var rows = container.querySelectorAll('.mits-slider-row');
    rows.forEach(bindRow);
  });
  </script>
  <?php require_once(DIR_WS_INCLUDES . 'footer.php'); ?>
  <br />
  </body></html>
<?php
}
require_once(DIR_WS_INCLUDES . 'application_bottom.php'); ?>