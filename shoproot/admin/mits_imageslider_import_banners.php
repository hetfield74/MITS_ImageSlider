<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider_import_banners.php
 * Date: 06.05.2026
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

require_once('includes/application_top.php');

defined('TABLE_BANNERS') or define('TABLE_BANNERS', 'banners');
defined('TABLE_MITS_IMAGESLIDER_IMPORT_MAP') or define('TABLE_MITS_IMAGESLIDER_IMPORT_MAP', 'mits_imageslider_import_map');

if (defined('DIR_FS_EXTERNAL') && is_file(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php')) {
    require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php');
}

function mits_imageslider_import_t($constant, $fallback)
{
    return defined($constant) ? constant($constant) : $fallback;
}

function mits_imageslider_import_h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, $_SESSION['language_charset'] ?? 'UTF-8');
}

function mits_imageslider_import_table_exists($table)
{
    $q = xtc_db_query("SHOW TABLES LIKE '" . xtc_db_input($table) . "'");
    return xtc_db_num_rows($q) > 0;
}

function mits_imageslider_import_column_exists($table, $column)
{
    $q = xtc_db_query("SHOW COLUMNS FROM " . $table . " LIKE '" . xtc_db_input($column) . "'");
    return xtc_db_num_rows($q) > 0;
}

function mits_imageslider_import_create_map_table()
{
    xtc_db_query(
      "CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER_IMPORT_MAP . " (
        `source` VARCHAR(32) NOT NULL,
        `source_id` INT(11) NOT NULL,
        `imagesliders_id` INT(11) NOT NULL,
        `date_imported` DATETIME DEFAULT NULL,
        PRIMARY KEY (`source`, `source_id`),
        KEY `idx_mits_imageslider_import_map_slider` (`imagesliders_id`)
      )"
    );
}

function mits_imageslider_import_images_root()
{
    if (defined('DIR_FS_CATALOG_IMAGES')) {
        return rtrim(DIR_FS_CATALOG_IMAGES, '/\\') . '/';
    }
    if (defined('DIR_FS_CATALOG') && defined('DIR_WS_IMAGES')) {
        return rtrim(DIR_FS_CATALOG, '/\\') . '/' . trim(DIR_WS_IMAGES, '/\\') . '/';
    }
    return '';
}

function mits_imageslider_import_normalize_relative_image($image)
{
    $image = str_replace('\\', '/', trim((string)$image));
    if ($image === '' || preg_match('#^https?://#i', $image) || strpos($image, '//') === 0) {
        return '';
    }
    $image = ltrim($image, '/');
    if (strpos($image, 'images/') === 0) {
        $image = substr($image, 7);
    }
    return $image;
}

function mits_imageslider_import_find_banner_image($image)
{
    $root = mits_imageslider_import_images_root();
    if ($root === '') {
        return '';
    }

    $image = mits_imageslider_import_normalize_relative_image($image);
    if ($image === '') {
        return '';
    }

    $candidates = array($image);
    if (strpos($image, 'banner/') !== 0) {
        $candidates[] = 'banner/' . $image;
    }
    if (strpos($image, 'banners/') !== 0) {
        $candidates[] = 'banners/' . $image;
    }

    $seen = array();
    foreach ($candidates as $rel) {
        $rel = ltrim(str_replace('\\', '/', $rel), '/');
        if ($rel === '' || isset($seen[$rel])) {
            continue;
        }
        $seen[$rel] = true;
        if (is_file($root . $rel)) {
            return $rel;
        }
    }

    return '';
}

function mits_imageslider_import_safe_filename($filename)
{
    $filename = basename(str_replace('\\', '/', (string)$filename));
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = preg_replace('/[^A-Za-z0-9._-]+/', '_', $name);
    $name = trim($name, '._-');
    if ($name === '') {
        $name = 'banner';
    }
    $ext = preg_replace('/[^A-Za-z0-9]+/', '', $ext);
    return $name . ($ext !== '' ? '.' . $ext : '');
}

function mits_imageslider_import_language_directory($language)
{
    $directory = isset($language['directory']) ? (string)$language['directory'] : '';
    $directory = trim(str_replace('\\', '/', $directory), '/');
    $directory = preg_replace('/[^A-Za-z0-9._-]+/', '_', $directory);
    if ($directory === '') {
        $directory = 'language_' . (int)$language['id'];
    }
    return $directory;
}

function mits_imageslider_import_existing_lang_images($imagesliders_id, $language_id)
{
    $empty = array('desktop' => '', 'tablet' => '', 'mobile' => '');
    if ((int)$imagesliders_id <= 0 || (int)$language_id <= 0) {
        return $empty;
    }
    $q = xtc_db_query(
      "SELECT imagesliders_image, imagesliders_tablet_image, imagesliders_mobile_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imagesliders_id . " AND languages_id = " . (int)$language_id
    );
    if (xtc_db_num_rows($q)) {
        $row = xtc_db_fetch_array($q);
        return array(
          'desktop' => isset($row['imagesliders_image']) ? (string)$row['imagesliders_image'] : '',
          'tablet'  => isset($row['imagesliders_tablet_image']) ? (string)$row['imagesliders_tablet_image'] : '',
          'mobile'  => isset($row['imagesliders_mobile_image']) ? (string)$row['imagesliders_mobile_image'] : '',
        );
    }
    return $empty;
}

function mits_imageslider_import_delete_old_import_image($banner_id, $language, $old_rel)
{
    $old_rel = ltrim(str_replace('\\', '/', (string)$old_rel), '/');
    if ($old_rel === '') {
        return;
    }

    $language_dir = mits_imageslider_import_language_directory($language);
    $prefixes = array(
      'imagesliders/' . $language_dir . '/banner_' . (int)$banner_id . '_',
      'imagesliders/' . $language_dir . '/tablet/banner_' . (int)$banner_id . '_',
      'imagesliders/' . $language_dir . '/mobile/banner_' . (int)$banner_id . '_',
    );
    $is_import_file = false;
    foreach ($prefixes as $prefix) {
        if (strpos($old_rel, $prefix) === 0) {
            $is_import_file = true;
            break;
        }
    }
    if (!$is_import_file) {
        return;
    }

    if (function_exists('mits_imageslider_delete_variants_from_relative')) {
        mits_imageslider_delete_variants_from_relative($old_rel);
    } else {
        $root = mits_imageslider_import_images_root();
        if ($root !== '' && is_file($root . $old_rel)) {
            @unlink($root . $old_rel);
        }
    }
}

function mits_imageslider_import_generate_banner_image($banner_id, $source_rel, $language, $type = 'desktop')
{
    $root = mits_imageslider_import_images_root();
    if ($root === '' || $source_rel === '' || !is_file($root . $source_rel)) {
        return array('image' => '', 'width' => 0, 'height' => 0);
    }

    $type = in_array($type, array('desktop', 'tablet', 'mobile')) ? $type : 'desktop';
    $language_dir = mits_imageslider_import_language_directory($language);
    $dest_dir_rel = 'imagesliders/' . $language_dir . '/';
    if ($type === 'tablet') {
        $dest_dir_rel .= 'tablet/';
    } elseif ($type === 'mobile') {
        $dest_dir_rel .= 'mobile/';
    }
    $dest_dir_abs = $root . $dest_dir_rel;
    if (!is_dir($dest_dir_abs)) {
        @mkdir($dest_dir_abs, 0775, true);
    }

    $safe = mits_imageslider_import_safe_filename($source_rel);
    $dest_rel = $dest_dir_rel . 'banner_' . (int)$banner_id . '_' . $safe;
    $dest_abs = $root . $dest_rel;

    if (!@copy($root . $source_rel, $dest_abs)) {
        return array('image' => '', 'width' => 0, 'height' => 0);
    }
    @chmod($dest_abs, 0644);

    if (function_exists('mits_imageslider_generate_variants_from_relative')) {
        $dest_rel = mits_imageslider_generate_variants_from_relative($dest_rel, $type);
    }

    $width = $height = 0;
    if (is_file($root . $dest_rel)) {
        $size = @getimagesize($root . $dest_rel);
        if (is_array($size)) {
            $width = (float)$size[0];
            $height = (float)$size[1];
        }
    }

    return array('image' => (is_file($root . $dest_rel) ? $dest_rel : ''), 'width' => $width, 'height' => $height);
}

function mits_imageslider_import_first_existing_column($table, $candidates)
{
    foreach ($candidates as $candidate) {
        if (mits_imageslider_import_column_exists($table, $candidate)) {
            return $candidate;
        }
    }
    return '';
}

function mits_imageslider_import_detect_image_columns()
{
    return array(
      'desktop' => mits_imageslider_import_first_existing_column(TABLE_BANNERS, array('banners_image', 'banners_image_desktop', 'banner_image', 'banner_image_desktop', 'desktop_image', 'image_desktop', 'banners_desktop_image')),
      'tablet'  => mits_imageslider_import_first_existing_column(TABLE_BANNERS, array('banners_tablet_image', 'banners_image_tablet', 'banner_tablet_image', 'banner_image_tablet', 'tablet_image', 'image_tablet', 'banners_image_medium', 'banners_medium_image')),
      'mobile'  => mits_imageslider_import_first_existing_column(TABLE_BANNERS, array('banners_mobile_image', 'banners_image_mobile', 'banner_mobile_image', 'banner_image_mobile', 'mobile_image', 'image_mobile', 'banners_image_mobile_sm', 'banners_image_sm', 'banners_image_small', 'banners_small_image', 'banners_mobile')),
    );
}

function mits_imageslider_import_find_responsive_banner_image($source_rel, $type)
{
    $root = mits_imageslider_import_images_root();
    $source_rel = ltrim(str_replace('\\', '/', (string)$source_rel), '/');
    if ($root === '' || $source_rel === '' || !in_array($type, array('tablet', 'mobile'))) {
        return '';
    }

    $dir = trim(dirname($source_rel), '.');
    $dir = ($dir === '' ? '' : rtrim($dir, '/') . '/');
    $filename = basename($source_rel);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $suffixes = ($type === 'mobile')
      ? array('mobile', 'mobile_images', 'mobile_image', 'smartphone', 'phone')
      : array('tablet', 'tablet_images', 'tablet_image');

    $candidates = array();
    foreach ($suffixes as $suffix) {
        $candidates[] = $dir . $suffix . '/' . $filename;
        $candidates[] = $dir . $suffix . '_' . $filename;
        $candidates[] = $dir . $name . '_' . $suffix . ($ext !== '' ? '.' . $ext : '');
        $candidates[] = $dir . $name . '-' . $suffix . ($ext !== '' ? '.' . $ext : '');
    }

    $seen = array();
    foreach ($candidates as $rel) {
        $rel = ltrim(str_replace('\\', '/', $rel), '/');
        if ($rel === '' || isset($seen[$rel])) {
            continue;
        }
        $seen[$rel] = true;
        if (is_file($root . $rel)) {
            return $rel;
        }
    }
    return '';
}

function mits_imageslider_import_banner_image_source($banner, $image_columns, $type, $desktop_rel = '')
{
    $column = isset($image_columns[$type]) ? $image_columns[$type] : '';
    if ($column !== '' && isset($banner[$column]) && trim((string)$banner[$column]) !== '') {
        return mits_imageslider_import_find_banner_image($banner[$column]);
    }

    if (($type === 'tablet' || $type === 'mobile') && $desktop_rel !== '') {
        return mits_imageslider_import_find_responsive_banner_image($desktop_rel, $type);
    }

    return '';
}

function mits_imageslider_import_datetime($value, $end_of_day = false)
{
    $value = trim((string)$value);
    if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
        return 'null';
    }
    $ts = strtotime($value);
    if ($ts === false) {
        return 'null';
    }
    return date($end_of_day ? 'Y-m-d 23:59:59' : 'Y-m-d 00:00:00', $ts);
}

function mits_imageslider_import_field($row, $field, $default = '')
{
    return isset($row[$field]) ? $row[$field] : $default;
}

function mits_imageslider_import_url_type($url)
{
    $url = trim((string)$url);
    if ($url === '') {
        return 0;
    }
    return preg_match('#^(https?:)?//#i', $url) ? 0 : 1;
}

function mits_imageslider_import_existing_slider_id($banner_id)
{
    if (!mits_imageslider_import_table_exists(TABLE_MITS_IMAGESLIDER_IMPORT_MAP)) {
        return 0;
    }
    $q = xtc_db_query("SELECT imagesliders_id FROM " . TABLE_MITS_IMAGESLIDER_IMPORT_MAP . " WHERE source = 'banner_manager' AND source_id = " . (int)$banner_id);
    if (xtc_db_num_rows($q)) {
        $row = xtc_db_fetch_array($q);
        return (int)$row['imagesliders_id'];
    }
    return 0;
}

function mits_imageslider_import_slider_exists($imagesliders_id)
{
    if ((int)$imagesliders_id <= 0) {
        return false;
    }
    $q = xtc_db_query("SELECT imagesliders_id FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_id = " . (int)$imagesliders_id);
    return xtc_db_num_rows($q) > 0;
}

function mits_imageslider_import_upsert_info($imagesliders_id, $language_id, $data)
{
    $q = xtc_db_query(
      "SELECT imagesliders_id FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imagesliders_id . " AND languages_id = " . (int)$language_id
    );
    if (xtc_db_num_rows($q)) {
        xtc_db_perform(TABLE_MITS_IMAGESLIDER_INFO, $data, 'update', "imagesliders_id = " . (int)$imagesliders_id . " AND languages_id = " . (int)$language_id);
    } else {
        $data['imagesliders_id'] = (int)$imagesliders_id;
        $data['languages_id'] = (int)$language_id;
        xtc_db_perform(TABLE_MITS_IMAGESLIDER_INFO, $data);
    }
}

function mits_imageslider_import_process_banner($banner, $execute, $overwrite, $languages, $columns)
{
    $id_col = $columns['id'];
    $banner_id = (int)$banner[$id_col];
    $title = trim((string)mits_imageslider_import_field($banner, 'banners_title', ''));
    if ($title === '') {
        $title = 'Banner #' . $banner_id;
    }

    $group = strtolower(trim((string)mits_imageslider_import_field($banner, 'banners_group', '')));
    if ($group === '') {
        $group = 'mits_imageslider';
    }

    $image_columns = isset($columns['images']) && is_array($columns['images']) ? $columns['images'] : mits_imageslider_import_detect_image_columns();
    $source_image = ($image_columns['desktop'] !== '' && isset($banner[$image_columns['desktop']])) ? $banner[$image_columns['desktop']] : mits_imageslider_import_field($banner, 'banners_image', '');
    $source_rel = mits_imageslider_import_find_banner_image($source_image);
    if ($source_rel === '') {
        return array('status' => 'error', 'banner_id' => $banner_id, 'title' => $title, 'message' => 'Desktop-Bild nicht gefunden: ' . $source_image);
    }

    $tablet_rel = mits_imageslider_import_banner_image_source($banner, $image_columns, 'tablet', $source_rel);
    $mobile_rel = mits_imageslider_import_banner_image_source($banner, $image_columns, 'mobile', $source_rel);

    $existing_id = mits_imageslider_import_existing_slider_id($banner_id);
    $existing_is_valid = mits_imageslider_import_slider_exists($existing_id);
    if ($existing_id > 0 && !$overwrite && $existing_is_valid) {
        return array('status' => 'skipped', 'banner_id' => $banner_id, 'title' => $title, 'message' => 'bereits importiert');
    }

    if (!$execute) {
        $language_dirs = array();
        foreach ($languages as $language) {
            $language_dirs[] = 'imagesliders/' . mits_imageslider_import_language_directory($language) . '/';
        }
        $language_dirs = array_unique($language_dirs);
        $parts = array('Desktop: ' . $source_rel);
        if ($tablet_rel !== '') {
            $parts[] = 'Tablet: ' . $tablet_rel;
        }
        if ($mobile_rel !== '') {
            $parts[] = 'Mobile: ' . $mobile_rel;
        }
        return array(
          'status' => ($existing_id > 0 && $existing_is_valid ? 'would_update' : 'would_import'),
          'banner_id' => $banner_id,
          'title' => $title,
          'message' => $group . ' / ' . implode(' / ', $parts) . ' -> ' . implode(', ', $language_dirs) . ' + Varianten'
        );
    }

    $status_col = $columns['status'];
    $banner_status = ($status_col !== '' && isset($banner[$status_col])) ? (int)$banner[$status_col] : 1;
    $imageslider_status = ($banner_status === 1) ? 0 : 1;

    $sorting_col = $columns['sorting'];
    $sorting = ($sorting_col !== '' && isset($banner[$sorting_col])) ? (int)$banner[$sorting_col] : 0;

    $date_scheduled = mits_imageslider_import_datetime(mits_imageslider_import_field($banner, 'date_scheduled', ''), false);
    $expires_date = mits_imageslider_import_datetime(mits_imageslider_import_field($banner, 'expires_date', ''), true);

    $main_data = array(
      'imagesliders_name'  => xtc_db_prepare_input($title),
      'date_scheduled'     => $date_scheduled,
      'expires_date'       => $expires_date,
      'recurring'          => 0,
      'recurring_start_md' => 'null',
      'recurring_end_md'   => 'null',
      'last_modified'      => 'now()',
      'status'             => $imageslider_status,
      'sorting'            => $sorting,
      'imagesliders_group' => xtc_db_prepare_input($group),
    );

    if ($existing_id > 0 && $existing_is_valid) {
        xtc_db_perform(TABLE_MITS_IMAGESLIDER, $main_data, 'update', "imagesliders_id = " . (int)$existing_id);
        $imagesliders_id = (int)$existing_id;
        $status = 'updated';
    } else {
        $main_data['date_added'] = 'now()';
        unset($main_data['last_modified']);
        xtc_db_perform(TABLE_MITS_IMAGESLIDER, $main_data);
        $imagesliders_id = xtc_db_insert_id();
        xtc_db_query(
          "REPLACE INTO " . TABLE_MITS_IMAGESLIDER_IMPORT_MAP . " (`source`, `source_id`, `imagesliders_id`, `date_imported`) VALUES ('banner_manager', " . (int)$banner_id . ", " . (int)$imagesliders_id . ", now())"
        );
        $status = 'imported';
    }

    $url = trim((string)mits_imageslider_import_field($banner, 'banners_url', ''));
    $description = (string)mits_imageslider_import_field($banner, 'banners_html_text', '');

    $generated_images = array();
    foreach ($languages as $language) {
        $language_id = (int)$language['id'];
        $old_images = ($existing_id > 0 && $existing_is_valid) ? mits_imageslider_import_existing_lang_images($existing_id, $language_id) : array('desktop' => '', 'tablet' => '', 'mobile' => '');

        $generated = mits_imageslider_import_generate_banner_image($banner_id, $source_rel, $language, 'desktop');
        if ($generated['image'] === '') {
            return array('status' => 'error', 'banner_id' => $banner_id, 'title' => $title, 'message' => 'Desktop-Bild konnte nicht generiert werden: ' . $source_rel . ' / Sprache ' . $language_id);
        }
        if ($old_images['desktop'] !== '' && $old_images['desktop'] !== $generated['image']) {
            mits_imageslider_import_delete_old_import_image($banner_id, $language, $old_images['desktop']);
        }

        $generated_tablet = array('image' => '', 'width' => 0, 'height' => 0);
        if ($tablet_rel !== '') {
            $generated_tablet = mits_imageslider_import_generate_banner_image($banner_id, $tablet_rel, $language, 'tablet');
            if ($generated_tablet['image'] === '') {
                return array('status' => 'error', 'banner_id' => $banner_id, 'title' => $title, 'message' => 'Tablet-Bild konnte nicht generiert werden: ' . $tablet_rel . ' / Sprache ' . $language_id);
            }
        }
        if ($old_images['tablet'] !== '' && $old_images['tablet'] !== $generated_tablet['image']) {
            mits_imageslider_import_delete_old_import_image($banner_id, $language, $old_images['tablet']);
        }

        $generated_mobile = array('image' => '', 'width' => 0, 'height' => 0);
        if ($mobile_rel !== '') {
            $generated_mobile = mits_imageslider_import_generate_banner_image($banner_id, $mobile_rel, $language, 'mobile');
            if ($generated_mobile['image'] === '') {
                return array('status' => 'error', 'banner_id' => $banner_id, 'title' => $title, 'message' => 'Mobile-Bild konnte nicht generiert werden: ' . $mobile_rel . ' / Sprache ' . $language_id);
            }
        }
        if ($old_images['mobile'] !== '' && $old_images['mobile'] !== $generated_mobile['image']) {
            mits_imageslider_import_delete_old_import_image($banner_id, $language, $old_images['mobile']);
        }

        $generated_images[] = 'D:' . $generated['image'] . ($generated_tablet['image'] !== '' ? ' T:' . $generated_tablet['image'] : '') . ($generated_mobile['image'] !== '' ? ' M:' . $generated_mobile['image'] : '');

        $lang_data = array(
          'imagesliders_title'               => xtc_db_prepare_input($title),
          'imagesliders_alt'                 => xtc_db_prepare_input($title),
          'imagesliders_linktitle'           => xtc_db_prepare_input($title),
          'imagesliders_url'                 => xtc_db_prepare_input($url),
          'imagesliders_url_target'          => 0,
          'imagesliders_url_typ'             => mits_imageslider_import_url_type($url),
          'imagesliders_description'         => xtc_db_prepare_input($description),
          'imagesliders_image'               => $generated['image'],
          'imagesliders_image_width'         => $generated['width'],
          'imagesliders_image_height'        => $generated['height'],
          'imagesliders_tablet_image'        => $generated_tablet['image'],
          'imagesliders_tablet_image_width'  => $generated_tablet['width'],
          'imagesliders_tablet_image_height' => $generated_tablet['height'],
          'imagesliders_mobile_image'        => $generated_mobile['image'],
          'imagesliders_mobile_image_width'  => $generated_mobile['width'],
          'imagesliders_mobile_image_height' => $generated_mobile['height'],
        );
        mits_imageslider_import_upsert_info($imagesliders_id, $language_id, $lang_data);
    }

    return array('status' => $status, 'banner_id' => $banner_id, 'title' => $title, 'message' => $group . ' / ' . implode(', ', $generated_images));
}

$execute = (isset($_GET['import']) && $_GET['import'] == '1');
$run_import_check = ($execute || isset($_GET['dry_run']));
$overwrite = (isset($_GET['overwrite']) && $_GET['overwrite'] == '1');
$group_filter = isset($_GET['groupfilter']) ? xtc_db_prepare_input($_GET['groupfilter']) : '';
$results = array();
$summary = array('total' => 0, 'imported' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0);
$groups = array();
$table_ok = mits_imageslider_import_table_exists(TABLE_BANNERS);

if ($table_ok) {
    if (mits_imageslider_import_column_exists(TABLE_BANNERS, 'banners_group')) {
        $groups_query = xtc_db_query("SELECT DISTINCT banners_group FROM " . TABLE_BANNERS . " WHERE banners_group != '' ORDER BY banners_group");
        while ($group = xtc_db_fetch_array($groups_query)) {
            $groups[] = $group['banners_group'];
        }
    }

    if ($execute) {
        mits_imageslider_import_create_map_table();
    }

    $id_col = mits_imageslider_import_column_exists(TABLE_BANNERS, 'banners_id') ? 'banners_id' : 'banner_id';
    $status_col = mits_imageslider_import_column_exists(TABLE_BANNERS, 'status') ? 'status' : (mits_imageslider_import_column_exists(TABLE_BANNERS, 'banner_status') ? 'banner_status' : '');
    $sorting_col = mits_imageslider_import_column_exists(TABLE_BANNERS, 'sort_order') ? 'sort_order' : (mits_imageslider_import_column_exists(TABLE_BANNERS, 'banners_sort_order') ? 'banners_sort_order' : '');
    $columns = array('id' => $id_col, 'status' => $status_col, 'sorting' => $sorting_col, 'images' => mits_imageslider_import_detect_image_columns());

    if ($run_import_check && mits_imageslider_import_column_exists(TABLE_BANNERS, $id_col)) {
        $where = '';
        if ($group_filter !== '' && mits_imageslider_import_column_exists(TABLE_BANNERS, 'banners_group')) {
            $where = " WHERE banners_group = '" . xtc_db_input($group_filter) . "'";
        }
        $order = (mits_imageslider_import_column_exists(TABLE_BANNERS, 'banners_group') ? 'banners_group, ' : '') . ($sorting_col !== '' ? $sorting_col . ', ' : '') . $id_col;
        $banners_query = xtc_db_query("SELECT * FROM " . TABLE_BANNERS . $where . " ORDER BY " . $order);
        $languages = xtc_get_languages();
        while ($banner = xtc_db_fetch_array($banners_query)) {
            $summary['total']++;
            $result = mits_imageslider_import_process_banner($banner, $execute, $overwrite, $languages, $columns);
            $results[] = $result;
            switch ($result['status']) {
                case 'imported':
                    $summary['imported']++;
                    break;
                case 'updated':
                    $summary['updated']++;
                    break;
                case 'error':
                    $summary['errors']++;
                    break;
                default:
                    $summary['skipped']++;
                    break;
            }
        }
    }
}

require_once(DIR_WS_INCLUDES . 'head.php');
?>
<style>
  .mits-import-box { margin: 10px 0 20px; padding: 12px; background: #fff; border: 1px solid #ddd; }
  .mits-import-box table { width: 100%; border-collapse: collapse; }
  .mits-import-box th, .mits-import-box td { padding: 6px; border-bottom: 1px solid #eee; text-align: left; }
  .mits-import-ok { color: #060; font-weight: bold; }
  .mits-import-warn { color: #a60; font-weight: bold; }
  .mits-import-error { color: #900; font-weight: bold; }
</style>
</head>
<body>
<?php require_once(DIR_WS_INCLUDES . 'header.php'); ?>
<table class="tableBody">
  <tr>
    <?php
    if (USE_ADMIN_TOP_MENU == 'false') {
        echo '<td class="columnLeft2">' . PHP_EOL;
        require_once(DIR_WS_INCLUDES . 'column_left.php');
        echo '</td>' . PHP_EOL;
    }
    ?>
    <td class="boxCenter" width="100%" valign="top">
      <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS . 'heading/icon_configuration.png'); ?></div>
      <div class="pageHeading"><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_HEADING', 'MITS ImageSlider - Banner Import'); ?></div>
      <div class="main pdg2 flt-l"><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_INTRO', 'Import banners into MITS ImageSlider.'); ?></div>
      <div style="clear:both;"></div>

      <div class="mits-import-box">
        <?php if (!$table_ok) { ?>
          <p class="mits-import-error"><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE', 'The banners table was not found.'); ?></p>
        <?php } else { ?>
          <?php echo xtc_draw_form('mits_imageslider_import', FILENAME_MITS_IMAGESLIDER_IMPORT_BANNERS, '', 'get'); ?>
            <label><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER', 'Filter banner group'); ?>:
              <select name="groupfilter">
                <option value="">--</option>
                <?php foreach ($groups as $group) { ?>
                  <option value="<?php echo mits_imageslider_import_h($group); ?>"<?php echo ($group_filter === $group ? ' selected="selected"' : ''); ?>><?php echo mits_imageslider_import_h($group); ?></option>
                <?php } ?>
              </select>
            </label>
            &nbsp;&nbsp;
            <label><?php echo xtc_draw_selection_field('overwrite', 'checkbox', '1', $overwrite); ?> <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_OVERWRITE', 'Update already imported banners'); ?></label>
            <br><br>
            <button class="button" type="submit" name="dry_run" value="1"><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_DRY_RUN', 'Preview / dry run'); ?></button>
            <button class="button but_green" type="submit" name="import" value="1" onclick="return confirm('<?php echo mits_imageslider_import_h(mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Run import')); ?>?');"><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Run import'); ?></button>
          </form>
        <?php } ?>
      </div>

      <?php if ($table_ok && $run_import_check && $summary['total'] == 0) { ?>
        <div class="mits-import-box"><p><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_NO_BANNERS', 'No banners found.'); ?></p></div>
      <?php } ?>

      <?php if ($table_ok && $run_import_check && $summary['total'] > 0) { ?>
        <div class="mits-import-box">
          <h3><?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_RESULT', 'Import result'); ?></h3>
          <p>
            <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_TOTAL', 'Total'); ?>: <strong><?php echo (int)$summary['total']; ?></strong> &nbsp;|&nbsp;
            <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_IMPORTED', 'Imported'); ?>: <strong><?php echo (int)$summary['imported']; ?></strong> &nbsp;|&nbsp;
            <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_UPDATED', 'Updated'); ?>: <strong><?php echo (int)$summary['updated']; ?></strong> &nbsp;|&nbsp;
            <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_SKIPPED', 'Skipped'); ?>: <strong><?php echo (int)$summary['skipped']; ?></strong> &nbsp;|&nbsp;
            <?php echo mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_ERRORS', 'Errors'); ?>: <strong><?php echo (int)$summary['errors']; ?></strong>
          </p>
          <table>
            <tr>
              <th>ID</th>
              <th>Banner</th>
              <th>Status</th>
              <th>Info</th>
            </tr>
            <?php foreach ($results as $result) {
                $class = ($result['status'] === 'error') ? 'mits-import-error' : (($result['status'] === 'imported' || $result['status'] === 'updated') ? 'mits-import-ok' : 'mits-import-warn');
            ?>
              <tr>
                <td><?php echo (int)$result['banner_id']; ?></td>
                <td><?php echo mits_imageslider_import_h($result['title']); ?></td>
                <td class="<?php echo $class; ?>"><?php echo mits_imageslider_import_h($result['status']); ?></td>
                <td><?php echo mits_imageslider_import_h($result['message']); ?></td>
              </tr>
            <?php } ?>
          </table>
        </div>
      <?php } ?>

      <p><?php echo xtc_button_link(mits_imageslider_import_t('MITS_IMAGESLIDER_IMPORT_BACK', 'Back to ImageSlider'), xtc_href_link(FILENAME_MITS_IMAGESLIDER)); ?></p>
    </td>
  </tr>
</table>
<?php require_once(DIR_WS_INCLUDES . 'footer.php'); ?>
</body>
</html>
<?php require_once(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
