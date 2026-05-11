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
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'Nome ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Immagine');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'Gruppo ImageSlider');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Ordinamento');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Stato');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Azione');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'Nuova immagine');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Modifica immagine');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Elimina immagine');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Immagine:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'aggiunta il:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', 'ultima modifica:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'Immagine non esistente');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Inserire la nuova immagine con tutti i dati necessari.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Effettuare le modifiche necessarie');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'Titolo per immagine:');
defined('TEXT_IMAGESLIDERS_ALT') or define('TEXT_IMAGESLIDERS_ALT', 'Testo alt per immagine: <small style="font-weight:normal">(&lt;img <b>alt=""</b>&gt;)</small>');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Nome per voce immagine:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Immagine:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Immagine per tablet (600px - 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Immagine per vista mobile (- 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'Immagine collegata al seguente URL:');
defined('TEXT_IMAGESLIDERS_LINKTITLE') or define('TEXT_IMAGESLIDERS_LINKTITLE', 'Titolo del link: <small style="font-weight:normal">(&lt;a <b>title=""</b>&gt;)</small>');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Finestra di destinazione:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Tipo di link:');
defined('TEXT_URL') or define('TEXT_URL', 'URL del link:');
defined('NONE_TARGET') or define('NONE_TARGET', 'nessuna destinazione');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'Link a un prodotto (inserire solo la productsID nell&rsquo;URL del link)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'Link a una categoria (inserire solo la catID nell&rsquo;URL del link)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'Link a una pagina contenuto (inserire solo la coID nell&rsquo;URL del link)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'Link a un produttore (inserire solo la mID nell&rsquo;URL del link)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Link interno del negozio (es. account.php o newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'Link esterno (es. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Descrizione immagine:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', 'Sei sicuro di voler eliminare questa immagine?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', 'Eliminare anche il file immagine?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Errore: la directory %s non &egrave; scrivibile. Correggere i permessi di accesso!');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Errore: la directory %s non esiste!');
defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Visualizzazione da <b>%d</b> a <b>%d</b> (di <b>%d</b> voci ImageSlider)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Attivo');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Attiva');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Non attivo');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'Disattiva');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Attiva');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'Disattiva');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'Gruppo ImageSlider:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Scegliere un gruppo ImageSlider esistente oppure inserire sotto un nuovo gruppo ImageSlider.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Per visualizzare un ImageSlider nel template, il template deve essere adattato.<br/>Esempio: gruppo ImageSlider <i>MITS_IMAGESLIDER</i>, visualizzabile in index.html con <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Istruzioni MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Istruzioni MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Errore: titolo ImageSlider richiesto.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Errore: gruppo ImageSlider richiesto.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Errore: immagine ImageSlider richiesta.');
defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'AAAA-MM-GG');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'Valido fino al:');
defined('TEXT_IMAGESLIDERS_IMPRESSIONS') or define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impressioni/visualizzazioni.');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'Valido dal:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Note sulla scadenza:</b><ul><li>Data di inizio e fine possono essere combinate.</li><li>Lasciare questi campi vuoti se l&rsquo;immagine deve essere visualizzata senza limiti di data.</li><li>Lo stato manuale non viene modificato automaticamente.</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Note sulla pianificazione:</b><ul><li>Se viene impostata una data, l&rsquo;immagine viene mostrata da tale data.</li><li>Il controllo delle date avviene nel frontend senza modificare lo stato manuale.</li></ul>');
defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Data aggiunta:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'Valido dal: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'Valido fino al: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_RECURRING') or define('TEXT_IMAGESLIDERS_RECURRING', 'Validit&agrave; ricorrente:');
defined('TEXT_IMAGESLIDERS_RECURRING_YEARLY') or define('TEXT_IMAGESLIDERS_RECURRING_YEARLY', 'ripeti annualmente');
defined('TEXT_IMAGESLIDERS_RECURRING_NOTE') or define('TEXT_IMAGESLIDERS_RECURRING_NOTE', '<b>Validit&agrave; ricorrente:</b><ul><li>Se attiva, vengono usati solo giorno e mese di Valido dal/fino al.</li><li>Esempio: 2026-12-24 fino a 2027-01-06 viene mostrato ogni anno dal 24.12. al 06.01.</li><li>Lo stato manuale rimane l&rsquo;interruttore principale.</li></ul>');
defined('MITS_IMAGESLIDER_IMPORT_HEADING') or define('MITS_IMAGESLIDER_IMPORT_HEADING', 'MITS ImageSlider - Importazione Banner Manager');
defined('MITS_IMAGESLIDER_IMPORT_INTRO') or define('MITS_IMAGESLIDER_IMPORT_INTRO', 'Questo strumento importa gruppi e voci banner dal Banner Manager in MITS ImageSlider.');
defined('MITS_IMAGESLIDER_IMPORT_DRY_RUN') or define('MITS_IMAGESLIDER_IMPORT_DRY_RUN', 'Anteprima / dry run');
defined('MITS_IMAGESLIDER_IMPORT_EXECUTE') or define('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Esegui importazione');
defined('MITS_IMAGESLIDER_IMPORT_OVERWRITE') or define('MITS_IMAGESLIDER_IMPORT_OVERWRITE', 'Aggiorna banner gi&agrave; importati');
defined('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER') or define('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER', 'Filtra gruppo banner');
defined('MITS_IMAGESLIDER_IMPORT_RESULT') or define('MITS_IMAGESLIDER_IMPORT_RESULT', 'Risultato importazione');
defined('MITS_IMAGESLIDER_IMPORT_TOTAL') or define('MITS_IMAGESLIDER_IMPORT_TOTAL', 'Totale');
defined('MITS_IMAGESLIDER_IMPORT_IMPORTED') or define('MITS_IMAGESLIDER_IMPORT_IMPORTED', 'Importati');
defined('MITS_IMAGESLIDER_IMPORT_UPDATED') or define('MITS_IMAGESLIDER_IMPORT_UPDATED', 'Aggiornati');
defined('MITS_IMAGESLIDER_IMPORT_SKIPPED') or define('MITS_IMAGESLIDER_IMPORT_SKIPPED', 'Saltati');
defined('MITS_IMAGESLIDER_IMPORT_ERRORS') or define('MITS_IMAGESLIDER_IMPORT_ERRORS', 'Errori');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE', 'La tabella banner non &egrave; stata trovata.');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS', 'Nessun banner trovato.');
defined('MITS_IMAGESLIDER_IMPORT_BACK') or define('MITS_IMAGESLIDER_IMPORT_BACK', 'Torna a ImageSlider');
