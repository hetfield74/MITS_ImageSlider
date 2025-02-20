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
        </span>'
    );

    foreach ($lang_array as $key => $val) {
        defined($key) || define($key, $val);
    }
}