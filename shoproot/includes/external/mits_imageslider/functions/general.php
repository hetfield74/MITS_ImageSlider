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

function xtc_get_imageslider_image($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_image'];
    }
}

function xtc_get_imageslider_tablet_image($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_tablet_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_tablet_image'];
    }
}

function xtc_get_imageslider_mobile_image($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_mobile_image FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_mobile_image'];
    }
}

function xtc_get_imageslider_url($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_url FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_url'];
    }
}

function xtc_get_imageslider_url_target($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_url_target FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_url_target'];
    }
}

function xtc_get_imageslider_url_typ($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_url_typ FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_url_typ'];
    }
}

function xtc_get_imageslider_linktitle($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_linktitle FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_linktitle'];
    }
}

function xtc_get_imageslider_title($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_title FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_title'];
    }
}

function xtc_get_imageslider_alt($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_alt FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_alt'];
    }
}

function xtc_get_imageslider_description($imageslider_id, $language_id = '')
{
    $language_id = ($language_id == '') ? (int)$_SESSION['languages_id'] : $language_id;
    $imageslider_query = xtc_db_query("SELECT imagesliders_description FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE imagesliders_id = " . (int)$imageslider_id . " AND languages_id = " . (int)$language_id);
    if (xtc_db_num_rows($imageslider_query) > 0) {
        $imageslider = xtc_db_fetch_array($imageslider_query);
        return $imageslider['imagesliders_description'];
    }
}

if (!function_exists('mits_imageslider_strip_session_from_url')) {
    function mits_imageslider_strip_session_from_url($url)
    {
        $url = trim((string)$url);
        if ($url == '') {
            return '';
        }

        $session_names = array('XTCsid', 'MODsid');
        if (function_exists('xtc_session_name')) {
            $session_names[] = xtc_session_name();
        }
        if (function_exists('session_name')) {
            $session_names[] = session_name();
        }
        $session_names = array_unique(array_filter($session_names));

        foreach ($session_names as $session_name) {
            $session_name = preg_quote($session_name, '#');
            $url = preg_replace('~([?&])' . $session_name . '=[^&#]*~i', '$1', $url);
        }

        while (strpos($url, '&&') !== false) {
            $url = str_replace('&&', '&', $url);
        }
        $url = str_replace('?&', '?', $url);
        $url = preg_replace('~[?&](#.*)?$~', '$1', $url);

        return rtrim($url, '?&');
    }
}


if (!function_exists('mits_imageslider_has_modified_seo_delimiter')) {
    function mits_imageslider_has_modified_seo_delimiter($url)
    {
        return (bool)preg_match('~(?:::{1}|(?<!:):{2}(?!:)|:_:|:\.:|---|(?<!-)--(?!-)|-_-|-\.-)~', (string)$url);
    }
}

if (!function_exists('mits_imageslider_is_allowed_url_scheme')) {
    function mits_imageslider_is_allowed_url_scheme($url)
    {
        $url = trim((string)$url);
        if ($url == '') {
            return true;
        }

        if (strpos($url, '//') === 0) {
            $url = 'https:' . $url;
        }

        if (preg_match('#^([a-z][a-z0-9+.-]*):#i', $url, $match)) {
            return in_array(strtolower($match[1]), array('http', 'https', 'mailto', 'tel'), true);
        }

        return true;
    }
}

if (!function_exists('mits_imageslider_normalize_host')) {
    function mits_imageslider_normalize_host($host)
    {
        $host = strtolower(trim((string)$host));
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
        }
        return $host;
    }
}

if (!function_exists('mits_imageslider_shop_hosts')) {
    function mits_imageslider_shop_hosts()
    {
        $hosts = array();
        $servers = array();

        if (defined('HTTP_SERVER')) {
            $servers[] = HTTP_SERVER;
        }
        if (defined('HTTPS_SERVER')) {
            $servers[] = HTTPS_SERVER;
        }

        foreach ($servers as $server) {
            $host = parse_url($server, PHP_URL_HOST);
            if ($host != '') {
                $hosts[] = mits_imageslider_normalize_host($host);
            }
        }

        return array_unique(array_filter($hosts));
    }
}

if (!function_exists('mits_imageslider_shop_base_paths')) {
    function mits_imageslider_shop_base_paths()
    {
        $paths = array();
        $servers = array();

        if (defined('HTTP_SERVER')) {
            $servers[] = HTTP_SERVER;
        }
        if (defined('HTTPS_SERVER')) {
            $servers[] = HTTPS_SERVER;
        }

        foreach ($servers as $server) {
            $path = parse_url($server, PHP_URL_PATH);
            if ($path != '') {
                $paths[] = $path;
            }
        }
        if (defined('DIR_WS_CATALOG')) {
            $paths[] = DIR_WS_CATALOG;
        }
        if (defined('DIR_WS_BASE')) {
            $paths[] = DIR_WS_BASE;
        }

        $normalized = array();
        foreach ($paths as $path) {
            $path = '/' . trim(str_replace('\\', '/', (string)$path), '/') . '/';
            if ($path != '//') {
                $normalized[] = $path;
            }
        }

        usort($normalized, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        return array_unique($normalized);
    }
}

if (!function_exists('mits_imageslider_make_relative_shop_url')) {
    function mits_imageslider_make_relative_shop_url($path, $query = '', $fragment = '')
    {
        $path = str_replace('\\', '/', (string)$path);
        if ($path == '') {
            $path = '/';
        }

        foreach (mits_imageslider_shop_base_paths() as $base_path) {
            if ($path == rtrim($base_path, '/')) {
                $path = '/';
                break;
            }
            if (strpos($path, $base_path) === 0) {
                $path = '/' . substr($path, strlen($base_path));
                break;
            }
        }

        $path = ltrim($path, '/');
        if ($path == '') {
            $path = defined('FILENAME_DEFAULT') ? FILENAME_DEFAULT : 'index.php';
        }

        if ($query != '') {
            $path .= '?' . $query;
        }
        if ($fragment != '') {
            $path .= '#' . $fragment;
        }

        return mits_imageslider_strip_session_from_url($path);
    }
}

if (!function_exists('mits_imageslider_extract_internal_url')) {
    function mits_imageslider_extract_internal_url($url)
    {
        $url = trim((string)$url);
        if ($url == '') {
            return false;
        }

        $parse_url_value = $url;
        if (strpos($parse_url_value, '//') === 0) {
            $parse_url_value = 'https:' . $parse_url_value;
        } elseif (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $parse_url_value)
          && preg_match('~^[a-z0-9.-]+\.[a-z0-9-]+(?::[0-9]+)?(?:[/?#]|$)~i', $parse_url_value)
        ) {
            $parse_url_value = 'https://' . $parse_url_value;
        }

        $parsed = @parse_url($parse_url_value);
        if (!is_array($parsed)) {
            return false;
        }

        if (isset($parsed['host']) && $parsed['host'] != '') {
            $host = mits_imageslider_normalize_host($parsed['host']);
            if (!in_array($host, mits_imageslider_shop_hosts())) {
                return false;
            }

            $path = isset($parsed['path']) ? $parsed['path'] : '/';
            $query = isset($parsed['query']) ? $parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? $parsed['fragment'] : '';

            return mits_imageslider_make_relative_shop_url($path, $query, $fragment);
        }

        if (strpos($url, '/') === 0) {
            $parsed = @parse_url($url);
            if (!is_array($parsed)) {
                return false;
            }
            $path = isset($parsed['path']) ? $parsed['path'] : '/';
            $query = isset($parsed['query']) ? $parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? $parsed['fragment'] : '';

            return mits_imageslider_make_relative_shop_url($path, $query, $fragment);
        }

        return false;
    }
}



if (!function_exists('mits_imageslider_is_relative_shop_url')) {
    function mits_imageslider_is_relative_shop_url($url)
    {
        $url = trim((string)$url);
        if ($url == '') {
            return false;
        }

        // Absolute URLs und protocol-relative URLs werden separat behandelt.
        if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
            return false;
        }

        // modified SEO-URLs koennen wie ein Schema aussehen, z.B.:
        // Mueslimischungen:::1.html, Inhalt:_:12.html oder Hersteller:.:4.html.
        // Diese Pruefung muss vor der allgemeinen Schema-Pruefung kommen.
        if (preg_match('~(?:/{0,1}[a-z]{2}/)?[^?#]*(?:::{1}|(?<!:):{2}(?!:)|:_:|:\.:|---|(?<!-)--(?!-)|-_-|-\.-)[^?#]*\.(?:html|htm)(?:[?#].*)?$~i', $url)) {
            return true;
        }

        // Andere echte Schemata wie mailto:, tel:, javascript: usw. sind keine Shoplinks.
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return false;
        }

        // Klassische relative Shopdateien muessen vor der Domain-Pruefung erkannt werden,
        // sonst wuerde index.php faelschlich wie eine Domain ohne Schema behandelt.
        if (preg_match('~^(?:index|product_info|shop_content|popup_image|popup_coupon_help|advanced_search|specials|products_new|account|login|checkout_[a-z0-9_]+)\.php(?:[?#].*)?$~i', $url)) {
            return true;
        }

        // Sonstige statische SEO-Links ohne Domain.
        if (preg_match('~^[^?#]+\.(?:html|htm)(?:[?#].*)?$~i', $url)) {
            return true;
        }

        // Domains ohne Schema sind externe Links, z.B. www.domain.de oder domain.de/pfad.
        if (preg_match('~^[a-z0-9.-]+\.[a-z0-9-]+(?::[0-9]+)?(?:[/?#]|$)~i', $url)) {
            return false;
        }

        return false;
    }
}

if (!function_exists('mits_imageslider_query_param')) {
    function mits_imageslider_query_param($params, $name)
    {
        if (!is_array($params) || $name == '') {
            return '';
        }

        foreach ($params as $key => $value) {
            if (strtolower((string)$key) == strtolower((string)$name)) {
                return is_array($value) ? '' : trim((string)$value);
            }
        }

        return '';
    }
}

if (!function_exists('mits_imageslider_query_has_only')) {
    function mits_imageslider_query_has_only($params, $allowed)
    {
        if (!is_array($params)) {
            return true;
        }

        $allowed = array_map('strtolower', (array)$allowed);
        $allowed[] = 'language';
        $allowed = array_unique($allowed);

        foreach ($params as $key => $value) {
            $key = strtolower((string)$key);
            if ($key === '') {
                continue;
            }
            if (!in_array($key, $allowed)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('mits_imageslider_last_numeric_id')) {
    function mits_imageslider_last_numeric_id($value)
    {
        $value = trim((string)$value);
        if ($value == '') {
            return 0;
        }

        if (preg_match_all('#[0-9]+#', $value, $matches) && !empty($matches[0])) {
            return (int)end($matches[0]);
        }

        return 0;
    }
}

if (!function_exists('mits_imageslider_first_numeric_id')) {
    function mits_imageslider_first_numeric_id($value)
    {
        $value = trim((string)$value);
        if ($value == '') {
            return 0;
        }

        if (preg_match('#[0-9]+#', $value, $match)) {
            return (int)$match[0];
        }

        return 0;
    }
}

if (!function_exists('mits_imageslider_parse_url_query')) {
    function mits_imageslider_parse_url_query($query)
    {
        $params = array();
        $query = html_entity_decode((string)$query, ENT_QUOTES, 'UTF-8');
        if ($query != '') {
            parse_str($query, $params);
        }
        return $params;
    }
}

if (!function_exists('mits_imageslider_split_relative_url')) {
    function mits_imageslider_split_relative_url($url)
    {
        $fragment = '';
        $query = '';
        $path = (string)$url;

        if (strpos($path, '#') !== false) {
            $fragment = substr($path, strpos($path, '#') + 1);
            $path = substr($path, 0, strpos($path, '#'));
        }
        if (strpos($path, '?') !== false) {
            $query = substr($path, strpos($path, '?') + 1);
            $path = substr($path, 0, strpos($path, '?'));
        }

        return array(
            'path' => $path,
            'query' => $query,
            'fragment' => $fragment,
            'params' => mits_imageslider_parse_url_query($query),
        );
    }
}

if (!function_exists('mits_imageslider_extract_seo_entity_url')) {
    function mits_imageslider_extract_seo_entity_url($path, $preferred_typ = 0)
    {
        $preferred_typ = (int)$preferred_typ;
        $decoded_path = rawurldecode(trim((string)$path));
        $decoded_path = ltrim($decoded_path, '/');

        if ($decoded_path == '') {
            return false;
        }

        // modified SEO-Kategorien mit Doppelpunkt-Delimiter:
        // de/Name:::1.html, Name:::1.html, Name:::1:2.html
        if (preg_match('~:::([_0-9]+)(?::([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false; // page wuerde bei Linkart Kategorie verloren gehen, daher als Intern speichern.
            }
            $detected_id = mits_imageslider_last_numeric_id($match[1]);
            if ($detected_id > 0) {
                return array('url' => (string)$detected_id, 'typ' => 3);
            }
            return false;
        }

        // modified SEO-Kategorien mit Bindestrich-Delimiter:
        // de/Name---1.html, Name---1.html, Name---1-2.html
        if (preg_match('~---([_0-9]+)(?:-([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false;
            }
            $detected_id = mits_imageslider_last_numeric_id($match[1]);
            if ($detected_id > 0) {
                return array('url' => (string)$detected_id, 'typ' => 3);
            }
            return false;
        }

        // MITS FAQ Manager mit Doppelpunkt-Delimiter:
        // de/Name:_:12:slug:c3.html / de/Name:_:12:slug:f4.html
        // Diese URLs enthalten Zusatzparameter. Daher nicht auf Content-ID reduzieren.
        if (preg_match('~:_:(\d+):[^?#]+:[cf](\d+)\.(?:html|htm)$~i', $decoded_path)) {
            return false;
        }

        // MITS FAQ Manager mit Bindestrich-Delimiter:
        // de/Name-_-12-slug-c3.html / de/Name-_-12-slug-f4.html
        if (preg_match('~-_-(\d+)-[^?#]+-[cf](\d+)\.(?:html|htm)$~i', $decoded_path)) {
            return false;
        }

        // modified SEO-Content mit Doppelpunkt-Delimiter:
        // de/Name:_:12.html, Name:_:12.html, Name:_:12:2.html
        if (preg_match('~:_:(\d+)(?::([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false;
            }
            return array('url' => (string)(int)$match[1], 'typ' => 4);
        }

        // modified SEO-Content mit Bindestrich-Delimiter:
        // de/Name-_-12.html, Name-_-12.html, Name-_-12-2.html
        if (preg_match('~-_-(\d+)(?:-([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false;
            }
            return array('url' => (string)(int)$match[1], 'typ' => 4);
        }

        // modified SEO-Hersteller mit Doppelpunkt-Delimiter:
        // de/Name:.:4.html, Name:.:4.html, Name:.:4:2.html
        if (preg_match('~:\.:([_0-9]+)(?::([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false;
            }
            $detected_id = mits_imageslider_last_numeric_id($match[1]);
            if ($detected_id > 0) {
                return array('url' => (string)$detected_id, 'typ' => 5);
            }
            return false;
        }

        // modified SEO-Hersteller mit Bindestrich-Delimiter:
        // de/Name-.-4.html, Name-.-4.html, Name-.-4-2.html
        if (preg_match('~-\.-([_0-9]+)(?:-([_0-9]+))?\.(?:html|htm)$~i', $decoded_path, $match)) {
            if (isset($match[2]) && $match[2] !== '') {
                return false;
            }
            $detected_id = mits_imageslider_last_numeric_id($match[1]);
            if ($detected_id > 0) {
                return array('url' => (string)$detected_id, 'typ' => 5);
            }
            return false;
        }

        // modified SEO-Produkte mit Doppelpunkt-Delimiter:
        // de/Name::123.html, Name::123.html
        if (preg_match('~(?<!:):{2}(?!:)([^?#]+?)\.(?:html|htm)$~i', $decoded_path, $match)) {
            $detected_id = mits_imageslider_first_numeric_id($match[1]);
            if ($detected_id > 0) {
                if (in_array($preferred_typ, array(2, 4, 5))) {
                    return array('url' => (string)$detected_id, 'typ' => $preferred_typ);
                }
                return array('url' => (string)$detected_id, 'typ' => 2);
            }
            return false;
        }

        // modified SEO-Produkte mit Bindestrich-Delimiter:
        // de/Name--123.html, Name--123.html
        if (preg_match('~(?<!-)--(?!-)([^?#]+?)\.(?:html|htm)$~i', $decoded_path, $match)) {
            $detected_id = mits_imageslider_first_numeric_id($match[1]);
            if ($detected_id > 0) {
                if (in_array($preferred_typ, array(2, 4, 5))) {
                    return array('url' => (string)$detected_id, 'typ' => $preferred_typ);
                }
                return array('url' => (string)$detected_id, 'typ' => 2);
            }
            return false;
        }

        return false;
    }
}

if (!function_exists('mits_imageslider_extract_entity_url')) {
    function mits_imageslider_extract_entity_url($url, $preferred_typ = 0)
    {
        $url = trim((string)$url);
        $preferred_typ = (int)$preferred_typ;

        if ($url == '') {
            return false;
        }

        $relative_url = mits_imageslider_extract_internal_url($url);
        if ($relative_url === false) {
            $relative_url = $url;
        }

        $relative_url = mits_imageslider_strip_session_from_url($relative_url);
        $relative_url = ltrim($relative_url, '/');

        // SEO-URLs duerfen nicht direkt mit parse_url() zerlegt werden, wenn sie
        // Doppelpunkt-Delimiter verwenden. Der Teil vor dem ersten Doppelpunkt
        // wuerde sonst als Schema interpretiert.
        $split = mits_imageslider_split_relative_url($relative_url);
        $path = isset($split['path']) ? trim((string)$split['path']) : '';
        $query = isset($split['query']) ? (string)$split['query'] : '';
        $params = isset($split['params']) ? $split['params'] : array();

        $seo_entity = mits_imageslider_extract_seo_entity_url($path, $preferred_typ);
        if ($seo_entity !== false && mits_imageslider_query_has_only($params, array())) {
            return $seo_entity;
        }

        // Klassische URLs mit Query-Parametern.
        $products_id_value = mits_imageslider_query_param($params, 'products_id');
        $products_id = mits_imageslider_first_numeric_id($products_id_value);
        if ($products_id > 0 && mits_imageslider_query_has_only($params, array('products_id'))) {
            return array('url' => (string)$products_id, 'typ' => 2);
        }

        $manufacturers_id_value = mits_imageslider_query_param($params, 'manufacturers_id');
        $manufacturers_id = mits_imageslider_last_numeric_id($manufacturers_id_value);
        if ($manufacturers_id > 0 && mits_imageslider_query_has_only($params, array('manufacturers_id'))) {
            return array('url' => (string)$manufacturers_id, 'typ' => 5);
        }

        $content_id_value = mits_imageslider_query_param($params, 'coID');
        if ($content_id_value == '') {
            $content_id_value = mits_imageslider_query_param($params, 'content_id');
        }
        $content_id = mits_imageslider_last_numeric_id($content_id_value);
        if ($content_id > 0 && mits_imageslider_query_has_only($params, array('coid', 'content_id'))) {
            return array('url' => (string)$content_id, 'typ' => 4);
        }

        $category_id = 0;
        $cpath = mits_imageslider_query_param($params, 'cPath');
        if ($cpath != '') {
            $category_id = mits_imageslider_last_numeric_id($cpath);
        }
        if ($category_id <= 0) {
            $cat = mits_imageslider_query_param($params, 'cat');
            if ($cat != '' && preg_match('~(?:^|[_-])c([0-9]+)(?:[_-]|$)~i', $cat, $match)) {
                $category_id = (int)$match[1];
            }
        }
        if ($category_id > 0 && mits_imageslider_query_has_only($params, array('cpath', 'cat'))) {
            return array('url' => (string)$category_id, 'typ' => 3);
        }

        // Fallback: Wenn der Benutzer bewusst eine konkrete Linkart gewaehlt hat
        // und eine einfache HTML-URL mit ID verwendet, kann die ID uebernommen werden.
        // URL-Varianten mit eindeutigem Zusatz wie page/faq/category werden oben bewusst
        // nicht reduziert, damit keine Parameter verloren gehen.
        if ($preferred_typ >= 2 && $preferred_typ <= 5 && mits_imageslider_query_has_only($params, array())) {
            $decoded_path = rawurldecode($path);
            if (preg_match('~([0-9]+)\.(?:html|htm)$~i', $decoded_path, $match)) {
                return array('url' => (string)(int)$match[1], 'typ' => $preferred_typ);
            }
        }

        return false;
    }
}

if (!function_exists('mits_imageslider_prepare_url_for_save')) {
    function mits_imageslider_prepare_url_for_save($url, $url_typ)
    {
        $url = html_entity_decode(trim((string)$url), ENT_QUOTES, 'UTF-8');
        $url = str_replace(array("\r", "\n", "\t"), '', $url);
        $url = mits_imageslider_strip_session_from_url($url);
        $url_typ = (int)$url_typ;

        if ($url == '') {
            return array('url' => '', 'typ' => $url_typ);
        }

        // Produkt-, Kategorie-, Content- und Hersteller-Links bleiben ID-basiert,
        // wenn im Feld wirklich nur eine ID steht.
        if ($url_typ >= 2 && $url_typ <= 5 && preg_match('#^[0-9]+$#', $url)) {
            return array('url' => $url, 'typ' => $url_typ);
        }

        // Vollstaendige oder relative Shoplinks aus der Browser-Adressleiste erkennen
        // und nach Moeglichkeit in die passende ID-Linkart umwandeln.
        $entity_url = mits_imageslider_extract_entity_url($url, $url_typ);
        if ($entity_url !== false) {
            return $entity_url;
        }

        $internal_url = mits_imageslider_extract_internal_url($url);
        if ($internal_url !== false) {
            return array('url' => $internal_url, 'typ' => 1);
        }

        if (mits_imageslider_is_relative_shop_url($url)) {
            return array('url' => $url, 'typ' => 1);
        }

        if (strpos($url, '//') === 0) {
            $url = 'https:' . $url;
        }

        if (!mits_imageslider_is_allowed_url_scheme($url)) {
            return array('url' => '', 'typ' => 0);
        }

        if (preg_match('#^(mailto|tel):#i', $url)) {
            return array('url' => $url, 'typ' => 0);
        }

        if (preg_match('#^https?://#i', $url)) {
            return array('url' => $url, 'typ' => 0);
        }

        if (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $url)
          && preg_match('~^[a-z0-9.-]+\.[a-z0-9-]+(?::[0-9]+)?(?:[/?#]|$)~i', $url)
        ) {
            $url = 'https://' . $url;
            return array('url' => $url, 'typ' => 0);
        }

        return array('url' => $url, 'typ' => $url_typ);
    }
}

if (!function_exists('xtc_get_default_table_data')) {
    function xtc_get_default_table_data($table)
    {
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
