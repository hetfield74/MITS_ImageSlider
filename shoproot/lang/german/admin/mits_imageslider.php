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

defined('HEADING_TITLE_IMAGESLIDERS') or define('HEADING_TITLE_IMAGESLIDERS', 'MITS ImageSlider <small style="font-weight:normal;font-size:0.6em;">&copy; 2008-' . date('Y') . ' by <a href="https://www.merz-it-service.de/" target="_blank">Hetfield</a></small>');
defined('HEADING_SUBTITLE_IMAGESLIDERS') or define('HEADING_SUBTITLE_IMAGESLIDERS', '<a href="https://www.merz-it-service.de/" target="_blank"><img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;max-height:40px;margin-top:6px;margin-bottom:6px;" /></a>');
defined('TABLE_HEADING_IMAGESLIDERS') or define('TABLE_HEADING_IMAGESLIDERS', 'MITS ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'ImageSlider Name');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Bild');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'ImageSlider-Gruppe');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Sortierung');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Status');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Aktion');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'Neues Bild');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Bild bearbeiten');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Bild l&ouml;schen');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Bild:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'hinzugef&uuml;gt am:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', 'letzte &Auml;nderung am:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'BILD NICHT VORHANDEN');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Bitte legen Sie das neue Bild mit allen relevanten Daten ein.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'Title f&uuml;r Bild:');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Name f&uuml;r Bildeintrag:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Bild:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Bild f&uuml;r Tablets (600px bis 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Bild f&uuml;r Mobile Ansicht (bis 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'Bild verlinken: ');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Zielfenster:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Linkart:');
defined('TEXT_URL') or define('TEXT_URL', 'Linkadresse:');
defined('NONE_TARGET') or define('NONE_TARGET', 'Kein Ziel festlegen');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'Link zum Produkt (bitte nur die productsID bei Linkadresse eintragen)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'Link zur Kategorie (bitte nur die catID bei Linkadresse eintragen)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'Link zur Contentseite (bitte nur die coID bei Linkadresse eintragen)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'Link zum Hersteller (bitte nur die mID bei Linkadresse eintragen)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Interner Shoplink (z.B. account.php oder newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'Externer Link (z.B. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Bildbeschreibung:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', 'Sind Sie sicher, dass Sie dieses Bild l&ouml;schen m&ouml;chten?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', 'Bild ebenfalls l&ouml;schen?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnis %s ist schreibgesch&uuml;tzt. Bitte korrigieren Sie die Zugriffsrechte zu diesem Verzeichnis!');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis %s existiert nicht!');
defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> ImageSlider-Eintr&auml;gen)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Aktiv');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Aktivieren');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Nicht aktiv');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'Deaktivieren');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Aktivieren');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'Deaktivieren');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'ImageSlider-Gruppe:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'W&auml;hlen Sie im Dropdown-Feld die gew&uuml;nschte ImageSlider-Gruppe aus (falls vorhanden) oder geben Sie im Textfeld darunter eine neue ImageSlider-Gruppe ein.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Damit ein ImageSlider im Template angezeigt wird, muss das Template erweitert werden. Beispiel: ImageSlider-Gruppe ist <i>MITS_IMAGESLIDER</i>, dann kann im Template in der index.html dieser ImageSlider mit der Smarty-Variable <i>{$MITS_IMAGESLIDER}</i> an der gew&uuml;nschten Position angezeigt werden.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Anleitung f&uuml;r das Modul MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Anleitung f&uuml;r das Modul MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Fehler: Ein Titel wird ben&ouml;tigt.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Fehler: Eine ImageSlider-Gruppe wird ben&ouml;tigt.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Fehler: Ein Bild wird selbstverst&auml;ndlich ben&ouml;tigt.');

defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'JJJJ-MM-TT');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'G&uuml;ltigkeit bis:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'G&uuml;ltigkeit ab:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>G&uuml;ltigkeit Bemerkung:</b><ul><li>Nur ein Feld ausf&uuml;llen!</li><li>Wenn das Sliderbild unbegrenzt angezeigt werden soll, tragen Sie in diesen Feldern nichts ein.</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>G&uuml;ltigkeit ab Bemerkung:</b><ul><li>Bei Verwendung dieser Funktion, wird das Sliderbild erst ab dem angegeben Datum angezeigt.</li><li>Alle Sliderbilder mit dieser Funktion werden bis zu ihrer Aktivierung als deaktiviert angezeigt.</li></ul>');

defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'hinzugef&uuml;gt am:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'G&uuml;ltigkeit ab: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'G&uuml;ltigkeit bis zum: <b>%s</b>');
