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
  '<p><strong>IMPORTANT:</strong> Avant et apr&egrave;s la zone r&eacute;p&eacute;t&eacute;e, le placeholder <strong>###SLIDERITEM###</strong> doit &ecirc;tre saisi (voir exemple standard).</p><p>Placeholders disponibles:</p><ul><li><strong>{ID}</strong></li><li><strong>{IMAGE}</strong></li><li><strong>{MAINIMAGE}</strong></li><li><strong>{TABLETIMAGE}</strong></li><li><strong>{MOBILEIMAGE}</strong></li><li><strong>{LINK}</strong></li><li><strong>{LINKTARGET}</strong></li><li><strong>{IMAGEALT}</strong></li><li><strong>{TITLE}</strong></li><li><strong>{TEXT}</strong></li></ul>'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE' => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION' => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>Le module MITS ImageSlider permet de cr&eacute;er un diaporama d&rsquo;images pour la page d&rsquo;accueil de votre boutique. Les images peuvent &ecirc;tre li&eacute;es &agrave; des cat&eacute;gories, produits, contenus, autres pages de la boutique ou adresses externes.</p>
    <div style="text-align:center;margin:20px 0;"><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>MITS ImageSlider</u></strong></a></div>
    <p>MerZ IT-SerVice</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Page de contact MerZ-IT-SerVice.de</a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE' => 'Activer le module MITS ImageSlider?',
  'MODULE_' . $modulname . '_STATUS_DESC' => 'Activer le module MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_TITLE' => 'Affichage de MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_DESC' => 'O&ugrave; MITS ImageSlider doit-il &ecirc;tre affich&eacute; ou disponible? <br /><ul><li>start = uniquement sur la page d&rsquo;accueil</li><li>general = disponible sur toutes les pages, n&eacute;cessaire pour le plugin Smarty <br /><i>{getImageSlider slidergroup=mits_imageslider}</i></li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE' => 'Choisir le plugin slider',
  'MODULE_' . $modulname . '_TYPE_DESC' => 'Remarque: tous les plugins supposent une biblioth&egrave;que jQuery existante. Si le template ne la fournit pas, veuillez l&rsquo;int&eacute;grer avant activation.',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE' => 'Code personnalis&eacute;',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC' => 'Vous pouvez utiliser votre propre code pour MITS ImageSlider. S&eacute;lectionnez le plugin <i>custom</i>, saisissez votre code et utilisez les placeholders n&eacute;cessaires.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE' => 'Charger les fichiers JavaScript du plugin slider?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC' => 'Si cette option vaut true, le module charge les fichiers JavaScript du module MITS ImageSlider. false est utile si le plugin est d&eacute;j&agrave; charg&eacute; autrement.',
  'MODULE_' . $modulname . '_LOADCSS_TITLE' => 'Charger les fichiers CSS du plugin slider?',
  'MODULE_' . $modulname . '_LOADCSS_DESC' => 'Si cette option vaut true, le module charge les fichiers CSS du module MITS ImageSlider. false est utile si le plugin est d&eacute;j&agrave; int&eacute;gr&eacute; dans le template.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE' => 'Largeur maximale pour image mobile',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC' => 'Saisir la largeur maximale en pixels pour afficher l&rsquo;image mobile. (Standard 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE' => 'Largeur maximale pour image tablette',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC' => 'Saisir la largeur maximale en pixels pour afficher l&rsquo;image tablette. (Standard 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'Entr&eacute;es MITS ImageSlider par page dans l&rsquo;administration',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC' => 'Combien d&rsquo;entr&eacute;es MITS ImageSlider afficher par page dans l&rsquo;administration?',
  'MODULE_' . $modulname . '_UPDATE_TITLE' => 'Mise &agrave; jour du module',
  'MODULE_' . $modulname . '_DO_UPDATE' => 'Effectuer la mise &agrave; jour de MITS ImageSlider?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE' => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC' => 'Activer le support lazyload?',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE' => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Veuillez effectuer la mise &agrave; jour du module!</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC' => '',
  'MODULE_' . $modulname . '_UPDATE_FINISHED' => 'Le module MITS ImageSlider a &eacute;t&eacute; mis &agrave; jour.',
  'MODULE_' . $modulname . '_UPDATE_ERROR' => 'Erreur',
  'MODULE_' . $modulname . '_UPDATE_MODUL' => 'Mettre &agrave; jour le module',
  'MODULE_' . $modulname . '_DELETE_MODUL' => 'Supprimer compl&egrave;tement MITS ImageSlider du serveur',
  'MODULE_' . $modulname . '_CONFIRM_DELETE_MODUL' => 'Voulez-vous vraiment supprimer le module MITS ImageSlider avec tous ses fichiers du serveur?',
  'MODULE_' . $modulname . '_DELETE_FINISHED' => 'Le module MITS ImageSlider a &eacute;t&eacute; supprim&eacute; du serveur.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS' => 'G&eacute;n&eacute;rer les r&eacute;solutions et fallbacks manquants.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS_DESC' => 'R&eacute;g&eacute;n&egrave;re automatiquement les variantes WebP, JPG/PNG fallback et srcset pour toutes les images ImageSlider existantes.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS' => 'Importer le Banner Manager.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS_DESC' => 'Importe les groupes et entr&eacute;es de banni&egrave;res du Banner Manager dans MITS ImageSlider.',
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
