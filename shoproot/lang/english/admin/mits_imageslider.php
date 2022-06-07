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

defined('HEADING_TITLE_IMAGESLIDERS') or define('HEADING_TITLE_IMAGESLIDERS', 'MITS ImageSlider <small style="font-weight:normal;font-size:0.6em;">&copy; 2008-' . date('Y') . ' by <a href="http://www.merz-it-service.de/" target="_blank">Hetfield</a></small>');
defined('HEADING_SUBTITLE_IMAGESLIDERS') or define('HEADING_SUBTITLE_IMAGESLIDERS', '<a href="https://www.merz-it-service.de/" target="_blank"><img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;max-height:40px;margin-top:6px;margin-bottom:6px;" /></a>');
defined('TABLE_HEADING_IMAGESLIDERS') or define('TABLE_HEADING_IMAGESLIDERS', 'MITS ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'ImageSlider Name');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Image');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'ImageSlider-Group');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Sorting');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Status');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Action');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'New image');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Edit image');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Delte image');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Image:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'added:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', 'last modified:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'Image does not exist');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Insert a new image with all datas.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'Title for image:');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Name for image entry:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Image:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Image for Tablets (600px - 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Image for Mobile View (- 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'Image links to the following url:');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Window target:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Linktype:');
defined('TEXT_URL') or define('TEXT_URL', 'Link-URL:');
defined('NONE_TARGET') or define('NONE_TARGET', 'no target');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'URL for a product (please insert only the productsID at Link-URL)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'URL for a categorie (please insert only the catID at Link-URL)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'URL for a contentsite (please insert only the coID at Link-URL)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'URL for a manufacturer (please insert only the mID at Link-URL)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Internal Shop-URL (e.g. account.php or newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'External URL (e.g. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Image-description:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', 'Also delete image?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: The directory %s is not writeable. Please correct the access rights to this directory!');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: The directory %s does not exist!');
defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> ImageSlider-Eintr&auml;gen)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Aktiv');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Aktivieren');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Nicht aktiv');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'Deaktivieren');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Active');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'Not active');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'ImageSlider Group:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Choose an existing ImageSlider group (if exists) or enter a new ImageSlider group below.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'To display a ImageSlider in the template, the template must be extended<br/>Example: ImageSlider-Group <i>MITS_IMAGESLIDER</i>, the banner can be displayed in the template in the index.html with <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Tutorial for MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Tutorial for MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Error: ImageSlider title required.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Error: ImageSlider group required.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Error: ImageSlider image required.');

defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'JJJJ-MM-TT');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'Expires on:');
defined('TEXT_IMAGESLIDERS_IMPRESSIONS') or define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impressions/views.');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'Scheduled At:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Expiry Notes:</b><ul><li>Only one of the two fields should be submitted</li><li>If the sliderimage is not to expire automatically, then leave these fields blank</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Schedule Notes:</b><ul><li>If a schedule is set, the sliderimage will be activated on that date.</li><li>All scheduled sliderimages are marked as deactive until their date has arrived, to which they will then be marked active.</li></ul>');

defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Date added:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'Scheduled at: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'Expires at: <b>%s</b>');
?>