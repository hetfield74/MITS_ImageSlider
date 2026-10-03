<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 24.06.2021
 * Time: 15:17
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

if (!function_exists('mits_imageslider_output_charset')) {
    function mits_imageslider_output_charset()
    {
        $charset = !empty($_SESSION['language_charset']) ? $_SESSION['language_charset'] : (defined('CHARSET') ? CHARSET : 'UTF-8');
        $charset_lc = strtolower((string)$charset);
        return ($charset_lc == 'utf8' || $charset_lc == 'utf-8') ? 'UTF-8' : (string)$charset;
    }
}

if (!function_exists('mits_imageslider_h')) {
    function mits_imageslider_h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, mits_imageslider_output_charset());
    }
}

if (!function_exists('mits_imageslider_safe_href')) {
    function mits_imageslider_safe_href($url)
    {
        $url = trim((string)$url);
        if ($url == '') {
            return '';
        }
        if (function_exists('mits_imageslider_is_allowed_url_scheme')) {
            if (!mits_imageslider_is_allowed_url_scheme($url)) {
                return '';
            }
        } elseif (preg_match('#^([a-z][a-z0-9+.-]*):#i', $url, $match)) {
            if (!in_array(strtolower($match[1]), array('http', 'https', 'mailto', 'tel'), true)) {
                return '';
            }
        }
        if (strpos($url, '//') === 0) {
            $url = 'https:' . $url;
        }
        return mits_imageslider_h($url);
    }
}

if (!function_exists('mits_imageslider_target_attr')) {
    function mits_imageslider_target_attr($target_id)
    {
        switch ((int)$target_id) {
            case 1:
                return ' target="_blank" rel="noopener noreferrer"';
            case 2:
                return ' target="_top"';
            case 3:
                return ' target="_self"';
            case 4:
                return ' target="_parent"';
            default:
                return '';
        }
    }
}

if (!function_exists('mits_imageslider_link_attrs')) {
    function mits_imageslider_link_attrs($url, $linktitle, $target)
    {
        $href = mits_imageslider_safe_href($url);
        if ($href == '') {
            return '';
        }
        return ' href="' . $href . '"' . (((string)$linktitle != '') ? ' title="' . mits_imageslider_h($linktitle) . '"' : '') . $target;
    }
}

if (!function_exists('mits_imageslider_render_image')) {

    function mits_imageslider_render_image($sd, $index, $mobile_bp, $tablet_bp, $datasrc, $lazyloadclass, $options = array())
    {
        $options = is_array($options) ? $options : array();
        $sizes_attr = isset($options['sizes']) && trim((string)$options['sizes']) != '' ? trim((string)$options['sizes']) : '100vw';
        $img_class = isset($options['img_class']) && trim((string)$options['img_class']) != '' ? trim((string)$options['img_class']) : '';
        $use_main_dimensions = !empty($options['use_main_dimensions']);
        $img_alt = isset($sd['alt_raw']) ? $sd['alt_raw'] : (isset($sd['alt']) ? $sd['alt'] : '');
        $img_title = isset($sd['title_raw']) ? $sd['title_raw'] : (isset($sd['title']) ? $sd['title'] : '');

        $has_main = (!empty($sd['main_rel']) && !empty($sd['main_image']));
        $has_tablet = (!empty($sd['tablet_rel']) && !empty($sd['tablet_image']));
        $has_mobile = (!empty($sd['mobile_rel']) && !empty($sd['mobile_image']));

        $has_webp = false;
        if (function_exists('mits_imageslider_build_srcset')) {
            if ($has_mobile) {
                $has_webp = (mits_imageslider_build_srcset($sd['mobile_rel'], 'mobile', 'webp', $sd['mobile_width'] ?? null) !== '');
            }
            if (!$has_webp && $has_tablet) {
                $has_webp = (mits_imageslider_build_srcset($sd['tablet_rel'], 'tablet', 'webp', $sd['tablet_width'] ?? null) !== '');
            }
            if (!$has_webp && $has_main) {
                $has_webp = (mits_imageslider_build_srcset($sd['main_rel'], 'desktop', 'webp', $sd['main_width'] ?? null) !== '');
            }
        }

        $use_picture = ($has_mobile || $has_tablet || $has_webp);

        $srcset_prefix = ($index == 0) ? '' : $datasrc;

        if ($use_picture && function_exists('mits_imageslider_build_srcset')) {
            $html = '<picture>';

            if ($has_mobile) {
                $webp = mits_imageslider_build_srcset($sd['mobile_rel'], 'mobile', 'webp', $sd['mobile_width'] ?? null);
                $fallback = mits_imageslider_build_srcset($sd['mobile_rel'], 'mobile', 'fallback', $sd['mobile_width'] ?? null);
                if ($webp !== '') {
                    $html .= '<source type="image/webp" media="(max-width:' . (int)$mobile_bp . 'px)" ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($webp) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
                if ($fallback !== '') {
                    $html .= '<source media="(max-width:' . (int)$mobile_bp . 'px)" ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($fallback) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
            }

            if ($has_tablet) {
                $webp = mits_imageslider_build_srcset($sd['tablet_rel'], 'tablet', 'webp', $sd['tablet_width'] ?? null);
                $fallback = mits_imageslider_build_srcset($sd['tablet_rel'], 'tablet', 'fallback', $sd['tablet_width'] ?? null);
                if ($webp !== '') {
                    $html .= '<source type="image/webp" media="(max-width:' . (int)$tablet_bp . 'px)" ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($webp) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
                if ($fallback !== '') {
                    $html .= '<source media="(max-width:' . (int)$tablet_bp . 'px)" ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($fallback) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
            }

            if ($has_main) {
                $webp = mits_imageslider_build_srcset($sd['main_rel'], 'desktop', 'webp', $sd['main_width'] ?? null);
                $fallback = mits_imageslider_build_srcset($sd['main_rel'], 'desktop', 'fallback', $sd['main_width'] ?? null);
                if ($webp !== '') {
                    $html .= '<source type="image/webp" ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($webp) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
                if ($fallback !== '') {
                    $html .= '<source ' . $srcset_prefix . 'srcset="' . mits_imageslider_h($fallback) . '" sizes="' . mits_imageslider_h($sizes_attr) . '">';
                }
            }

            $width = $use_main_dimensions ? (int)$sd['main_width'] : (int)$sd['current_w'];
            $height = $use_main_dimensions ? (int)$sd['main_height'] : (int)$sd['current_h'];

            $html .= '<img '
                . ($img_class != '' ? 'class="' . mits_imageslider_h($img_class) . '" ' : '')
                . ($index == 0 ? 'fetchpriority="high" loading="eager" decoding="async" ' : 'loading="lazy" decoding="async" ' . $lazyloadclass . $datasrc)
                . 'src="' . mits_imageslider_h($sd['img_src']) . '"'
                . ' width="' . $width . '" height="' . $height . '"'
                . ' alt="' . mits_imageslider_h($img_alt) . '" title="' . mits_imageslider_h($img_title) . '">';

            $html .= '</picture>';
            return $html;
        }

        return '<img '
            . ($img_class != '' ? 'class="' . mits_imageslider_h($img_class) . '" ' : '')
            . ($index == 0 ? 'fetchpriority="high" loading="eager" decoding="async" ' : '')
            . 'width="' . (int)$sd['main_width'] . '" height="' . (int)$sd['main_height'] . '" '
            . $lazyloadclass . $datasrc . 'src="' . mits_imageslider_h($sd['main_image']) . '" alt="' . mits_imageslider_h($img_alt) . '" title="' . mits_imageslider_h($img_title) . '">';
    }
}

function MITS_get_imageslider($group = 'mits_imageslider', $give_array = 'false')
{
    if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
        if (defined('DIR_FS_EXTERNAL') && is_file(DIR_FS_EXTERNAL . 'mits_imageslider/functions/general.php')) {
            require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/general.php');
        } elseif (defined('DIR_FS_CATALOG') && is_file(rtrim(DIR_FS_CATALOG, '/\\') . '/includes/external/mits_imageslider/functions/general.php')) {
            require_once(rtrim(DIR_FS_CATALOG, '/\\') . '/includes/external/mits_imageslider/functions/general.php');
        }
        if (defined('DIR_FS_EXTERNAL') && is_file(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php')) {
            require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php');
        } elseif (defined('DIR_FS_CATALOG') && is_file(rtrim(DIR_FS_CATALOG, '/\\') . '/includes/external/mits_imageslider/functions/images.php')) {
            require_once(rtrim(DIR_FS_CATALOG, '/\\') . '/includes/external/mits_imageslider/functions/images.php');
        }
        $mobile_width_breakpoint = defined('MODULE_MITS_IMAGESLIDER_MOBILEWIDTH') && MODULE_MITS_IMAGESLIDER_MOBILEWIDTH != '' ? MODULE_MITS_IMAGESLIDER_MOBILEWIDTH : '600';
        $tablet_width_breakpoint = defined('MODULE_MITS_IMAGESLIDER_TABLETWIDTH') && MODULE_MITS_IMAGESLIDER_TABLETWIDTH != '' ? MODULE_MITS_IMAGESLIDER_TABLETWIDTH : '1023';
        $group = strtolower($group);

        $today_md = xtc_db_input(date('m-d'));
        $date_validity_sql = "
          AND (
            (
              (i.recurring IS NULL OR i.recurring = 0)
              AND (i.date_scheduled IS NULL OR i.date_scheduled = '0000-00-00 00:00:00' OR i.date_scheduled <= NOW())
              AND (i.expires_date IS NULL OR i.expires_date = '0000-00-00 00:00:00' OR i.expires_date >= NOW())
            )
            OR
            (
              i.recurring = 1
              AND i.recurring_start_md IS NOT NULL
              AND i.recurring_start_md != ''
              AND i.recurring_end_md IS NOT NULL
              AND i.recurring_end_md != ''
              AND (
                (i.recurring_start_md <= i.recurring_end_md AND '" . $today_md . "' BETWEEN i.recurring_start_md AND i.recurring_end_md)
                OR
                (i.recurring_start_md > i.recurring_end_md AND ('" . $today_md . "' >= i.recurring_start_md OR '" . $today_md . "' <= i.recurring_end_md))
              )
            )
          )";

        $mits_imagesliders_query = xtDBquery(
          "SELECT *
             FROM " . TABLE_MITS_IMAGESLIDER . " i,
                  " . TABLE_MITS_IMAGESLIDER_INFO . " ii
            WHERE ii.languages_id = " . (int)$_SESSION['languages_id'] . "
              AND i.imagesliders_id = ii.imagesliders_id
              AND ii.imagesliders_image != ''
              AND i.status = 0
              AND i.imagesliders_group = '" . xtc_db_input($group) . "'"
              . $date_validity_sql . "
         ORDER BY i.sorting, i.imagesliders_id ASC"
        );
        if (xtc_db_num_rows($mits_imagesliders_query, true)) {
            $datasrc = (defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD') && MODULE_MITS_IMAGESLIDER_LAZYLOAD == 'true') ? 'data-' : '';
            $lazyloadclass = (defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD') && MODULE_MITS_IMAGESLIDER_LAZYLOAD == 'true') ? 'class="lazyload" ' : '';

            $mits_imagesliders_string = '';
            $sliderdata = array();
            $mits_imageslider_num = 1;
            while ($mits_imageslider_data = xtc_db_fetch_array($mits_imagesliders_query, true)) {
                $target = mits_imageslider_target_attr($mits_imageslider_data['imagesliders_url_target']);

                switch ($mits_imageslider_data['imagesliders_url_typ']) {
                    case 0:
                        $url = $mits_imageslider_data['imagesliders_url'];
                        break;
                    case 1:
                        $url = xtc_href_link($mits_imageslider_data['imagesliders_url']);
                        break;
                    case 2:
                        $url = xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$mits_imageslider_data['imagesliders_url']);
                        if (function_exists('xtc_product_link')) {
                            $product_query = xtDBquery("SELECT products_id, products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = " . (int)$mits_imageslider_data['imagesliders_url'] . " AND language_id = " . (int)$_SESSION['languages_id']);
                            if (xtc_db_num_rows($product_query, true)) {
                                $product = xtc_db_fetch_array($product_query, true);
                                $url = xtc_href_link(FILENAME_PRODUCT_INFO, xtc_product_link($product['products_id'], $product['products_name']));
                            }
                        }
                        break;
                    case 3:
                        $url = xtc_href_link(FILENAME_DEFAULT, 'cPath=' . (int)$mits_imageslider_data['imagesliders_url']);
                        if (function_exists('xtc_category_link')) {
                            $categorie_query = xtDBquery("SELECT categories_id, categories_name FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = " . (int)$mits_imageslider_data['imagesliders_url'] . " AND language_id = " . (int)$_SESSION['languages_id']);
                            if (xtc_db_num_rows($categorie_query, true)) {
                                $categorie = xtc_db_fetch_array($categorie_query, true);
                                $url = xtc_href_link(FILENAME_DEFAULT, xtc_category_link((int)$categorie['categories_id'], $categorie['categories_name']));
                            }
                        }
                        break;
                    case 4:
                        $url = xtc_href_link(FILENAME_CONTENT, 'coID=' . (int)$mits_imageslider_data['imagesliders_url']);
                        break;
                    case 5:
                        $url = xtc_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . (int)$mits_imageslider_data['imagesliders_url']);
                        if (function_exists('xtc_manufacturer_link')) {
                            $manufacturer_query = xtDBquery("SELECT manufacturers_id, manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . (int)$mits_imageslider_data['imagesliders_url']);
                            if (xtc_db_num_rows($manufacturer_query, true)) {
                                $manufacturer = xtc_db_fetch_array($manufacturer_query, true);
                                $url = xtc_href_link(FILENAME_DEFAULT, xtc_manufacturer_link($manufacturer['manufacturers_id'], $manufacturer['manufacturers_name']));
                            }
                        }
                        break;
                    default:
                        $url = xtc_href_link(FILENAME_DEFAULT);
                }

                $main_image = ($mits_imageslider_data['imagesliders_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'])) ? DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'] : '';
                $tablet_image = ($mits_imageslider_data['imagesliders_tablet_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'])) ? DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'] : '';
                $mobile_image = ($mits_imageslider_data['imagesliders_mobile_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'])) ? DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'] : '';

                $main_rel = ($mits_imageslider_data['imagesliders_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'])) ? $mits_imageslider_data['imagesliders_image'] : '';
                $tablet_rel = ($mits_imageslider_data['imagesliders_tablet_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'])) ? $mits_imageslider_data['imagesliders_tablet_image'] : '';
                $mobile_rel = ($mits_imageslider_data['imagesliders_mobile_image'] != '' && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'])) ? $mits_imageslider_data['imagesliders_mobile_image'] : '';

                // If no dedicated tablet/mobile image was uploaded, use generated fallback variants from the main image.
                // These fallback files are generated in admin/regenerator, not written into the DB fields.
                if ($main_rel != '' && function_exists('mits_imageslider_resolve_auto_fallback_from_relative')) {
                    if ($tablet_rel == '') {
                        $tablet_auto_rel = mits_imageslider_resolve_auto_fallback_from_relative($main_rel, 'tablet', false);
                        if ($tablet_auto_rel != '') {
                            $tablet_rel = $tablet_auto_rel;
                            $tablet_image = DIR_WS_BASE . DIR_WS_IMAGES . $tablet_auto_rel;
                        }
                    }
                    if ($mobile_rel == '') {
                        $mobile_auto_rel = mits_imageslider_resolve_auto_fallback_from_relative($main_rel, 'mobile', false);
                        if ($mobile_auto_rel != '') {
                            $mobile_rel = $mobile_auto_rel;
                            $mobile_image = DIR_WS_BASE . DIR_WS_IMAGES . $mobile_auto_rel;
                        }
                    }
                }

                if ($main_image != '') {
                    $main_width = $main_height = $main_type = $main_image_attr = $tablet_width = $tablet_height = $tablet_type = $tablet_image_attr = $mobile_width = $mobile_height = $mobile_type = $mobile_image_attr = '';

                    if (isset($mits_imageslider_data['imagesliders_image_width']) && is_numeric($mits_imageslider_data['imagesliders_image_width']) && (float)$mits_imageslider_data['imagesliders_image_width'] > 0
                      && isset($mits_imageslider_data['imagesliders_image_height']) && is_numeric($mits_imageslider_data['imagesliders_image_height']) && (float)$mits_imageslider_data['imagesliders_image_height'] > 0) {
                        $main_width = $mits_imageslider_data['imagesliders_image_width'];
                        $main_height = $mits_imageslider_data['imagesliders_image_height'];
                        $main_image_attr = ' width="' . $mits_imageslider_data['imagesliders_image_width'] . '" height="' . $mits_imageslider_data['imagesliders_image_height'] . '" ';
                    } else {
                        $main_slider_img = substr($main_image, strlen(DIR_WS_BASE));
                        list($main_width, $main_height, $main_type, $main_image_attr) = getimagesize(DIR_FS_CATALOG . $main_slider_img);
                        // Fill missing dimensions in DB (older entries often contain 0)
                        if ((float)$main_width > 0 && (float)$main_height > 0
                          && (!isset($mits_imageslider_data['imagesliders_image_width']) || (float)$mits_imageslider_data['imagesliders_image_width'] <= 0
                            || !isset($mits_imageslider_data['imagesliders_image_height']) || (float)$mits_imageslider_data['imagesliders_image_height'] <= 0)
                        ) {
                            @xtc_db_query(
                              "UPDATE " . TABLE_MITS_IMAGESLIDER_INFO . " SET imagesliders_image_width = " . (int)$main_width . ", imagesliders_image_height = " . (int)$main_height . " WHERE languages_id = " . (int)$_SESSION['languages_id'] . " AND imagesliders_id = " . (int)$mits_imageslider_data['imagesliders_id']
                            );
                        }
                    }
                    $image = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'];
                    $img_src = $main_image;
                    $current_w = $main_width;
                    $current_h = $main_height;
                    if ($tablet_image != '') {
                        $image = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'];
                        if (isset($mits_imageslider_data['imagesliders_tablet_image_width']) && is_numeric($mits_imageslider_data['imagesliders_tablet_image_width']) && (float)$mits_imageslider_data['imagesliders_tablet_image_width'] > 0
                          && isset($mits_imageslider_data['imagesliders_tablet_image_height']) && is_numeric($mits_imageslider_data['imagesliders_tablet_image_height']) && (float)$mits_imageslider_data['imagesliders_tablet_image_height'] > 0) {
                            $tablet_width = $mits_imageslider_data['imagesliders_tablet_image_width'];
                            $tablet_height = $mits_imageslider_data['imagesliders_tablet_image_height'];
                            $tablet_image_attr = ' width="' . $mits_imageslider_data['imagesliders_tablet_image_width'] . '" height="' . $mits_imageslider_data['imagesliders_tablet_image_height'] . '" ';
                        } else {
                            $tablet_slider_img = substr($tablet_image, strlen(DIR_WS_BASE));
                            list($tablet_width, $tablet_height, $tablet_type, $tablet_image_attr) = getimagesize(DIR_FS_CATALOG . $tablet_slider_img);
                            if ((float)$tablet_width > 0 && (float)$tablet_height > 0
                              && (!isset($mits_imageslider_data['imagesliders_tablet_image_width']) || (float)$mits_imageslider_data['imagesliders_tablet_image_width'] <= 0
                                || !isset($mits_imageslider_data['imagesliders_tablet_image_height']) || (float)$mits_imageslider_data['imagesliders_tablet_image_height'] <= 0)
                            ) {
                                @xtc_db_query(
                                  "UPDATE " . TABLE_MITS_IMAGESLIDER_INFO . " SET imagesliders_tablet_image_width = " . (int)$tablet_width . ", imagesliders_tablet_image_height = " . (int)$tablet_height . " WHERE languages_id = " . (int)$_SESSION['languages_id'] . " AND imagesliders_id = " . (int)$mits_imageslider_data['imagesliders_id']
                                );
                            }
                        }
                        $img_src = $tablet_image;
                        $current_w = $tablet_width;
                        $current_h = $tablet_height;
                    }
                    if ($mobile_image != '') {
                        $image = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'];
                        if (isset($mits_imageslider_data['imagesliders_mobile_image_width']) && is_numeric($mits_imageslider_data['imagesliders_mobile_image_width']) && (float)$mits_imageslider_data['imagesliders_mobile_image_width'] > 0
                          && isset($mits_imageslider_data['imagesliders_mobile_image_height']) && is_numeric($mits_imageslider_data['imagesliders_mobile_image_height']) && (float)$mits_imageslider_data['imagesliders_mobile_image_height'] > 0) {
                            $mobile_width = $mits_imageslider_data['imagesliders_mobile_image_width'];
                            $mobile_height = $mits_imageslider_data['imagesliders_mobile_image_height'];
                            $mobile_image_attr = ' width="' . $mits_imageslider_data['imagesliders_mobile_image_width'] . '" height="' . $mits_imageslider_data['imagesliders_mobile_image_height'] . '" ';
                        } else {
                            $mobile_slider_img = substr($mobile_image, strlen(DIR_WS_BASE));
                            list($mobile_width, $mobile_height, $mobile_type, $mobile_image_attr) = getimagesize(DIR_FS_CATALOG . $mobile_slider_img);
                            if ((float)$mobile_width > 0 && (float)$mobile_height > 0
                              && (!isset($mits_imageslider_data['imagesliders_mobile_image_width']) || (float)$mits_imageslider_data['imagesliders_mobile_image_width'] <= 0
                                || !isset($mits_imageslider_data['imagesliders_mobile_image_height']) || (float)$mits_imageslider_data['imagesliders_mobile_image_height'] <= 0)
                            ) {
                                @xtc_db_query(
                                  "UPDATE " . TABLE_MITS_IMAGESLIDER_INFO . " SET imagesliders_mobile_image_width = " . (int)$mobile_width . ", imagesliders_mobile_image_height = " . (int)$mobile_height . " WHERE languages_id = " . (int)$_SESSION['languages_id'] . " AND imagesliders_id = " . (int)$mits_imageslider_data['imagesliders_id']
                                );
                            }
                        }
                        $img_src = $mobile_image;
                        $current_w = $mobile_width;
                        $current_h = $mobile_height;
                    }

                    if (!is_numeric($tablet_width) || (float)$tablet_width <= 0 || !is_numeric($tablet_height) || (float)$tablet_height <= 0) {
                        $tablet_width = $main_width;
                        $tablet_height = $main_height;
                    }
                    if (!is_numeric($mobile_width) || (float)$mobile_width <= 0 || !is_numeric($mobile_height) || (float)$mobile_height <= 0) {
                        $mobile_width = $tablet_width;
                        $mobile_height = $tablet_height;
                    }

                    if ($mits_imageslider_num == 1 && is_numeric($main_height) && $main_height > 0 && is_numeric($main_width) && $main_width > 0) {
                        $desktop_style = number_format((float)$main_height / ((float)$main_width / 100), 2, '.', '');
                        $tablet_style = ($tablet_image != '' && is_numeric($tablet_height) && $tablet_height > 0 && is_numeric($tablet_width) && $tablet_width > 0) ? number_format((float)$tablet_height / ((float)$tablet_width / 100), 2, '.', '') : $desktop_style;
                        $mobile_style = ($mobile_image != '' && is_numeric($mobile_height) && $mobile_height > 0 && is_numeric($mobile_width) && $mobile_width > 0) ? number_format((float)$mobile_height / ((float)$mobile_width / 100), 2, '.', '') : $tablet_style;
                    }

                    $desktop_style_item = '0';
                    if (is_numeric($main_height) && (float)$main_height > 0 && is_numeric($main_width) && (float)$main_width > 0) {
                        $desktop_style_item = number_format((float)$main_height / ((float)$main_width / 100), 2, '.', '');
                    }
                    $tablet_style_item = $desktop_style_item;
                    if ($tablet_image != '' && is_numeric($tablet_height) && (float)$tablet_height > 0 && is_numeric($tablet_width) && (float)$tablet_width > 0) {
                        $tablet_style_item = number_format((float)$tablet_height / ((float)$tablet_width / 100), 2, '.', '');
                    }
                    $mobile_style_item = $tablet_style_item;
                    if ($mobile_image != '' && is_numeric($mobile_height) && (float)$mobile_height > 0 && is_numeric($mobile_width) && (float)$mobile_width > 0) {
                        $mobile_style_item = number_format((float)$mobile_height / ((float)$mobile_width / 100), 2, '.', '');
                    }

                    $alt = (isset($mits_imageslider_data['imagesliders_alt']) && $mits_imageslider_data['imagesliders_alt'] != '') ? $mits_imageslider_data['imagesliders_alt'] : $mits_imageslider_data['imagesliders_title'];
                    $linktitle = (isset($mits_imageslider_data['imagesliders_linktitle']) && $mits_imageslider_data['imagesliders_linktitle'] != '') ? $mits_imageslider_data['imagesliders_linktitle'] : $mits_imageslider_data['imagesliders_title'];

                    $index = count($sliderdata);

                    $sd = array(
                      'id'                => $mits_imageslider_data['imagesliders_id'],
                      'link'              => mits_imageslider_safe_href($url),
                      'linktitle'         => mits_imageslider_h(strip_tags($linktitle)),
                      'linktitle_raw'     => strip_tags($linktitle),
                      'target'            => $target,
                      'main_image'        => $main_image,
                      'tablet_image'      => $tablet_image,
                      'mobile_image'      => $mobile_image,
                      'main_rel'          => $main_rel,
                      'tablet_rel'        => $tablet_rel,
                      'mobile_rel'        => $mobile_rel,
                      'main_image_attr'   => $main_image_attr,
                      'tablet_image_attr' => $tablet_image_attr,
                      'mobile_image_attr' => $mobile_image_attr,
                      'main_width'        => $main_width,
                      'main_height'       => $main_height,
                      'tablet_width'      => $tablet_width,
                      'tablet_height'     => $tablet_height,
                      'mobile_width'      => $mobile_width,
                      'mobile_height'     => $mobile_height,
                      'desktop_style'     => $desktop_style_item,
                      'tablet_style'      => $tablet_style_item,
                      'mobile_style'      => $mobile_style_item,
                      'image'             => $image,
                      'imageset'          => $mobile_image . $tablet_image . $main_image,
                      'img_src'           => $img_src,
                      'current_w'         => $current_w,
                      'current_h'         => $current_h,
                      'title'             => mits_imageslider_h(strip_tags($mits_imageslider_data['imagesliders_title'])),
                      'title_raw'         => strip_tags($mits_imageslider_data['imagesliders_title']),
                      'alt'               => mits_imageslider_h(strip_tags($alt)),
                      'alt_raw'           => strip_tags($alt),
                      'text'              => $mits_imageslider_data['imagesliders_description']
                    );
                    $sd['link_attrs'] = mits_imageslider_link_attrs($url, $sd['linktitle_raw'], $target);

                    $sd_pic = $sd;
                    $sd_pic['tablet_image'] = ($sd_pic['tablet_image'] != '') ? $sd_pic['tablet_image'] : $sd_pic['main_image'];
                    $sd_pic['mobile_image'] = ($sd_pic['mobile_image'] != '') ? $sd_pic['mobile_image'] : $sd_pic['tablet_image'];
                    $sd_pic['tablet_rel']   = ($sd_pic['tablet_rel']   != '') ? $sd_pic['tablet_rel']   : $sd_pic['main_rel'];
                    $sd_pic['mobile_rel']   = ($sd_pic['mobile_rel']   != '') ? $sd_pic['mobile_rel']   : $sd_pic['tablet_rel'];

                    $sd['pictureset'] = mits_imageslider_render_image($sd_pic, $index, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                    $sd['pictureset_logo'] = mits_imageslider_render_image($sd_pic, $index, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass, array('sizes' => '(min-width:1024px) 25vw, (min-width:601px) 33vw, 50vw', 'img_class' => 'mits-imageslider-logo-img', 'use_main_dimensions' => true));

                    $sliderdata[] = $sd;

                    $mits_imageslider_num++;
                }
            }

            $count_slides = sizeof($sliderdata);

            $slider_id = '';
            $desktop_style = '0';
            $tablet_style = '0';
            $mobile_style = '0';
            foreach ($sliderdata as $sd) {
                if (isset($sd['desktop_style']) && is_numeric($sd['desktop_style'])) {
                    $desktop_style = max((float)$desktop_style, (float)$sd['desktop_style']);
                }
                if (isset($sd['tablet_style']) && is_numeric($sd['tablet_style'])) {
                    $tablet_style = max((float)$tablet_style, (float)$sd['tablet_style']);
                }
                if (isset($sd['mobile_style']) && is_numeric($sd['mobile_style'])) {
                    $mobile_style = max((float)$mobile_style, (float)$sd['mobile_style']);
                }
            }
            if ((float)$desktop_style <= 0) {
                $desktop_style = 30.00;
            }
            if ((float)$tablet_style <= 0) {
                $tablet_style = (float)$desktop_style;
            }
            if ((float)$mobile_style <= 0) {
                $mobile_style = (float)$tablet_style;
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Splide tpl_modified_nova') {
                if ($count_slides > 0) {
                    $banner_array = array('shoplogo', 'banner1', 'banner2', 'banner3', 'banner4', 'banner5', 'banner6', 'banner7', 'banner8');
                    $no_banner = !in_array($group, $banner_array);
                    $mits_imagesliders_string = '';
                    if ($no_banner) {
                        $item_content_class = 'slider_item_content';
                        $mits_imagesliders_string .= '<div class="mits_imageslider_container"><div class="slider_row">
            <div class="splide ' . ($count_slides == 1 ? 'splide_slider_single' : 'splide_slider') . '" aria-label="Slider">
              <div class="splide__track">
        		    <div class="splide__list">';
                    } else {
                        $item_content_class = 'banner_item_content';
                    }
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $slidertext = (($sliderdata[$i]['text'] != '') ? '<span class="' . $item_content_class . '">' . $sliderdata[$i]['text'] . '</span>' : '');                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        if ($no_banner) {
                            $mits_imagesliders_string .= '<div class="splide__slide">';
                        }
                        $mits_imagesliders_string .= $link_begin . $img . $slidertext . $link_end;
                        if ($no_banner) {
                            $mits_imagesliders_string .= '</div>';
                        } else {
                            break;
                        }
                    }
                    if ($no_banner) {
                        $mits_imagesliders_string .= '</div></div></div></div></div>';
                        //$mits_imagesliders_string .= ($count_slides == 1) ? '' : '<style>.mits_imageslider_container .slider_row::before{padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {.mits_imageslider_container .slider_row::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) {.mits_imageslider_container .slider_row::before{padding-bottom:' . $desktop_style . '%}}</style>';
                    }
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Splide') {
                if ($count_slides > 0) {
                    $slider_id = $sliderdata[0]['id'];
                    $mw = (isset($sliderdata[0]['mobile_width']) && (float)$sliderdata[0]['mobile_width'] > 0) ? (float)$sliderdata[0]['mobile_width'] : 1;
                    $mh = (isset($sliderdata[0]['mobile_height']) && (float)$sliderdata[0]['mobile_height'] > 0) ? (float)$sliderdata[0]['mobile_height'] : 1;
                    $tw = (isset($sliderdata[0]['tablet_width']) && (float)$sliderdata[0]['tablet_width'] > 0) ? (float)$sliderdata[0]['tablet_width'] : $mw;
                    $th = (isset($sliderdata[0]['tablet_height']) && (float)$sliderdata[0]['tablet_height'] > 0) ? (float)$sliderdata[0]['tablet_height'] : $mh;
                    $dw = (isset($sliderdata[0]['main_width']) && (float)$sliderdata[0]['main_width'] > 0) ? (float)$sliderdata[0]['main_width'] : $tw;
                    $dh = (isset($sliderdata[0]['main_height']) && (float)$sliderdata[0]['main_height'] > 0) ? (float)$sliderdata[0]['main_height'] : $th;

                    $ratioMobile  = ($mh / $mw) * 100;
                    $ratioTablet  = ($th / $tw) * 100;
                    $ratioDesktop = ($dh / $dw) * 100;
                    $mits_container = ($count_slides == 1) ? 'mits_sliderimage' : 'splide__slide';
                    $mits_slider_css =  '#mits_slider__' . $group . ' img';
                    $container_css = ($count_slides == 1) ? '
                      #slider_img_' . $slider_id . '{
                        position: relative;
                        width: 100%;
                        height: 0;
                        padding-bottom: ' . $ratioMobile . '%;
                        display:block;
                        overflow:hidden;
                      }
                      @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {
                        #slider_img_' . $slider_id . '{
                          padding-bottom: ' . $ratioTablet . '%;
                        }
                      }
                      @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) {
                        #slider_img_' . $slider_id . '{
                          padding-bottom: ' . $ratioDesktop . '%;
                        }
                      }
                      #slider_img_' . $slider_id . ' img {
                        position: absolute;
                        top: 0;
                        left: 0;  
                        width: 100%;
                        height: 100%;
                        display: block;
                        object-fit: cover;                    
                      }
                    ' : '';
                    $mits_imagesliders_string = '<style>' . $container_css . '
                      ' . $mits_slider_css . ' {
                        width: 100%;
                        max-width: 100%;
                        height: auto;
                        display: block;
                        aspect-ratio: ' . $mw . ' / ' . $mh . ';
                        object-fit: cover;
                      }
                      @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {
                        ' . $mits_slider_css . ' {
                          aspect-ratio: ' . $tw . ' / ' . $th . ';
                        }
                      }
                      @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) {
                        ' . $mits_slider_css . ' {
                          aspect-ratio: ' . $dw . ' / ' . $dh . ';
                        }
                      }
                    </style>';
                    $mits_imagesliders_string .= ($count_slides == 1) ? '' : '<div id="mits_slider__' . $group . '" class="splide mits_imageslider" aria-label="Slider">
            <div class="splide__track">
              <div class="splide__list">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="slider-desc">' . $sliderdata[$i]['text'] . '</div>' : '');                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $container_id = ($count_slides == 1) ? ' id="slider_img_' . $sliderdata[$i]['id'] . '"' : '';
                        $slider_id = ($count_slides == 1) ? $sliderdata[$i]['id'] : '';
                        $mits_imagesliders_string .= '
						<div class="' . $mits_container . '"' . $container_id . '>
							' . $link_begin . '
                ' . $img . '
                ' . $slidertext . '
							' . $link_end . '					
						</div>';
                    }
                    //$mits_imagesliders_string .= ($count_slides == 1) ? '</div><style>.mits_sliderimage img{width:100%;height:auto;}.mits_sliderimage::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $desktop_style . '%} }</style>' : '</div></div></div><style>.mits_imageslider::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {.mits_imageslider::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_imageslider::before{padding-bottom:' . $desktop_style . '%} }</style>';
                    $mits_imagesliders_string .= ($count_slides == 1) ? '' : '</div></div></div>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Slick tpl_modified') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = ($count_slides == 1) ? '<div class="mits_sliderimage" aria-label="Image">' : '<div><div class="content_slider cf" aria-label="Slider">
              <div class="slider_home">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="slick-desc">' . $sliderdata[$i]['text'] . '</div>' : '');                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
						<div class="slider_item">
							' . $link_begin . '
								' . $img . '
							' . $link_end . '
							' . $slidertext . '
						</div>';
                    }
                    $mits_imagesliders_string .= '</div>';
                    $mits_imagesliders_string .= ($count_slides == 1) ? '<style>.mits_sliderimage img,.mits_slickslider img{width:100%;height:auto;}.mits_sliderimage::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $desktop_style . '%} }</style>' : '</div></div><style>.content_slider::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {.content_slider::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .content_slider::before{padding-bottom:' . $desktop_style . '%} }</style>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Slick') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = $count_slides == 1 ? '<div class="mits_imageslider_container"><div class="mits_sliderimage" aria-label="Image">' : '<div class="mits_imageslider_container"><div class="mits_slickslider" aria-label="Slider">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $slidertext = ($sliderdata[$i]['text'] != '') ? '<div class="slick-desc">' . $sliderdata[$i]['text'] . '</div>' : '';                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
						<div>
							' . $link_begin . '
								' . $img . '
							' . $link_end . '
							' . $slidertext . '
						</div>';
                    }
                    $mits_imagesliders_string .= '
				  </div></div>';
                    $mits_imagesliders_string .= ($count_slides == 1) ? '<style>.mits_sliderimage img,.mits_slickslider img{width:100%;height:auto;}.mits_sliderimage::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_sliderimage::before{padding-bottom:' . $desktop_style . '%} }</style>' : '<style>.mits_slickslider::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) { .mits_slickslider::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_slickslider::before{padding-bottom:' . $desktop_style . '%} }</style>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'bxSlider') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = '
          <div class="mits_imageslider_container">
          <div class="mits_imageslider">
					<ul class="mits_bxslider">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
						<li>
							' . $link_begin . '
								' . $img . '
							' . $link_end . '
						</li>';
                    }
                    $mits_imagesliders_string .= '
					</ul>
					</div>
					</div>';
                    $mits_imagesliders_string .= '<style>.mits_imageslider::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) {.mits_imageslider::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .mits_imageslider::before{padding-bottom:' . $desktop_style . '%} }</style>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'bxSlider tpl_modified') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = '<div class="content_banner cf">
					<ul class="bxcarousel_slider">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
						<li>
							' . $link_begin . '
								' . $img . '
							' . $link_end . '
						</li>';
                    }
                    $mits_imagesliders_string .= '
					</ul>
					</div>';
                    $mits_imagesliders_string .= '<style>.content_banner::before{content:"";float:left;padding-bottom:' . $mobile_style . '%} @media (min-width: ' . ($mobile_width_breakpoint + 1) . 'px) { .content_banner::before{padding-bottom:' . $tablet_style . '%}} @media (min-width: ' . ($tablet_width_breakpoint + 1) . 'px) { .content_banner::before{padding-bottom:' . $desktop_style . '%} }</style>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'NivoSlider') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = '
          <div class="mits_imageslider_container">
					<div class="slider-wrapper theme-default">
						<div class="ribbon"></div>';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $mits_imagesliders_string .= chr(9) . '<div id="' . $sliderdata[$i]['id'] . '" class="nivo-html-caption">' . chr(13);
                        if ($sliderdata[$i]['title'] != '') {
                            $mits_imagesliders_string .= chr(9) . chr(9) . '<h3>' . strip_tags($sliderdata[$i]['title']) . '</h3>' . chr(13);
                        }
                        if ($sliderdata[$i]['text'] != '') {
                            $mits_imagesliders_string .= chr(9) . chr(9) . '<div>' . strip_tags($sliderdata[$i]['text']) . '</div>' . chr(13);
                        }
                        $mits_imagesliders_string .= chr(9) . '</div>' . chr(13);
                    }
                    $mits_imagesliders_string .= '
						<div class="nivoSlider mits_nivoSlider">' . chr(13);
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
            ' . $link_begin . '
							' . $img . '
						' . $link_end . chr(13);
                    }
                    $mits_imagesliders_string .= '
						</div>
					</div>
					</div>' . chr(13);
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'FlexSlider') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = '
					<div class="mits_imageslider_container">
					<div class="flex-container">
						<div class="flexslider">
							<ul class="slides">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                        $slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="flex-caption"><div class="flex-caption-header">' . $sliderdata[$i]['title'] . '</div><div>' . $sliderdata[$i]['text'] . '</div></div>' : '<div class="flex-caption"><div class="flex-caption-header">' . $sliderdata[$i]['title'] . '</div></div>');                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
								<li>
									' . $link_begin . '
										' . $img . '
										' . $slidertext . '
									' . $link_end . '
								</li>';
                    }
                    $mits_imagesliders_string .= '
							</ul>
						</div>
					</div>	
					</div>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'jQuery.innerfade') {
                if ($count_slides > 0) {
                    $mits_imagesliders_string = '
          <div class="mits_imageslider_container">
					<div class="mits_imageslider">
						<ul class="imageslider">';
                    for ($i = 0, $n = $count_slides; $i < $n; $i++) {                        $img = mits_imageslider_render_image($sliderdata[$i], $i, $mobile_width_breakpoint, $tablet_width_breakpoint, $datasrc, $lazyloadclass);
                        $link_begin = $link_end = '';
                        if ($sliderdata[$i]['link'] != '') {
                            $link_begin = ($sliderdata[$i]['link_attrs'] != '') ? '<a' . $sliderdata[$i]['link_attrs'] . '>' : '';
                            $link_end = '</a>';
                        }
                        $mits_imagesliders_string .= '
							<li>
								' . $link_begin . '
									' . $img . '
								' . $link_end;
                        if ($sliderdata[$i]['text'] != '') {
                            $mits_imagesliders_string .= '<div class="slidercontent"><div class="slidercontentinner">' . $sliderdata[$i]['text'] . '</div></div>';
                        }
                        $mits_imagesliders_string .= '
							</li>';
                    }
                    $mits_imagesliders_string .= '
						</ul>
						<ul class="imageslider-nav">
							<li class="slideprev"><a class="prev" href="#">' . PREVNEXT_BUTTON_PREV . '</a></li>
							<li class="slidepause"><a class="pause" href="#">Pause</a></li>
							<li class="slidenext"><a class="next" href="#">' . PREVNEXT_BUTTON_NEXT . '</a></li>
						</ul>
						<div class="innerfadeclear"></div>
					</div>
					</div>';
                }
            }

            if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'custom'
              && defined('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE') && MODULE_MITS_IMAGESLIDER_CUSTOM_CODE != ''
            ) {
                $customcode = explode('###SLIDERITEM###', MODULE_MITS_IMAGESLIDER_CUSTOM_CODE);
                if (is_array($customcode)) {
                    $customcode_top = (isset($customcode[0]) && !empty($customcode[0])) ? $customcode[0] : '';
                    $customcode_slider = (isset($customcode[1]) && !empty($customcode[1])) ? $customcode[1] : '';
                    $customcode_bottom = (isset($customcode[2]) && !empty($customcode[2])) ? $customcode[2] : '';

                    if ($count_slides > 0 && $customcode_slider != '') {
                        $mits_imagesliders_string = $customcode_top;
                        for ($i = 0, $n = $count_slides; $i < $n; $i++) {
                            $sliderdata[$i]['tablet_image'] = ($sliderdata[$i]['tablet_image'] != '') ? $sliderdata[$i]['tablet_image'] : $sliderdata[$i]['image'];
                            $sliderdata[$i]['mobile_image'] = ($sliderdata[$i]['mobile_image'] != '') ? $sliderdata[$i]['mobile_image'] : $sliderdata[$i]['tablet_image'];
                            $sliderdata[$i]['tablet_rel'] = ($sliderdata[$i]['tablet_rel'] != '') ? $sliderdata[$i]['tablet_rel'] : $sliderdata[$i]['main_rel'];
                            $sliderdata[$i]['mobile_rel'] = ($sliderdata[$i]['mobile_rel'] != '') ? $sliderdata[$i]['mobile_rel'] : $sliderdata[$i]['tablet_rel'];
                            $customcode_slider_item = $customcode_slider;
                            $customcode_slider_item = str_replace('{ID}', $sliderdata[$i]['id'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{LINK}', $sliderdata[$i]['link'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{LINKTITLE}', $sliderdata[$i]['linktitle'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{LINKTARGET}', $sliderdata[$i]['target'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MAINIMAGE}', $sliderdata[$i]['main_image'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TABLETIMAGE}', $sliderdata[$i]['tablet_image'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MOBILEIMAGE}', $sliderdata[$i]['mobile_image'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MAINIMAGE_ATTR}', $sliderdata[$i]['main_image_attr'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TABLETIMAGE_ATTR}', $sliderdata[$i]['tablet_image_attr'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MOBILEIMAGE_ATTR}', $sliderdata[$i]['mobile_image_attr'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MAINWIDTH}', $sliderdata[$i]['main_width'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MAINHEIGHT}', $sliderdata[$i]['main_height'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TABLETWIDTH}', $sliderdata[$i]['tablet_width'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TABLETHEIGHT}', $sliderdata[$i]['tablet_height'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MOBILEWIDTH}', $sliderdata[$i]['mobile_width'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{MOBILEHEIGHT}', $sliderdata[$i]['mobile_height'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{IMAGE}', $sliderdata[$i]['image'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{IMAGESET}', $sliderdata[$i]['imageset'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{PICTURESET}', $sliderdata[$i]['pictureset'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TITLE}', $sliderdata[$i]['title'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{IMAGEALT}', $sliderdata[$i]['alt'], $customcode_slider_item);
                            $customcode_slider_item = str_replace('{TEXT}', $sliderdata[$i]['text'], $customcode_slider_item);
                            $mits_imagesliders_string .= $customcode_slider_item;
                        }
                        $mits_imagesliders_string .= $customcode_bottom;
                    }
                }
            }

            if ($give_array != 'false' && is_array($sliderdata) && count($sliderdata) > 0) {
                return $sliderdata;
            } elseif ($mits_imagesliders_string != '' && $give_array == 'false') {
                $version = (defined('MODULE_MITS_IMAGESLIDER_VERSION') && MODULE_MITS_IMAGESLIDER_VERSION != '') ? ' v' . MODULE_MITS_IMAGESLIDER_VERSION : '';
                return '<!-- MITS Imageslider' . $version . ' (c)2008-' . date('Y') . ' by Hetfield - www.MerZ-IT-SerVice.de - Begin -->' . $mits_imagesliders_string . '<!-- MITS Imageslider' . $version . ' (c)2008-' . date('Y') . ' by Hetfield - www.MerZ-IT-SerVice.de - End -->';
            } else {
                return false;
            }
        }
    }
}
