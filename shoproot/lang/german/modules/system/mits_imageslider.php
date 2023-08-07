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

$available_slider_vars = draw_tooltip('<p><strong>WICHTIG:</strong> Vor und nach dem sich wiederholendem Bereich muss als Platzhalter jeweils <strong>###SLIDERITEM###</strong> eingetragen werden (siehe Standardbeispiel).</p>
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
');

defined('MODULE_MITS_IMAGESLIDER_TITLE') or define('MODULE_MITS_IMAGESLIDER_TITLE', 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>');
defined('MODULE_MITS_IMAGESLIDER_DESCRIPTION') or define('MODULE_MITS_IMAGESLIDER_DESCRIPTION', '
<a href="https://www.merz-it-service.de/" target="_blank">
  <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
</a><br />
<p>Mit dem MITS ImageSlider-Modul k&ouml;nnen Sie eine Bilderslideshow auf der Startseite Ihres Shops erstellen. Sie k&ouml;nnen dort wechselnde Bilder mit Kategorien, Produkten, Content oder anderen Shopseiten ohne Sessionverlust verlinken oder zu einer externen Adresse verlinken.</p>
<p>Das bereits mehrere tausendfach bew&auml;hrte MITS ImageSlider-Modul &copy by Hetfield erhalten Sie im Original nur vom Hersteller unter <a target="_blank" href="https://www.merz-it-service.de"><strong><u>MerZ IT-SerVice</u></strong></a> f&uuml;r Ihre modified eCommerce Shopsoftware.</p>
<div><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Anleitung f&uuml;r das Modul MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Anleitung f&uuml;r das Modul MITS ImageSlider</u></strong></a></div>
<p>Bei Fragen, Problemen oder W&uuml;nschen zu diesem Modul oder auch zu anderen Anliegen rund um die modified eCommerce Shopsoftware nehmen Sie einfach Kontakt zu uns auf:</p> 
<div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Kontaktseite auf MerZ-IT-SerVice.de</strong></a></div>
');
defined('MODULE_MITS_IMAGESLIDER_STATUS_TITLE') or define('MODULE_MITS_IMAGESLIDER_STATUS_TITLE', 'MITS ImageSlider-Modul aktivieren?');
defined('MODULE_MITS_IMAGESLIDER_STATUS_DESC') or define('MODULE_MITS_IMAGESLIDER_STATUS_DESC', 'Das Modul MITS ImageSlider aktivieren');
defined('MODULE_MITS_IMAGESLIDER_SHOW_TITLE') or define('MODULE_MITS_IMAGESLIDER_SHOW_TITLE', 'Anzeige des MITS ImagSlider');
defined('MODULE_MITS_IMAGESLIDER_SHOW_DESC') or define('MODULE_MITS_IMAGESLIDER_SHOW_DESC', 'Wo soll der MITS ImageSlider angezeigt werden, bzw. verf&uuml;gbar sein? <br /><ul><li>start = nur auf der Startseite (default)</li><li>general = Anzeige auf allen Seiten (notwenig bei der Nutzung des MITS ImageSliders &uuml;ber Smarty-Plugin mit <br /><i>{getImageSlider slidergroup=mits_imageslider}</i> <br />in anderen Templatedateien ausser der index.html)</li></ul>');
defined('MODULE_MITS_IMAGESLIDER_TYPE_TITLE') or define('MODULE_MITS_IMAGESLIDER_TYPE_TITLE', 'Slider-Plugin ausw&auml;hlen');
defined('MODULE_MITS_IMAGESLIDER_TYPE_DESC') or define('MODULE_MITS_IMAGESLIDER_TYPE_DESC', 'Hinweis: Alle Plugins setzen eine vorhandene jQuery-Bibliothek voraus. Sollte das im Shop verwendete Template diese Voraussetzung nicht erf&uuml;llen, binden Sie diese bitte vor der Aktivierung des Moduls in Ihr Template ein. Achten Sie auch bitte darauf, dass ihr gew&auml;hltes Plugin nicht schon bereits im Template vorhanden ist (z.B. das bxSlider-Plugin im Standard-Template tpl_modified, tpl_modified_responsive usw.). Setzen Sie eines der Standard-Templates, wie z.B. tpl_modified, tpl_modified_responsive (nicht xtc5) von modified ein und m&ouml;chten Sie den bxSlider f&uuml;r den ImageSlider benutzen, dann w&auml;hlen Sie einfach das Plugin <i>bxSlider tpl_modified</i> aus.<br /><br />Hinweise zu &auml;teren Plugins: Das Plugin NivoSlider ist veraltet und wird nicht weiter gepflegt. Die Nutzung wird nicht weiter empfohlen. Das Plugin FlexSlider funktioniert nur mit jQuery 1.x. F&uuml;r jQuery 3.x wird das Plugin jQuery-Migrate ben&ouml;tigt!');
defined('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE_TITLE') or define('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE_TITLE', 'Benutzerdefinierter Code');
defined('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE_DESC') or define('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE_DESC', 'Sie k&ouml;nnen eigenen Code f&uuml;r den MITS ImageSlider verwenden. W&auml;hlen sie dazu als Plugin die Option <i>custom</i> aus. Tragen Sie dann ihren eigenen Code in dieses Textfeld und setzen sie die notwendigen Platzhalter ein. Die notwendigen Javascript- und CSS-Dateien inkl. Javascript-Funktionsaufrufe f&uuml;r den Slider m&uuml;ssen sie bei dieser Auswahl selbst implementieren. Als Beispiel ist bei der Modulinstallation der Standardcode aus dem tpl_modified_resonsive passend zum bxslider hinterlegt.' . $available_slider_vars);
defined('MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT_TITLE') or define('MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT_TITLE', 'JavaScript-Files vom Slider-Plugin laden?');
defined('MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT_DESC') or define('MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT_DESC', 'Das Modul MITS ImageSlider verwendet bei der Einstellung "true" die Javascript-Dateien vom MITS ImageSlider-Modul. Bei der Einstellung "false" werden die Javascript-Dateien nicht geladen. Die Einstellung "false" ist eigentlich nur dann notwendig, wenn das gew&uuml;nschte Slider-Plugin bereits durch andere Anpassungen im Shop verf&uuml;gbar ist (z.B. durch manuelle Installation im Template o.&auml;). Bei der Einstellung "false" wird nur die Datei <i>mits_imageslider.js</i> im jeweiligen Plugin-Ordner (<i>includes/external/mits_imageslider/plugins/PLUGINNAME/</i>) geladen. Bei der Plugin-Auswahl <i>custom</i> werden keine Dateien geladen und diese Einstellung wird ignoriert.</i>');
defined('MODULE_MITS_IMAGESLIDER_LOADCSS_TITLE') or define('MODULE_MITS_IMAGESLIDER_LOADCSS_TITLE', 'CSS-Files vom Slider-Plugin laden?');
defined('MODULE_MITS_IMAGESLIDER_LOADCSS_DESC') or define('MODULE_MITS_IMAGESLIDER_LOADCSS_DESC', 'Das Modul MITS ImageSlider verwendet bei der Einstellung "true" die CSS-Dateien vom MITS ImageSlider-Modul. Bei der Einstellung "false" werden die CSS-Dateien nicht geladen. Die Einstellung "false" ist eigentlich nur dann notwendig, wenn das gew&uuml;nschte Slider-Plugin bereits durch andere Anpassungen im Shop verf&uuml;gbar ist (z.B. durch manuelle Installation/Anpassungen im Template o.&auml;). Bei der Plugin-Auswahl <i>custom</i> werden keine Dateien geladen und diese Einstellung wird ignoriert.');
defined('MODULE_MITS_IMAGESLIDER_MOBILEWIDTH_TITLE') or define('MODULE_MITS_IMAGESLIDER_MOBILEWIDTH_TITLE', 'Maximale Bildschrimbreite f&uuml;r Mobilbild');
defined('MODULE_MITS_IMAGESLIDER_MOBILEWIDTH_DESC') or define('MODULE_MITS_IMAGESLIDER_MOBILEWIDTH_DESC', 'Geben Sie hier den Wert in Pixel ein, bis zu welcher Aufl&ouml;sung das Mobilbild dargestellt werden sollen. (Standard 600)');
defined('MODULE_MITS_IMAGESLIDER_TABLETWIDTH_TITLE') or define('MODULE_MITS_IMAGESLIDER_TABLETWIDTH_TITLE', 'Maximale Bildschrimbreite f&uuml;r Tabletbild');
defined('MODULE_MITS_IMAGESLIDER_TABLETWIDTH_DESC') or define('MODULE_MITS_IMAGESLIDER_TABLETWIDTH_DESC', 'Geben Sie hier den Wert in Pixel ein, bis zu welcher Aufl&ouml;sung das Tabletbild dargestellt werden sollen. (Standard 1023)');
defined('MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS_TITLE') or define('MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS_TITLE', 'Anzeige MITS ImageSlider-Eintr&auml;ge pro Seite in der Administration');
defined('MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS_DESC') or define('MODULE_MITS_IMAGESLIDER_MAX_DISPLAY_RESULTS_DESC', 'Wieviel MITS ImageSlider-Eintr&auml;ge sollen pro Seite im Administrationsbereich des Shops angezeigt werden? Die Einstellung kann auch direkt in der &Uuml;bersicht ge&auml;ndert werden.');
defined('MODULE_MITS_IMAGESLIDER_UPDATE_TITLE') or define('MODULE_MITS_IMAGESLIDER_UPDATE_TITLE', 'Modulaktualisierung');
defined('MODULE_MITS_IMAGESLIDER_DO_UPDATE') or define('MODULE_MITS_IMAGESLIDER_DO_UPDATE', 'Aktualisierung f&uuml;r den MITS ImageSlider durchf&uuml;hren?');
defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD_TITLE') or define('MODULE_MITS_IMAGESLIDER_LAZYLOAD_TITLE', 'Lazy Load');
defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD_DESC') or define('MODULE_MITS_IMAGESLIDER_LAZYLOAD_DESC', 'Soll der Support f&uuml;r lazyload aktiviert werden? (Nicht f&uuml;r alle Plugins m&ouml;glich!)');
defined('MODULE_MITS_IMAGESLIDER_UPDATE_AVAILABLE_TITLE') or define('MODULE_MITS_IMAGESLIDER_UPDATE_AVAILABLE_TITLE', ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Bitte Modulaktualisierung durchf&uuml;hren!</span>');
defined('MODULE_MITS_IMAGESLIDER_UPDATE_AVAILABLE_DESC') or define('MODULE_MITS_IMAGESLIDER_UPDATE_AVAILABLE_DESC', '');
