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

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true' && defined('MODULE_MITS_IMAGESLIDER_VERSION')) {
    $lang_array = array(
      'MITS_BOX_IMAGESLIDER' => 'MITS ImageSlider - v' . MODULE_MITS_IMAGESLIDER_VERSION,
      'MITS_BOX_IMAGESLIDER_IMPORT_BANNERS' => 'MITS ImageSlider - Import banni&egrave;res',
      'TEXT_IMAGESLIDERS_GROUP' => 'MITS ImageSlider-Groupe:
        <span class="tooltip"><img src="images/icons/tooltip_icon.png"  style="border:0;">
          <em>Vous pouvez attribuer ici un groupe MITS ImageSlider existant qui sera affich&eacute; dans le frontend. Les fichiers de template correspondants doivent &ecirc;tre adapt&eacute;s.</em>
        </span>',
      'MITS_IMAGESLIDER_VARIANTS_HEADING' => 'MITS ImageSlider &ndash; R&eacute;g&eacute;n&eacute;rer les variantes d&rsquo;image',
      'MITS_IMAGESLIDER_VARIANTS_INTRO' => 'Cet outil admin g&eacute;n&egrave;re des variantes suppl&eacute;mentaires pour les images ImageSlider existantes afin d&rsquo;am&eacute;liorer les performances (PageSpeed), notamment:',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_WEBP' => 'Versions WebP (si le serveur prend en charge WebP via GD)',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_FALLBACK' => 'Fichiers fallback (JPG/PNG) sous une forme coh&eacute;rente',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_SRCSET' => 'Plusieurs r&eacute;solutions (d&eacute;riv&eacute;s srcset) dans un sous-dossier srcset/',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_DB' => 'Optionnel: mettre &agrave; jour les chemins et les dimensions en base de donn&eacute;es',
      'MITS_IMAGESLIDER_VARIANTS_PURPOSE' => 'Cet outil optimise apr&egrave;s coup les images de slider existantes sans devoir les t&eacute;l&eacute;verser &agrave; nouveau.',
      'MITS_IMAGESLIDER_VARIANTS_DOC_LINK_TEXT' => 'Manuel et informations compl&eacute;mentaires',
      'MITS_IMAGESLIDER_VARIANTS_DOC_WINDOW_TITLE' => 'Manuel et informations compl&eacute;mentaires',
      'MITS_IMAGESLIDER_VARIANTS_TOTAL' => 'Total',
      'MITS_IMAGESLIDER_VARIANTS_RECORDS' => 'enregistrements',
      'MITS_IMAGESLIDER_VARIANTS_BATCH' => 'Lot',
      'MITS_IMAGESLIDER_VARIANTS_MODE' => 'Mode',
      'MITS_IMAGESLIDER_VARIANTS_BTN_SCAN' => 'Analyser (contr&ocirc;le uniquement)',
      'MITS_IMAGESLIDER_VARIANTS_BTN_EXECUTE' => 'Ex&eacute;cuter (g&eacute;n&eacute;rer variantes)',
      'MITS_IMAGESLIDER_VARIANTS_NOTE_LABEL' => 'Remarque',
      'MITS_IMAGESLIDER_VARIANTS_NOTE' => 'Le traitement continue automatiquement par petits lots jusqu&rsquo;&agrave; ce que toutes les images soient termin&eacute;es. Les fichiers sont g&eacute;n&eacute;r&eacute;s/&eacute;cras&eacute;s et les chemins ainsi que les dimensions sont mis &agrave; jour si n&eacute;cessaire.',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_MODE' => 'Mode ex&eacute;cution',
      'MITS_IMAGESLIDER_VARIANTS_PLEASE_CONFIRM' => 'Veuillez confirmer pour autoriser les mises &agrave; jour DB.',
      'MITS_IMAGESLIDER_VARIANTS_BTN_CONFIRM_DB' => 'J&rsquo;ai une sauvegarde et je veux ex&eacute;cuter les mises &agrave; jour DB',
      'MITS_IMAGESLIDER_VARIANTS_NO_CONFIRM_NOTE' => 'Sans confirmation, les variantes sont g&eacute;n&eacute;r&eacute;es, mais les chemins/dimensions DB ne sont pas mis &agrave; jour.',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED' => 'Ex&eacute;cution confirm&eacute;e',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED_TEXT' => 'Les chemins DB et les dimensions seront mis &agrave; jour si quelque chose change.',
      'MITS_IMAGESLIDER_VARIANTS_BATCH_RESULT' => 'R&eacute;sultat du lot',
      'MITS_IMAGESLIDER_VARIANTS_PROCESSED' => 'trait&eacute;s',
      'MITS_IMAGESLIDER_VARIANTS_DB_UPDATES' => 'mises &agrave; jour DB',
      'MITS_IMAGESLIDER_VARIANTS_SKIPPED' => 'ignor&eacute;s',
      'MITS_IMAGESLIDER_VARIANTS_NO_ACTIONS' => 'Aucune action.',
      'MITS_IMAGESLIDER_VARIANTS_NEXT_BATCH' => 'Lot suivant',
      'MITS_IMAGESLIDER_VARIANTS_DONE' => 'Termin&eacute;.',
      'MITS_IMAGESLIDER_VARIANTS_YES' => '<span class="ok">oui</span>',
      'MITS_IMAGESLIDER_VARIANTS_NO' => '<span class="warn">non</span>',
      'MITS_IMAGESLIDER_VARIANTS_STATUS_CREATED' => 'cr&eacute;&eacute;',
      'MITS_IMAGESLIDER_VARIANTS_STATUS_UPDATED' => 'existe/mis &agrave; jour',
      'MITS_IMAGESLIDER_VARIANTS_STATUS_MISSING' => 'manquant',
      'MITS_IMAGESLIDER_VARIANTS_STATUS_NOT_SUPPORTED' => 'non pris en charge par le serveur',
      'MITS_IMAGESLIDER_VARIANTS_STATUS_NOT_NEEDED' => 'non n&eacute;cessaire / image plus petite que les largeurs cible',
      'MITS_IMAGESLIDER_VARIANTS_DETAIL_FALLBACK' => 'Fallback',
      'MITS_IMAGESLIDER_VARIANTS_AUTO_INTRO' => 'Le traitement continue automatiquement par petits lots jusqu&rsquo;&agrave; ce que toutes les images soient termin&eacute;es. Veuillez laisser la fen&ecirc;tre du navigateur ouverte.',
      'MITS_IMAGESLIDER_VARIANTS_BTN_START_AUTO' => 'R&eacute;g&eacute;n&eacute;rer automatiquement toutes les images',
      'MITS_IMAGESLIDER_VARIANTS_BTN_STOP' => 'Annuler',
      'MITS_IMAGESLIDER_VARIANTS_BTN_RESET' => 'D&eacute;marrer un nouveau traitement',
      'MITS_IMAGESLIDER_VARIANTS_RUNNING' => 'En cours',
      'MITS_IMAGESLIDER_VARIANTS_AUTO_REFRESH' => 'Le lot suivant d&eacute;marre automatiquement.',
      'MITS_IMAGESLIDER_VARIANTS_STARTED' => 'D&eacute;marr&eacute;',
      'MITS_IMAGESLIDER_VARIANTS_FINISHED' => 'Termin&eacute;',
      'MITS_IMAGESLIDER_VARIANTS_BATCH_SIZE' => 'Enregistrements par lot',
      'MITS_IMAGESLIDER_VARIANTS_NOSCRIPT' => 'JavaScript/meta refresh est d&eacute;sactiv&eacute;. Veuillez d&eacute;marrer le lot suivant manuellement.',
      'MITS_IMAGESLIDER_VARIANTS_ERR_MODULE_DISABLED' => 'Le module n&rsquo;est pas activ&eacute;.',
      'MITS_IMAGESLIDER_VARIANTS_ERR_HELPER_MISSING' => 'Assistant de variantes introuvable. Attendu:',
      'MITS_IMAGESLIDER_VARIANTS_LOG_MISSING' => '%s: %s manquant',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SKIP_EXT' => '%s: ignor&eacute; (extension) %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SCAN' => '%s: %s | webp=%s | srcset_dir=%s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_GENERATED' => '%s: variantes g&eacute;n&eacute;r&eacute;es pour %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_PATH_UPDATED' => '%s: chemin mis &agrave; jour %s -> %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_DIMS' => '%s: dimensions %dx%d -> %dx%d',
    );

    foreach ($lang_array as $key => $val) {
        defined($key) || define($key, $val);
    }
}
