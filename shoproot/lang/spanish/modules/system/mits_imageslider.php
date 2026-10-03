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
  '<p><strong>IMPORTANTE:</strong> Antes y despu&eacute;s del &aacute;rea repetida debe introducirse el placeholder <strong>###SLIDERITEM###</strong> (ver ejemplo est&aacute;ndar).</p><p>Placeholders disponibles:</p><ul><li><strong>{ID}</strong></li><li><strong>{IMAGE}</strong></li><li><strong>{MAINIMAGE}</strong></li><li><strong>{TABLETIMAGE}</strong></li><li><strong>{MOBILEIMAGE}</strong></li><li><strong>{LINK}</strong></li><li><strong>{LINKTARGET}</strong></li><li><strong>{IMAGEALT}</strong></li><li><strong>{TITLE}</strong></li><li><strong>{TEXT}</strong></li></ul>'
);

$lang_array = array(
  'MODULE_' . $modulname . '_TITLE' => 'MITS ImageSlider <span style="white-space:nowrap;">&copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span></span>',
  'MODULE_' . $modulname . '_DESCRIPTION' => '
    <a href="https://www.merz-it-service.de/" target="_blank">
      <img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;" />
    </a><br />
    <p>Con el m&oacute;dulo MITS ImageSlider puede crear una presentaci&oacute;n de im&aacute;genes en la p&aacute;gina de inicio de su tienda. Las im&aacute;genes pueden enlazarse con categor&iacute;as, productos, contenido, otras p&aacute;ginas de la tienda o direcciones externas.</p>
    <div style="text-align:center;margin:20px 0;"><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>MITS ImageSlider</u></strong></a></div>
    <p>MerZ IT-SerVice</p> 
    <div style="text-align:center;"><a style="background:#6a9;color:#444" target="_blank" href="https://www.merz-it-service.de/Kontakt.html" class="button" onclick="this.blur();">P&aacute;gina de contacto MerZ-IT-SerVice.de</a></div>
',
  'MODULE_' . $modulname . '_STATUS_TITLE' => '&iquest;Activar el m&oacute;dulo MITS ImageSlider?',
  'MODULE_' . $modulname . '_STATUS_DESC' => 'Activar el m&oacute;dulo MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_TITLE' => 'Visualizaci&oacute;n de MITS ImageSlider',
  'MODULE_' . $modulname . '_SHOW_DESC' => '&iquest;D&oacute;nde debe mostrarse o estar disponible MITS ImageSlider? <br /><ul><li>start = solo en la p&aacute;gina de inicio</li><li>general = disponible en todas las p&aacute;ginas, necesario para el plugin Smarty <br /><i>{getImageSlider slidergroup=mits_imageslider}</i></li></ul>',
  'MODULE_' . $modulname . '_TYPE_TITLE' => 'Seleccionar plugin slider',
  'MODULE_' . $modulname . '_TYPE_DESC' => 'Nota: todos los plugins requieren una biblioteca jQuery existente. Si la plantilla no la proporciona, incl&uacute;yala antes de activar el m&oacute;dulo.',
  'MODULE_' . $modulname . '_CUSTOM_CODE_TITLE' => 'C&oacute;digo personalizado',
  'MODULE_' . $modulname . '_CUSTOM_CODE_DESC' => 'Puede utilizar c&oacute;digo propio para MITS ImageSlider. Seleccione el plugin <i>custom</i>, introduzca su c&oacute;digo y use los placeholders necesarios.' . $available_slider_vars,
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_TITLE' => '&iquest;Cargar archivos JavaScript del plugin slider?',
  'MODULE_' . $modulname . '_LOADJAVASCRIPT_DESC' => 'Con true el m&oacute;dulo carga los archivos JavaScript de MITS ImageSlider. false es &uacute;til si el plugin ya se carga por otra v&iacute;a.',
  'MODULE_' . $modulname . '_LOADCSS_TITLE' => '&iquest;Cargar archivos CSS del plugin slider?',
  'MODULE_' . $modulname . '_LOADCSS_DESC' => 'Con true el m&oacute;dulo carga los archivos CSS de MITS ImageSlider. false es &uacute;til si el plugin ya est&aacute; integrado en la plantilla.',
  'MODULE_' . $modulname . '_MOBILEWIDTH_TITLE' => 'Anchura m&aacute;xima para imagen m&oacute;vil',
  'MODULE_' . $modulname . '_MOBILEWIDTH_DESC' => 'Introduzca la anchura m&aacute;xima en p&iacute;xeles para mostrar la imagen m&oacute;vil. (Predeterminado 600)',
  'MODULE_' . $modulname . '_TABLETWIDTH_TITLE' => 'Anchura m&aacute;xima para imagen tablet',
  'MODULE_' . $modulname . '_TABLETWIDTH_DESC' => 'Introduzca la anchura m&aacute;xima en p&iacute;xeles para mostrar la imagen tablet. (Predeterminado 1023)',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_TITLE' => 'Entradas MITS ImageSlider por p&aacute;gina en administraci&oacute;n',
  'MODULE_' . $modulname . '_MAX_DISPLAY_RESULTS_DESC' => '&iquest;Cu&aacute;ntas entradas MITS ImageSlider se mostrar&aacute;n por p&aacute;gina en administraci&oacute;n?',
  'MODULE_' . $modulname . '_UPDATE_TITLE' => 'Actualizaci&oacute;n del m&oacute;dulo',
  'MODULE_' . $modulname . '_DO_UPDATE' => '&iquest;Ejecutar la actualizaci&oacute;n de MITS ImageSlider?',
  'MODULE_' . $modulname . '_LAZYLOAD_TITLE' => 'Lazy Load',
  'MODULE_' . $modulname . '_LAZYLOAD_DESC' => '&iquest;Activar soporte lazyload?',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_TITLE' => ' <span style="font-weight:bold;color:#900;background:#ff6;padding:2px;border:1px solid #900;">Ejecute la actualizaci&oacute;n del m&oacute;dulo.</span>',
  'MODULE_' . $modulname . '_UPDATE_AVAILABLE_DESC' => '',
  'MODULE_' . $modulname . '_UPDATE_FINISHED' => 'El m&oacute;dulo MITS ImageSlider se ha actualizado.',
  'MODULE_' . $modulname . '_UPDATE_ERROR' => 'Error',
  'MODULE_' . $modulname . '_UPDATE_MODUL' => 'Actualizar m&oacute;dulo',
  'MODULE_' . $modulname . '_DELETE_MODUL' => 'Eliminar completamente MITS ImageSlider del servidor',
  'MODULE_' . $modulname . '_CONFIRM_DELETE_MODUL' => '&iquest;Desea eliminar realmente el m&oacute;dulo MITS ImageSlider con todos sus archivos del servidor?',
  'MODULE_' . $modulname . '_DELETE_FINISHED' => 'El m&oacute;dulo MITS ImageSlider se ha eliminado del servidor.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS' => 'Generar resoluciones y fallbacks faltantes.',
  'MODULE_' . $modulname . '_GENERATE_VARIANTS_DESC' => 'Regenera autom&aacute;ticamente variantes WebP, fallback JPG/PNG y srcset para todas las im&aacute;genes ImageSlider existentes.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS' => 'Importar Banner Manager.',
  'MODULE_' . $modulname . '_IMPORT_BANNERS_DESC' => 'Importa grupos y entradas de banners desde el Banner Manager a MITS ImageSlider.',
);

foreach ($lang_array as $key => $val) {
    defined($key) || define($key, $val);
}
