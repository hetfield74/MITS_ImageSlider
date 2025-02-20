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
  '<p><strong>IMPORTANT:</strong> Before and after the repeating area, <strong>###SLIDERITEM###</strong> must be entered as a placeholder (see standard example).</p>
<p>The following placeholders are available:</p>
<ul>
  <li><strong>{ID}</strong> (ID of the slider entry)</li>
  <li><strong>{IMAGE}</strong> (Smallest Image address of the slider entry)</li>
  <li><strong>{MAINIMAGE}</strong> (MainImage address of the slider entry)</li>
  <li><strong>{TABLETIMAGE}</strong> (Image address for Tablet-View of the slider entry)</li>
  <li><strong>{MOBILEIMAGE}</strong> (Image address for Mobile-View of the slider entry)</li>
  <li><strong>{LINK}</strong> (URL des Slidereintrags)</li>
  <li><strong>{LINKTARGET}</strong> (URL of the slider entry)</li>
  <li><strong>{IMAGEALT}</strong> (Alt text for picture)</li>
  <li><strong>{TITLE}</strong> (Title for picturd)</li>
  <li><strong>{TEXT}</strong> (Text from the editor)</li>
</ul>
'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE'                     => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION'               => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>With MITS ImageSlider module allows you to create a photo slideshow on the home page of your shop. You can also link changing images with categories, products, content, or other shop sites without session loss or link to an external address.</p>
    <p>The already several thousand times proven MITS ImageSlider Module &copy; by Hetfield see the original by the manufacturer under <a target="_blank" href="https://www.merz-it-service.de/"><strong><u>MerZ IT-SerVice</u></strong></a> your modified eCommerce Shopsoftware.</p>
    <div><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Instructions for the module MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Instructions for the module MITS ImageSlider</u></strong></a></div>
    <div style="text-align:center;">
      <small>Only on Github is there always the latest version of the module!</small><br />
      <a style="background:#6a9;color:#444" target="_blank" href="https://github.com/hetfield74/MITS_ImageSlider" class="button" onclick="this.blur();">MITS_ImageSlider on Github</a>
    </div>
    <p>If you have any questions, problems or wishes for this module or other concerns about the modified eCommerce shopsoftware, simply contact us:</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">Contact page on merz-it-service.de</strong></a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE'              => 'Enable MITS ImageSlider Module?',
  'MODULE_' . $modulname . '_STATUS_DESC'               => 'Activate the module MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_TITLE'                => 'Displaying the MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_DESC'                 => 'Where to display the MITS ImageSlider, or be available? <br /><ul><li>start = only on the home page (default)</li><li>general = display on all pages (necessary when using the MITS ImageSlider on Smarty plugin with<br /><i>{getImageSlider slidergroup=mits_imageslider}</i> <br />in other template files except the index.html)</li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE'                => 'Select Slider Plugin',
  'MODULE_' . $modulname . '_TYPE_DESC'                 => 'Note: Set all plugins advance an existing jQuery library. If the template used in the shop do not meet this requirement, you bind this please before activating the module into your template. Also be sure that their chosen plugin is not already used to template (e.g. the bxSlider plugin in standard template tpl modified).',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE'         => 'Custom code for  Slider Plugin',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC'          => 'You can use your own code for the MITS ImageSlider. To do this, select the <i>custom</i> option as a plugin. Then enter your own code in this text field and insert the necessary placeholders. You must implement the necessary Javascript and CSS files including Javascript function calls for the slider yourself when making this selection. As an example, the standard code from the tpl_modified_resonsive matching the bxslider is stored during the module installation.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE'      => 'JavaScript files loaded from Slider Plugin?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC'       => 'The module uses the MITS ImageSlider JavaScript files from the MITS ImageSlider module when "true". When set to "alse" the javascript files are not loaded. The "false" is really only necessary if the desired slider plugin is already available through other customizations in the shop (for example, by manually installing the template or similar). When set to "false" only the file is mits_imageslider.js the respective plug-in folder (<i>includes/external/mits_imageslider/plugins/PLUGINNAME/</i>) loaded. If the plugin is selected <i>custom</i> no files are loaded and this setting is ignored.',
  'MODULE_' . $modulname . '_LOADCSS_TITLE'             => 'CSS files loaded from Slider Plugin?',
  'MODULE_' . $modulname . '_LOADCSS_DESC'              => 'The module MITS ImageSlider used when "true" the CSS files from the MITS ImageSlider module. When set to "alse" CSS files are not loaded. The "false" is really only necessary if the desired slider plugin is already available through other customizations in the shop (for example, by manual installation/adjustments in the template or similar). If the plugin is selected <i>custom</i> no files are loaded and this setting is ignored.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE'         => 'Maximum screen width for mobile picture',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC'          => 'Enter the value in pixels up to which resolution the mobile picture should be displayed. (Standard 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE'         => 'Maximum screen width for tablet image',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC'          => 'Enter the value in pixels up to which resolution the tablet picture should be displayed. (Standard 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'Display MITS ImageSlider entries per page in the Administration',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC'  => 'How much MITS ImageSlider entries to be displayed per page in the administration area of the store? The setting can be changed directly in the overview.',
  'MODULE_' . $modulname . '_UPDATE_TITLE'              => 'Modulupdate',
  'MODULE_' . $modulname . '_DO_UPDATE'                 => 'Do the updates for the MITS ImageSlider?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE'            => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC'             => 'Activate support for lazyload?',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE'    => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Please carry out module updates!</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC'     => ''
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
