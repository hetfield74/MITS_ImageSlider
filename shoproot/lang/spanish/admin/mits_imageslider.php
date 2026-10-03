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
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'Nombre ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Imagen');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'Grupo ImageSlider');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Orden');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Estado');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Acci&oacute;n');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'Nueva imagen');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Editar imagen');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Eliminar imagen');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Imagen:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'a&ntilde;adido el:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', '&uacute;ltima modificaci&oacute;n:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'Imagen no existente');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Inserte una nueva imagen con todos los datos necesarios.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Realice los cambios necesarios');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'T&iacute;tulo para imagen:');
defined('TEXT_IMAGESLIDERS_ALT') or define('TEXT_IMAGESLIDERS_ALT', 'Texto alt para imagen: <small style="font-weight:normal">(&lt;img <b>alt=""</b>&gt;)</small>');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Nombre para entrada de imagen:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Imagen:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Imagen para tabletas (600px - 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Imagen para vista m&oacute;vil (- 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'La imagen enlaza con la siguiente URL:');
defined('TEXT_IMAGESLIDERS_LINKTITLE') or define('TEXT_IMAGESLIDERS_LINKTITLE', 'T&iacute;tulo del enlace: <small style="font-weight:normal">(&lt;a <b>title=""</b>&gt;)</small>');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Ventana destino:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Tipo de enlace:');
defined('TEXT_URL') or define('TEXT_URL', 'URL del enlace:');
defined('NONE_TARGET') or define('NONE_TARGET', 'sin destino');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'Enlace a producto (introducir solo productsID en la URL del enlace)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'Enlace a categor&iacute;a (introducir solo catID en la URL del enlace)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'Enlace a p&aacute;gina de contenido (introducir solo coID en la URL del enlace)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'Enlace a fabricante (introducir solo mID en la URL del enlace)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Enlace interno de la tienda (p. ej. account.php o newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'Enlace externo (p. ej. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Descripci&oacute;n de imagen:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', '&iquest;Seguro que desea eliminar esta imagen?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', '&iquest;Eliminar tambi&eacute;n el archivo de imagen?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: el directorio %s no tiene permisos de escritura. Corrija los derechos de acceso.');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: el directorio %s no existe.');
defined('TEXT_IMAGESLIDER_SORT_PAGE_NOTE') or define('TEXT_IMAGESLIDER_SORT_PAGE_NOTE', 'Cambie el orden mediante arrastrar y soltar dentro de la p&aacute;gina actual. La ordenaci&oacute;n se guarda autom&aacute;ticamente por grupo de slider al soltar; las entradas no se pueden mover entre grupos.');
defined('TEXT_IMAGESLIDER_SORT_HINT') or define('TEXT_IMAGESLIDER_SORT_HINT', 'Arrastre las filas mediante el icono de agarre a la posici&oacute;n deseada en esta p&aacute;gina. La ordenaci&oacute;n se guarda autom&aacute;ticamente.');
defined('TEXT_IMAGESLIDER_SORT_SAVING') or define('TEXT_IMAGESLIDER_SORT_SAVING', 'Guardando ordenaci&oacute;n...');
defined('TEXT_IMAGESLIDER_SORT_SAVED') or define('TEXT_IMAGESLIDER_SORT_SAVED', 'Ordenaci&oacute;n guardada.');
defined('TEXT_IMAGESLIDER_SORT_NO_ITEMS') or define('TEXT_IMAGESLIDER_SORT_NO_ITEMS', 'No se han enviado entradas ordenables.');
defined('TEXT_IMAGESLIDER_SORT_ERROR') or define('TEXT_IMAGESLIDER_SORT_ERROR', 'No se pudo guardar la ordenaci&oacute;n. Vuelva a cargar la p&aacute;gina e int&eacute;ntelo de nuevo.');
defined('TEXT_IMAGESLIDER_SORT_SAME_GROUP_ONLY') or define('TEXT_IMAGESLIDER_SORT_SAME_GROUP_ONLY', 'Las entradas solo se pueden ordenar dentro del mismo grupo de slider.');

defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Mostrando <b>%d</b> a <b>%d</b> (de <b>%d</b> entradas ImageSlider)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Activo');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Activar');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Inactivo');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'Desactivar');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Activar');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'Desactivar');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'Grupo ImageSlider:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Seleccione un grupo ImageSlider existente o introduzca un nuevo grupo ImageSlider abajo.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Para mostrar un ImageSlider en la plantilla, la plantilla debe ampliarse.<br/>Ejemplo: grupo ImageSlider <i>MITS_IMAGESLIDER</i>, se puede mostrar en index.html con <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Instrucciones MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Instrucciones MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Error: se requiere t&iacute;tulo ImageSlider.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Error: se requiere grupo ImageSlider.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Error: se requiere imagen ImageSlider.');
defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'AAAA-MM-DD');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'V&aacute;lido hasta:');
defined('TEXT_IMAGESLIDERS_IMPRESSIONS') or define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impresiones/vistas.');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'V&aacute;lido desde:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Notas de caducidad:</b><ul><li>La fecha de inicio y fin se pueden combinar.</li><li>Deje estos campos vac&iacute;os si la imagen debe mostrarse sin l&iacute;mites de fecha.</li><li>El estado manual no se cambia autom&aacute;ticamente.</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Notas de programaci&oacute;n:</b><ul><li>Si se define una fecha, la imagen se muestra desde esa fecha.</li><li>La comprobaci&oacute;n de fechas se realiza en el frontend sin cambiar el estado manual.</li></ul>');
defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Fecha de alta:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'V&aacute;lido desde: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'V&aacute;lido hasta: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_RECURRING') or define('TEXT_IMAGESLIDERS_RECURRING', 'Validez recurrente:');
defined('TEXT_IMAGESLIDERS_RECURRING_YEARLY') or define('TEXT_IMAGESLIDERS_RECURRING_YEARLY', 'repetir anualmente');
defined('TEXT_IMAGESLIDERS_RECURRING_NOTE') or define('TEXT_IMAGESLIDERS_RECURRING_NOTE', '<b>Validez recurrente:</b><ul><li>Si est&aacute; activado, solo se usan d&iacute;a y mes de V&aacute;lido desde/hasta.</li><li>Ejemplo: 2026-12-24 a 2027-01-06 se muestra cada a&ntilde;o del 24.12. al 06.01.</li><li>El estado manual sigue siendo el interruptor principal.</li></ul>');
defined('MITS_IMAGESLIDER_IMPORT_HEADING') or define('MITS_IMAGESLIDER_IMPORT_HEADING', 'MITS ImageSlider - Importaci&oacute;n del Banner Manager');
defined('MITS_IMAGESLIDER_IMPORT_INTRO') or define('MITS_IMAGESLIDER_IMPORT_INTRO', 'Esta herramienta importa grupos y entradas de banners desde el Banner Manager a MITS ImageSlider.');
defined('MITS_IMAGESLIDER_IMPORT_DRY_RUN') or define('MITS_IMAGESLIDER_IMPORT_DRY_RUN', 'Vista previa / dry run');
defined('MITS_IMAGESLIDER_IMPORT_EXECUTE') or define('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Ejecutar importaci&oacute;n');
defined('MITS_IMAGESLIDER_IMPORT_OVERWRITE') or define('MITS_IMAGESLIDER_IMPORT_OVERWRITE', 'Actualizar banners ya importados');
defined('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER') or define('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER', 'Filtrar grupo de banners');
defined('MITS_IMAGESLIDER_IMPORT_RESULT') or define('MITS_IMAGESLIDER_IMPORT_RESULT', 'Resultado de importaci&oacute;n');
defined('MITS_IMAGESLIDER_IMPORT_TOTAL') or define('MITS_IMAGESLIDER_IMPORT_TOTAL', 'Total');
defined('MITS_IMAGESLIDER_IMPORT_IMPORTED') or define('MITS_IMAGESLIDER_IMPORT_IMPORTED', 'Importados');
defined('MITS_IMAGESLIDER_IMPORT_UPDATED') or define('MITS_IMAGESLIDER_IMPORT_UPDATED', 'Actualizados');
defined('MITS_IMAGESLIDER_IMPORT_SKIPPED') or define('MITS_IMAGESLIDER_IMPORT_SKIPPED', 'Omitidos');
defined('MITS_IMAGESLIDER_IMPORT_ERRORS') or define('MITS_IMAGESLIDER_IMPORT_ERRORS', 'Errores');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE', 'No se encontr&oacute; la tabla de banners.');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS', 'No se encontraron banners.');
defined('MITS_IMAGESLIDER_IMPORT_BACK') or define('MITS_IMAGESLIDER_IMPORT_BACK', 'Volver a ImageSlider');
