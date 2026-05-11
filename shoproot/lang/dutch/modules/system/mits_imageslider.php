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
  '<p><strong>BELANGRIJK:</strong> Voor en na het herhalende gedeelte moet de placeholder <strong>###SLIDERITEM###</strong> worden ingevoerd (zie standaardvoorbeeld).</p><p>Beschikbare placeholders:</p><ul><li><strong>{ID}</strong></li><li><strong>{IMAGE}</strong></li><li><strong>{MAINIMAGE}</strong></li><li><strong>{TABLETIMAGE}</strong></li><li><strong>{MOBILEIMAGE}</strong></li><li><strong>{LINK}</strong></li><li><strong>{LINKTARGET}</strong></li><li><strong>{IMAGEALT}</strong></li><li><strong>{TITLE}</strong></li><li><strong>{TEXT}</strong></li></ul>'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE' => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION' => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>Met de MITS ImageSlider-module kunt u een afbeeldingenslideshow op de startpagina van uw shop maken. Afbeeldingen kunnen worden gekoppeld aan categorie&euml;n, producten, content, andere shoppagina&rsquo;s of externe adressen.</p>
    <div style="text-align:center;margin:20px 0;"><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>MITS ImageSlider</u></strong></a></div>
    <div style="text-align:center;">
      <small>Alleen op Github is altijd de nieuwste versie van de module beschikbaar!</small><br />
      <a style="background:#6a9;color:#444" target="_blank" href="https://github.com/hetfield74/MITS_ImageSlider" class="button" onclick="this.blur();">MITS_ImageSlider on Github</a>
    </div>
    <p>MerZ IT-SerVice</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Contactpagina op MerZ-IT-SerVice.de</a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE' => 'MITS ImageSlider-module activeren?',
  'MODULE_' . $modulname . '_STATUS_DESC' => 'Activeer de MITS ImageSlider-module',
  'MODULE_' . $modulname . '_SHOW_TITLE' => 'Weergave van MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_DESC' => 'Waar moet de MITS ImageSlider worden weergegeven of beschikbaar zijn? <br /><ul><li>start = alleen op de startpagina</li><li>general = beschikbaar op alle pagina&rsquo;s, nodig voor de Smarty-plugin <br /><i>{getImageSlider slidergroup=mits_imageslider}</i></li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE' => 'Slider-plugin selecteren',
  'MODULE_' . $modulname . '_TYPE_DESC' => 'Opmerking: alle plugins vereisen een bestaande jQuery-bibliotheek. Als de template deze niet levert, voeg deze dan toe voordat u de module activeert.',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE' => 'Aangepaste code',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC' => 'U kunt eigen code voor MITS ImageSlider gebruiken. Selecteer plugin <i>custom</i>, voer de code in en gebruik de benodigde placeholders.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE' => 'JavaScript-bestanden van slider-plugin laden?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC' => 'Bij true laadt de module de JavaScript-bestanden van MITS ImageSlider. false is nuttig als de plugin al op een andere manier beschikbaar is.',
  'MODULE_' . $modulname . '_LOADCSS_TITLE' => 'CSS-bestanden van slider-plugin laden?',
  'MODULE_' . $modulname . '_LOADCSS_DESC' => 'Bij true laadt de module de CSS-bestanden van MITS ImageSlider. false is nuttig als de plugin al in de template is ge&iuml;ntegreerd.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE' => 'Maximale schermbreedte voor mobiele afbeelding',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC' => 'Voer de maximale breedte in pixels in waarop de mobiele afbeelding wordt weergegeven. (Standaard 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE' => 'Maximale schermbreedte voor tabletafbeelding',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC' => 'Voer de maximale breedte in pixels in waarop de tabletafbeelding wordt weergegeven. (Standaard 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'MITS ImageSlider-items per pagina in administratie',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC' => 'Hoeveel MITS ImageSlider-items moeten per pagina in de administratie worden getoond?',
  'MODULE_' . $modulname . '_UPDATE_TITLE' => 'Module-update',
  'MODULE_' . $modulname . '_DO_UPDATE' => 'Update voor MITS ImageSlider uitvoeren?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE' => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC' => 'Ondersteuning voor lazyload activeren?',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE' => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Voer de module-update uit!</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC' => '',
  'MODULE_' . $modulname . '_UPDATE_FINISHED' => 'De module MITS ImageSlider is bijgewerkt.',
  'MODULE_' . $modulname . '_UPDATE_ERROR' => 'Fout',
  'MODULE_' . $modulname . '_UPDATE_MODUL' => 'Module bijwerken',
  'MODULE_' . $modulname . '_DELETE_MODUL' => 'MITS ImageSlider volledig van de server verwijderen',
  'MODULE_' . $modulname . '_CONFIRM_DELETE_MODUL' => 'Wilt u de module MITS ImageSlider met alle bestanden echt van de server verwijderen?',
  'MODULE_' . $modulname . '_DELETE_FINISHED' => 'De module MITS ImageSlider is van de server verwijderd.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS' => 'Ontbrekende resoluties en fallbacks genereren.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS_DESC' => 'Genereert automatisch WebP-, JPG/PNG-fallback- en srcset-varianten voor alle bestaande ImageSlider-afbeeldingen.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS' => 'Banner Manager importeren.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS_DESC' => 'Importeert bannergroepen en banneritems uit de Banner Manager in MITS ImageSlider.',
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
