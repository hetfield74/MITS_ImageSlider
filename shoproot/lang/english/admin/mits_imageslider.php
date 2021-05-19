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

define('HEADING_TITLE_IMAGESLIDERS', 'MITS ImageSlider <small style="font-weight:normal;font-size:0.6em;">&copy; 2008-' . date('Y') . ' by <a href="http://www.merz-it-service.de/" target="_blank">Hetfield</a></small>');
define('HEADING_SUBTITLE_IMAGESLIDERS', '<a href="https://www.merz-it-service.de/" target="_blank"><img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;max-height:40px;margin-top:6px;margin-bottom:6px;" /></a>');
define('TABLE_HEADING_IMAGESLIDERS', 'MITS ImageSlider');
define('TABLE_HEADING_IMAGESLIDERS_NAME', 'ImageSlider Name');
define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Image');
define('TABLE_HEADING_SLIDERGROUP', 'ImageSlider-Group');
define('TABLE_HEADING_SORTING', 'Sorting');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_HEADING_NEW_IMAGESLIDER', 'New image');
define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Edit image');
define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Delte image');
define('TEXT_IMAGESLIDERS', 'Image:');
define('TEXT_DATE_ADDED', 'added:');
define('TEXT_LAST_MODIFIED', 'last modified:');
define('TEXT_IMAGE_NONEXISTENT', 'Image does not exist');
define('TEXT_NEW_INTRO', 'Insert a new image with all datas.');
define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_IMAGESLIDERS_TITLE', 'Title for image:');
define('TEXT_IMAGESLIDERS_NAME', 'Name for image entry:');
define('TEXT_IMAGESLIDERS_IMAGE', 'Image:');
define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Image for Tablets (600px - 1023px):');
define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Image for Mobile View (- 600px):');
define('TEXT_IMAGESLIDERS_URL', 'Image links to the following url:');
define('TEXT_TARGET', 'Window target:');
define('TEXT_TYP', 'Linktype:');
define('TEXT_URL', 'Link-URL:');
define('NONE_TARGET', 'no target');
define('TARGET_BLANK', '_blank');
define('TARGET_TOP', '_top');
define('TARGET_SELF', '_self');
define('TARGET_PARENT', '_parent');
define('TYP_PRODUCT', 'URL for a product (please insert only the productsID at Link-URL)');
define('TYP_CATEGORIE', 'URL for a categorie (please insert only the catID at Link-URL)');
define('TYP_CONTENT', 'URL for a contentsite (please insert only the coID at Link-URL)');
define('TYP_INTERN', 'Internal Shop-URL (e.g. account.php or newsletter.php)');
define('TYP_EXTERN', 'External URL (e.g. http://www.example.org)');
define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Image-description:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this?');
define('TEXT_DELETE_IMAGE', 'Also delete image?');
define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: The directory %s is not writeable. Please correct the access rights to this directory!');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: The directory %s does not exist!');
define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> ImageSlider-Eintr&auml;gen)');
define('IMAGE_ICON_STATUS_GREEN', 'Aktiv');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Aktivieren');
define('IMAGE_ICON_STATUS_RED', 'Nicht aktiv');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Deaktivieren');
define('MITS_ACTIVE', 'Active');
define('MITS_NOTACTIVE', 'Not active');
define('TEXT_IMAGESLIDERS_GROUP', 'ImageSlider Group:');
define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Choose an existing ImageSlider group (if exists) or enter a new ImageSlider group below.');
define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'To display a ImageSlider in the template, the template must be extended<br/>Example: ImageSlider-Group <i>MITS_IMAGESLIDER</i>, the banner can be displayed in the template in the index.html with <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Tutorial for MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Tutorial for MITS ImageSlider &raquo;</u></strong></a>');
define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Error: ImageSlider title required.');
define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Error: ImageSlider group required.');
define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Error: ImageSlider image required.');

define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'JJJJ-MM-TT');
define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'Expires on:');
define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impressions/views.');
define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'Scheduled At:');
define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Expiry Notes:</b><ul><li>Only one of the two fields should be submitted</li><li>If the sliderimage is not to expire automatically, then leave these fields blank</li></ul>');
define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Schedule Notes:</b><ul><li>If a schedule is set, the sliderimage will be activated on that date.</li><li>All scheduled sliderimages are marked as deactive until their date has arrived, to which they will then be marked active.</li></ul>');

define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Date added:');
define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'Scheduled at: <b>%s</b>');
define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'Expires at: <b>%s</b>');
?>