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
      'MITS_BOX_IMAGESLIDER'    => 'MITS ImageSlider - v' . MODULE_MITS_IMAGESLIDER_VERSION,
      'TEXT_IMAGESLIDERS_GROUP' => 'MITS ImageSlider-Gruppe:
        <span class="tooltip"><img src="images/icons/tooltip_icon.png"  style="border:0;">
          <em>Hier k&ouml;nnen Sie eine bestehende MITS ImageSlider-Gruppe zuordnen, die im Frontend angezeigt werden soll. Voraussetzung ist nat&uuml;rlich, dass die entsprechenden Template-Dateien erg&auml;nzt wurden.</em>
        </span>',

      'MITS_IMAGESLIDER_VARIANTS_HEADING'          => 'MITS ImageSlider – Bilder-Varianten regenerieren',
      'MITS_IMAGESLIDER_VARIANTS_INTRO'            => 'Dieses Admin-Tool erzeugt f&uuml;r bereits vorhandene ImageSlider-Bilder automatisch zus&auml;tzliche Bildvarianten f&uuml;r bessere Performance (Pagespeed), insbesondere:',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_WEBP'     => 'WebP-Versionen (wenn der Server WebP &uuml;ber GD unterst&uuml;tzt)',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_FALLBACK' => 'Fallback-Dateien (JPG/PNG) in konsistenter Form',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_SRCSET'   => 'mehrere Aufl&ouml;sungen (srcset-Derivate) in einem Unterordner srcset/',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_DB'       => 'optional: Aktualisierung der Datenbank-Pfadfelder sowie Width/Height (f&uuml;r saubere Bildabmessungen / weniger CLS)',
      'MITS_IMAGESLIDER_VARIANTS_PURPOSE'          => 'Das Tool ist gedacht, um bestehende Sliderbilder nachtr&auml;glich zu optimieren, ohne sie erneut hochladen zu m&uuml;ssen.',
      'MITS_IMAGESLIDER_VARIANTS_DOC_LINK_TEXT'    => 'Anleitung und weitere Informationen f&uuml;r das Tool',
      'MITS_IMAGESLIDER_VARIANTS_DOC_WINDOW_TITLE' => 'Anleitung und weitere Informationen f&uuml;r das Tool',

      'MITS_IMAGESLIDER_VARIANTS_TOTAL'   => 'Gesamt',
      'MITS_IMAGESLIDER_VARIANTS_RECORDS' => 'Datens&auml;tze',
      'MITS_IMAGESLIDER_VARIANTS_BATCH'   => 'Batch',
      'MITS_IMAGESLIDER_VARIANTS_MODE'    => 'Modus',

      'MITS_IMAGESLIDER_VARIANTS_BTN_SCAN'    => 'Scan (nur pr&uuml;fen)',
      'MITS_IMAGESLIDER_VARIANTS_BTN_EXECUTE' => 'Execute (Varianten erzeugen)',
      'MITS_IMAGESLIDER_VARIANTS_NOTE_LABEL'  => 'Hinweis',
      'MITS_IMAGESLIDER_VARIANTS_NOTE'        => 'Im Modus <code>execute</code> werden Dateien erzeugt/&uuml;berschrieben. DB-Updates passieren erst nach Best&auml;tigung.',

      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_MODE'           => 'Execute-Modus',
      'MITS_IMAGESLIDER_VARIANTS_PLEASE_CONFIRM'         => 'Bitte best&auml;tigen, damit DB-Updates durchgef&uuml;hrt werden.',
      'MITS_IMAGESLIDER_VARIANTS_BTN_CONFIRM_DB'         => 'Ich habe ein Backup und m&ouml;chte DB-Updates ausf&uuml;hren',
      'MITS_IMAGESLIDER_VARIANTS_NO_CONFIRM_NOTE'        => 'Ohne Best&auml;tigung werden nur Varianten erzeugt (wenn die Helper-Funktion Dateien schreibt), aber keine DB-Pfade/Dims aktualisiert.',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED'      => 'Execute best&auml;tigt',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED_TEXT' => 'DB-Pfade und Width/Height werden aktualisiert, falls sich etwas &auml;ndert.',

      'MITS_IMAGESLIDER_VARIANTS_BATCH_RESULT' => 'Ergebnis Batch',
      'MITS_IMAGESLIDER_VARIANTS_PROCESSED'    => 'verarbeitet',
      'MITS_IMAGESLIDER_VARIANTS_DB_UPDATES'   => 'DB-Updates',
      'MITS_IMAGESLIDER_VARIANTS_SKIPPED'      => '&uuml;bersprungen',
      'MITS_IMAGESLIDER_VARIANTS_NO_ACTIONS'   => 'Keine Aktionen.',
      'MITS_IMAGESLIDER_VARIANTS_NEXT_BATCH'   => 'N&auml;chsten Batch',
      'MITS_IMAGESLIDER_VARIANTS_DONE'         => 'Fertig.',

      'MITS_IMAGESLIDER_VARIANTS_YES' => '<span class="ok">ja</span>',
      'MITS_IMAGESLIDER_VARIANTS_NO'  => '<span class="warn">nein</span>',

      'MITS_IMAGESLIDER_VARIANTS_ERR_MODULE_DISABLED' => 'Modul ist nicht aktiviert.',
      'MITS_IMAGESLIDER_VARIANTS_ERR_HELPER_MISSING'  => 'Variant-Helper nicht gefunden. Erwartet:',

      'MITS_IMAGESLIDER_VARIANTS_LOG_MISSING'      => '%s: Datei fehlt %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SKIP_EXT'     => '%s: &uuml;bersprungen (Endung) %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SCAN'         => '%s: %s | webp=%s | srcset_dir=%s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_GENERATED'    => '%s: Varianten erzeugt f&uuml;r %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_PATH_UPDATED' => '%s: Pfad ge&auml;ndert %s -&gt; %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_DIMS'         => '%s: Abmessungen %dx%d -&gt; %dx%d'
    );

    foreach ($lang_array as $key => $val) {
        defined($key) || define($key, $val);
    }
}