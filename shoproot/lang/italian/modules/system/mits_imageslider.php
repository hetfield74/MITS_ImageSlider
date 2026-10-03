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

$modulname = strtoupper("mits_imageslider");

$available_slider_vars = draw_tooltip(
  '<p><strong>IMPORTANTE:</strong> Prima e dopo l&rsquo;area ripetuta deve essere inserito il placeholder <strong>###SLIDERITEM###</strong> (vedi esempio standard).</p><p>Placeholder disponibili:</p><ul><li><strong>{ID}</strong></li><li><strong>{IMAGE}</strong></li><li><strong>{MAINIMAGE}</strong></li><li><strong>{TABLETIMAGE}</strong></li><li><strong>{MOBILEIMAGE}</strong></li><li><strong>{LINK}</strong></li><li><strong>{LINKTARGET}</strong></li><li><strong>{IMAGEALT}</strong></li><li><strong>{TITLE}</strong></li><li><strong>{TEXT}</strong></li></ul>'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE' => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION' => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>Con il modulo MITS ImageSlider puoi creare una slideshow di immagini nella homepage del tuo shop. Le immagini possono essere collegate a categorie, prodotti, contenuti, altre pagine dello shop o indirizzi esterni.</p>
    <div style="text-align:center;margin:20px 0;"><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>MITS ImageSlider</u></strong></a></div>
    <p>MerZ IT-SerVice</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Pagina contatti MerZ-IT-SerVice.de</a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE' => 'Attivare il modulo MITS ImageSlider?',
  'MODULE_' . $modulname . '_STATUS_DESC' => 'Attiva il modulo MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_TITLE' => 'Visualizzazione di MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_DESC' => 'Dove deve essere visualizzato o disponibile MITS ImageSlider? <br /><ul><li>start = solo nella homepage</li><li>general = disponibile su tutte le pagine, necessario per il plugin Smarty <br /><i>{getImageSlider slidergroup=mits_imageslider}</i></li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE' => 'Seleziona plugin slider',
  'MODULE_' . $modulname . '_TYPE_DESC' => 'Nota: tutti i plugin richiedono una libreria jQuery esistente. Se il template non la fornisce, integrarla prima dell&rsquo;attivazione.',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE' => 'Codice personalizzato',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC' => 'Puoi usare codice personalizzato per MITS ImageSlider. Seleziona il plugin <i>custom</i>, inserisci il codice e utilizza i placeholder necessari.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE' => 'Caricare i file JavaScript del plugin slider?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC' => 'Con true il modulo carica i file JavaScript del modulo MITS ImageSlider. false &egrave; utile se il plugin &egrave; gi&agrave; caricato altrove.',
  'MODULE_' . $modulname . '_LOADCSS_TITLE' => 'Caricare i file CSS del plugin slider?',
  'MODULE_' . $modulname . '_LOADCSS_DESC' => 'Con true il modulo carica i file CSS del modulo MITS ImageSlider. false &egrave; utile se il plugin &egrave; gi&agrave; integrato nel template.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE' => 'Larghezza massima per immagine mobile',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC' => 'Inserire la larghezza massima in pixel per visualizzare l&rsquo;immagine mobile. (Default 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE' => 'Larghezza massima per immagine tablet',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC' => 'Inserire la larghezza massima in pixel per visualizzare l&rsquo;immagine tablet. (Default 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'Voci MITS ImageSlider per pagina in amministrazione',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC' => 'Quante voci MITS ImageSlider visualizzare per pagina nell&rsquo;area admin?',
  'MODULE_' . $modulname . '_UPDATE_TITLE' => 'Aggiornamento modulo',
  'MODULE_' . $modulname . '_DO_UPDATE' => 'Eseguire l&rsquo;aggiornamento per MITS ImageSlider?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE' => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC' => 'Attivare il supporto lazyload?',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE' => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Eseguire l&rsquo;aggiornamento del modulo!</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC' => '',
  'MODULE_' . $modulname . '_UPDATE_FINISHED' => 'Il modulo MITS ImageSlider &egrave; stato aggiornato.',
  'MODULE_' . $modulname . '_UPDATE_ERROR' => 'Errore',
  'MODULE_' . $modulname . '_UPDATE_MODUL' => 'Aggiorna modulo',
  'MODULE_' . $modulname . '_DELETE_MODUL' => 'Rimuovi completamente MITS ImageSlider dal server',
  'MODULE_' . $modulname . '_CONFIRM_DELETE_MODUL' => 'Vuoi davvero eliminare il modulo MITS ImageSlider con tutti i file dal server?',
  'MODULE_' . $modulname . '_DELETE_FINISHED' => 'Il modulo MITS ImageSlider &egrave; stato eliminato dal server.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS' => 'Genera risoluzioni e fallback mancanti.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS_DESC' => 'Rigenera automaticamente varianti WebP, fallback JPG/PNG e srcset per tutte le immagini ImageSlider esistenti.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS' => 'Importa Banner Manager.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS_DESC' => 'Importa gruppi e voci banner dal Banner Manager in MITS ImageSlider.',
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
