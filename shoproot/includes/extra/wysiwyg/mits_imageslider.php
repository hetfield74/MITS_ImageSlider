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

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true' && isset($type)) {
    $language_id = (isset($langID) && !isset($language_id)) ? $langID : ($language_id ?? '');
    if (!empty($language_id)) {
        switch ($type) {
            case 'imagesliders_description':
                $editorName = 'imagesliders_description[' . $language_id . ']';
                $default_editor_height = 200;
                break;
        }
    }
}