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
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'ImageSlider naam');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Afbeelding');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'ImageSlider-groep');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Sortering');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Status');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Actie');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'Nieuwe afbeelding');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Afbeelding bewerken');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Afbeelding verwijderen');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Afbeelding:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'toegevoegd op:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', 'laatst gewijzigd:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'Afbeelding bestaat niet');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Voeg de nieuwe afbeelding met alle relevante gegevens toe.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Voer de noodzakelijke wijzigingen uit');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'Titel voor afbeelding:');
defined('TEXT_IMAGESLIDERS_ALT') or define('TEXT_IMAGESLIDERS_ALT', 'Alt-tekst voor afbeelding: <small style="font-weight:normal">(&lt;img <b>alt=""</b>&gt;)</small>');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Naam voor afbeeldingsitem:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Afbeelding:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Afbeelding voor tablets (600px - 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Afbeelding voor mobiele weergave (- 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'Afbeelding linkt naar de volgende URL:');
defined('TEXT_IMAGESLIDERS_LINKTITLE') or define('TEXT_IMAGESLIDERS_LINKTITLE', 'Titel voor link: <small style="font-weight:normal">(&lt;a <b>title=""</b>&gt;)</small>');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Doelvenster:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Linktype:');
defined('TEXT_URL') or define('TEXT_URL', 'Link-URL:');
defined('NONE_TARGET') or define('NONE_TARGET', 'geen doel');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'Link naar product (voer alleen de productsID in bij Link-URL)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'Link naar categorie (voer alleen de catID in bij Link-URL)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'Link naar contentpagina (voer alleen de coID in bij Link-URL)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'Link naar fabrikant (voer alleen de mID in bij Link-URL)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Interne shoplink (bijv. account.php of newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'Externe link (bijv. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Afbeeldingsbeschrijving:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', 'Weet u zeker dat u deze afbeelding wilt verwijderen?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', 'Ook het afbeeldingsbestand verwijderen?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Fout: de map %s is niet beschrijfbaar. Corrigeer de toegangsrechten.');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Fout: de map %s bestaat niet.');
defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Getoond worden <b>%d</b> tot <b>%d</b> (van in totaal <b>%d</b> ImageSlider-items)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Actief');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Activeren');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Niet actief');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'Deactiveren');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Activeren');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'Deactiveren');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'ImageSlider-groep:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Kies een bestaande ImageSlider-groep of voer hieronder een nieuwe ImageSlider-groep in.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Om een ImageSlider in de template weer te geven, moet de template worden uitgebreid.<br/>Voorbeeld: ImageSlider-groep <i>MITS_IMAGESLIDER</i>, in index.html weer te geven met <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Handleiding MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Handleiding MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Fout: ImageSlider-titel verplicht.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Fout: ImageSlider-groep verplicht.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Fout: ImageSlider-afbeelding verplicht.');
defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'JJJJ-MM-DD');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'Geldig tot:');
defined('TEXT_IMAGESLIDERS_IMPRESSIONS') or define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impressies/weergaven.');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'Geldig vanaf:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Opmerkingen geldigheid tot:</b><ul><li>Start- en einddatum kunnen worden gecombineerd.</li><li>Laat deze velden leeg als de sliderafbeelding zonder datumlimiet moet worden weergegeven.</li><li>De handmatige status wordt niet automatisch gewijzigd.</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Opmerkingen planning:</b><ul><li>Als een startdatum is ingesteld, wordt de sliderafbeelding vanaf die datum weergegeven.</li><li>De datumcontrole gebeurt in de frontend zonder automatische statuswijziging.</li></ul>');
defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Datum toegevoegd:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'Geldig vanaf: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'Geldig tot: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_RECURRING') or define('TEXT_IMAGESLIDERS_RECURRING', 'Terugkerende geldigheid:');
defined('TEXT_IMAGESLIDERS_RECURRING_YEARLY') or define('TEXT_IMAGESLIDERS_RECURRING_YEARLY', 'jaarlijks herhalen');
defined('TEXT_IMAGESLIDERS_RECURRING_NOTE') or define('TEXT_IMAGESLIDERS_RECURRING_NOTE', '<b>Terugkerende geldigheid:</b><ul><li>Indien geactiveerd, worden alleen dag en maand van Geldig vanaf/tot gebruikt.</li><li>Voorbeeld: 2026-12-24 tot 2027-01-06 wordt elk jaar van 24.12. tot 06.01. weergegeven.</li><li>De handmatige status blijft de hoofdschakelaar.</li></ul>');
defined('MITS_IMAGESLIDER_IMPORT_HEADING') or define('MITS_IMAGESLIDER_IMPORT_HEADING', 'MITS ImageSlider - Banner Manager import');
defined('MITS_IMAGESLIDER_IMPORT_INTRO') or define('MITS_IMAGESLIDER_IMPORT_INTRO', 'Deze tool importeert bannergroepen en banneritems uit de Banner Manager in MITS ImageSlider.');
defined('MITS_IMAGESLIDER_IMPORT_DRY_RUN') or define('MITS_IMAGESLIDER_IMPORT_DRY_RUN', 'Voorbeeld / dry run');
defined('MITS_IMAGESLIDER_IMPORT_EXECUTE') or define('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Import uitvoeren');
defined('MITS_IMAGESLIDER_IMPORT_OVERWRITE') or define('MITS_IMAGESLIDER_IMPORT_OVERWRITE', 'Reeds ge&iuml;mporteerde banners bijwerken');
defined('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER') or define('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER', 'Bannergroep filteren');
defined('MITS_IMAGESLIDER_IMPORT_RESULT') or define('MITS_IMAGESLIDER_IMPORT_RESULT', 'Importresultaat');
defined('MITS_IMAGESLIDER_IMPORT_TOTAL') or define('MITS_IMAGESLIDER_IMPORT_TOTAL', 'Totaal');
defined('MITS_IMAGESLIDER_IMPORT_IMPORTED') or define('MITS_IMAGESLIDER_IMPORT_IMPORTED', 'Ge&iuml;mporteerd');
defined('MITS_IMAGESLIDER_IMPORT_UPDATED') or define('MITS_IMAGESLIDER_IMPORT_UPDATED', 'Bijgewerkt');
defined('MITS_IMAGESLIDER_IMPORT_SKIPPED') or define('MITS_IMAGESLIDER_IMPORT_SKIPPED', 'Overgeslagen');
defined('MITS_IMAGESLIDER_IMPORT_ERRORS') or define('MITS_IMAGESLIDER_IMPORT_ERRORS', 'Fouten');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE', 'De banner-tabel is niet gevonden.');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS', 'Geen banners gevonden.');
defined('MITS_IMAGESLIDER_IMPORT_BACK') or define('MITS_IMAGESLIDER_IMPORT_BACK', 'Terug naar ImageSlider');
