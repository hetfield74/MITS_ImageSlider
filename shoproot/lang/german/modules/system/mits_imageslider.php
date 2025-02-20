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
  '<p><strong>WICHTIG:</strong> Vor und nach dem sich wiederholendem Bereich muss als Platzhalter jeweils <strong>###SLIDERITEM###</strong> eingetragen werden (siehe Standardbeispiel).</p>
<p>Folgende Platzhalter sind verf&uuml;gbar:</p>
<ul>
  <li><strong>{ID}</strong> (ID des Slidereintrags)</li>
  <li><strong>{IMAGE}</strong> (Bildadresse des Slidereintrags)</li>
  <li><strong>{MAINIMAGE}</strong> (Bildadresse vom Hauptbild des Slidereintrags)</li>
  <li><strong>{TABLETIMAGE}</strong> (Bildadresse zur Tablet-Ansicht des Slidereintrags)</li>
  <li><strong>{MOBILEIMAGE}</strong> (Bildadresse zur mobilen Ansicht des Slidereintrags)</li>
  <li><strong>{LINK}</strong> (URL des Slidereintrags)</li>
  <li><strong>{LINKTARGET}</strong> (Zielfenster der URL)</li>
  <li><strong>{IMAGEALT}</strong> (Alt-Text f&uuml;r Bild)</li>
  <li><strong>{TITLE}</strong> (Titel f&uuml;r Bild)</li>
  <li><strong>{TEXT}</strong> (Text aus dem Editor)</li>
</ul>
'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE'                     => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION'               => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>Mit dem MITS ImageSlider-Modul k&ouml;nnen Sie eine Bilderslideshow auf der Startseite Ihres Shops erstellen. Sie k&ouml;nnen dort wechselnde Bilder mit Kategorien, Produkten, Content oder anderen Shopseiten ohne Sessionverlust verlinken oder zu einer externen Adresse verlinken.</p>
    <p>Das bereits mehrere tausendfach bew&auml;hrte MITS ImageSlider-Modul &copy by Hetfield erhalten Sie im Original nur vom Hersteller unter <a target="_blank" href="https://www.merz-it-service.de"><strong><u>MerZ IT-SerVice</u></strong></a> f&uuml;r Ihre modified eCommerce Shopsoftware.</p>
    <div><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Anleitung f&uuml;r das Modul MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Anleitung f&uuml;r das Modul MITS ImageSlider</u></strong></a></div>
    <div style="text-align:center;">
      <small>Nur auf Github gibt es immer die aktuellste Version des Moduls!</small><br />
      <a style="background:#6a9;color:#444" target="_blank" href="https://github.com/hetfield74/MITS_ImageSlider" class="button" onclick="this.blur();">MITS_ImageSlider on Github</a>
    </div>
    <p>Bei Fragen, Problemen oder W&uuml;nschen zu diesem Modul oder auch zu anderen Anliegen rund um die modified eCommerce Shopsoftware nehmen Sie einfach Kontakt zu uns auf:</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Kontaktseite auf MerZ-IT-SerVice.de</strong></a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE'              => 'MITS ImageSlider-Modul aktivieren?',
  'MODULE_' . $modulname . '_STATUS_DESC'               => 'Das Modul MITS ImageSlider aktivieren',
  'MODULE_' . $modulname . '_SHOW_TITLE'                => 'Anzeige des MITS ImagSlider',
  'MODULE_' . $modulname . '_SHOW_DESC'                 => 'Wo soll der MITS ImageSlider angezeigt werden, bzw. verf&uuml;gbar sein? <br /><ul><li>start = nur auf der Startseite (default)</li><li>general = Anzeige auf allen Seiten (notwenig bei der Nutzung des MITS ImageSliders &uuml;ber Smarty-Plugin mit <br /><i>{getImageSlider slidergroup=mits_imageslider}</i> <br />in anderen Templatedateien ausser der index.html)</li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE'                => 'Slider-Plugin ausw&auml;hlen',
  'MODULE_' . $modulname . '_TYPE_DESC'                 => 'Hinweis: Alle Plugins setzen eine vorhandene jQuery-Bibliothek voraus. Sollte das im Shop verwendete Template diese Voraussetzung nicht erf&uuml;llen, binden Sie diese bitte vor der Aktivierung des Moduls in Ihr Template ein. Achten Sie auch bitte darauf, dass ihr gew&auml;hltes Plugin nicht schon bereits im Template vorhanden ist (z.B. das bxSlider-Plugin im Standard-Template tpl_modified, tpl_modified_responsive usw.). Setzen Sie eines der Standard-Templates, wie z.B. tpl_modified, tpl_modified_responsive (nicht xtc5) von modified ein und m&ouml;chten Sie den bxSlider f&uuml;r den ImageSlider benutzen, dann w&auml;hlen Sie einfach das Plugin <i>bxSlider tpl_modified</i> aus.<br /><br />Hinweise zu &auml;teren Plugins: Das Plugin NivoSlider ist veraltet und wird nicht weiter gepflegt. Die Nutzung wird nicht weiter empfohlen. Das Plugin FlexSlider funktioniert nur mit jQuery 1.x. F&uuml;r jQuery 3.x wird das Plugin jQuery-Migrate ben&ouml;tigt!',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE'         => 'Benutzerdefinierter Code',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC'          => 'Sie k&ouml;nnen eigenen Code f&uuml;r den MITS ImageSlider verwenden. W&auml;hlen sie dazu als Plugin die Option <i>custom</i> aus. Tragen Sie dann ihren eigenen Code in dieses Textfeld und setzen sie die notwendigen Platzhalter ein. Die notwendigen Javascript- und CSS-Dateien inkl. Javascript-Funktionsaufrufe f&uuml;r den Slider m&uuml;ssen sie bei dieser Auswahl selbst implementieren. Als Beispiel ist bei der Modulinstallation der Standardcode aus dem tpl_modified_resonsive passend zum bxslider hinterlegt.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE'      => 'JavaScript-Files vom Slider-Plugin laden?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC'       => 'Das Modul MITS ImageSlider verwendet bei der Einstellung "true" die Javascript-Dateien vom MITS ImageSlider-Modul. Bei der Einstellung "false" werden die Javascript-Dateien nicht geladen. Die Einstellung "false" ist eigentlich nur dann notwendig, wenn das gew&uuml;nschte Slider-Plugin bereits durch andere Anpassungen im Shop verf&uuml;gbar ist (z.B. durch manuelle Installation im Template o.&auml;). Bei der Einstellung "false" wird nur die Datei <i>mits_imageslider.js</i> im jeweiligen Plugin-Ordner (<i>includes/external/mits_imageslider/plugins/PLUGINNAME/</i>) geladen. Bei der Plugin-Auswahl <i>custom</i> werden keine Dateien geladen und diese Einstellung wird ignoriert.</i>',
  'MODULE_' . $modulname . '_LOADCSS_TITLE'             => 'CSS-Files vom Slider-Plugin laden?',
  'MODULE_' . $modulname . '_LOADCSS_DESC'              => 'Das Modul MITS ImageSlider verwendet bei der Einstellung "true" die CSS-Dateien vom MITS ImageSlider-Modul. Bei der Einstellung "false" werden die CSS-Dateien nicht geladen. Die Einstellung "false" ist eigentlich nur dann notwendig, wenn das gew&uuml;nschte Slider-Plugin bereits durch andere Anpassungen im Shop verf&uuml;gbar ist (z.B. durch manuelle Installation/Anpassungen im Template o.&auml;). Bei der Plugin-Auswahl <i>custom</i> werden keine Dateien geladen und diese Einstellung wird ignoriert.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE'         => 'Maximale Bildschirmbreite f&uuml;r Mobilbild',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC'          => 'Geben Sie hier den Wert in Pixel ein, bis zu welcher Aufl&ouml;sung das Mobilbild dargestellt werden sollen. (Standard 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE'         => 'Maximale Bildschirmbreite f&uuml;r Tabletbild',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC'          => 'Geben Sie hier den Wert in Pixel ein, bis zu welcher Aufl&ouml;sung das Tabletbild dargestellt werden sollen. (Standard 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'Anzeige MITS ImageSlider-Eintr&auml;ge pro Seite in der Administration',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC'  => 'Wieviel MITS ImageSlider-Eintr&auml;ge sollen pro Seite im Administrationsbereich des Shops angezeigt werden? Die Einstellung kann auch direkt in der &Uuml;bersicht ge&auml;ndert werden.',
  'MODULE_' . $modulname . '_UPDATE_TITLE'              => 'Modulaktualisierung',
  'MODULE_' . $modulname . '_DO_UPDATE'                 => 'Aktualisierung f&uuml;r den MITS ImageSlider durchf&uuml;hren?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE'            => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC'             => 'Soll der Support f&uuml;r lazyload aktiviert werden? (Nicht f&uuml;r alle Plugins m&ouml;glich!)',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE'    => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Bitte Modulaktualisierung durchf&uuml;hren!</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC'     => ''
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
