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
	
defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
  $add_contents[BOX_HEADING_TOOLS][] = array(
    'admin_access_name' => 'mits_imageslider',
    'filename'          => 'mits_imageslider.php',
    'boxname'           => MITS_BOX_IMAGESLIDER,
    'parameters'         => '',
    'ssl'               => ''
  );
}
