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

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

class mits_imageslider {
  var $code, $title, $description, $enabled;

  function __construct() {
    $this->code = 'mits_imageslider';
    $this->version = '2.04';
    $this->title = MODULE_MITS_IMAGESLIDER_TEXT_TITLE . ' - v' . $this->version;
    $this->description = MODULE_MITS_IMAGESLIDER_TEXT_DESCRIPTION;
    $this->sort_order = ((defined('MODULE_MITS_IMAGESLIDER_SORT_ORDER')) ? MODULE_MITS_IMAGESLIDER_SORT_ORDER : 0);
    $this->enabled = ((MODULE_MITS_IMAGESLIDER_STATUS == 'true') ? true : false);
  }

  function process($file) {
    if (isset($_POST['imageslider_update']) && $_POST['imageslider_update'] == true) {
      if (defined('MODULE_MITS_IMAGESLIDER_VERSION')) {
        xtc_db_query("UPDATE ".TABLE_CONFIGURATION." SET configuration_value = '" . $this->version . "' WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_VERSION'");
      } else {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_VERSION', '" . $this->version . "',  '6', '7', NULL, now())");
      }

      if (!defined('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE')) {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE', '<div class=\"content_banner cf\">
  <ul class=\"bxcarousel_slider\">
    ###SLIDERITEM###
    <li>
      <a href=\"{LINK}\" title=\"{TITLE}\" {LINKTARGET}>
        <img src=\"{IMAGE}\" alt=\"{IMAGEALT}\" title=\"{TITLE}\" />
      </a>
    </li>
    ###SLIDERITEM###
  </ul>
</div>',  '6', '5', 'xtc_cfg_textarea(', now())");
      }

      if (!defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD')) {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_LAZYLOAD', 'false',  '6', '8', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
      }

      xtc_db_query("UPDATE ".TABLE_CONFIGURATION."
                       SET set_function = 'xtc_cfg_select_option(array(\'bxSlider tpl_modified\', \'bxSlider\', \'Slick tpl_modified\', \'Slick\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), '
                     WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_TYPE'");

      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) NOT NULL");

      $check_mobilimg_field = false;
      $check_mobilimg_field_rows = xtc_db_query('DESCRIBE ' . TABLE_CATEGORIES);
      while ($check_mobilimg_field_row = xtc_db_fetch_array($check_mobilimg_field_rows)) {
        if ($check_mobilimg_field_row['Field'] == 'imagesliders_mobile_image') {
          $check_mobilimg_field = true;
        }
      }
      if ($check_mobilimg_field == false) {
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
      }

      $check_proslidergroup_field = false;
      $check_proslidergroup_rows = xtc_db_query('DESCRIBE ' . TABLE_PRODUCTS);
      while ($proslidergroup_row = xtc_db_fetch_array($check_proslidergroup_rows)) {
        if ($proslidergroup_row['Field'] == 'imagesliders_group') {
          $check_proslidergroup_field = true;
        }
      }
      if ($check_proslidergroup_field == false) {
        xtc_db_query('ALTER TABLE ' . TABLE_PRODUCTS . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }
      $check_catslidergroup_field = false;
      $check_catslidergroup_rows = xtc_db_query('DESCRIBE ' . TABLE_CATEGORIES);
      while ($catslidergroup_row = xtc_db_fetch_array($check_catslidergroup_rows)) {
        if ($catslidergroup_row['Field'] == 'imagesliders_group') {
          $check_catslidergroup_field = true;
        }
      }
      if ($check_catslidergroup_field == false) {
        xtc_db_query('ALTER TABLE ' . TABLE_CATEGORIES . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }
      $check_cmsslidergroup_field = false;
      $check_cmsslidergroup_rows = xtc_db_query('DESCRIBE ' . TABLE_CONTENT_MANAGER);
      while ($cmsslidergroup_row = xtc_db_fetch_array($check_cmsslidergroup_rows)) {
        if ($cmsslidergroup_row['Field'] == 'imagesliders_group') {
          $check_cmsslidergroup_field = true;
        }
      }
      if ($check_cmsslidergroup_field == false) {
        xtc_db_query('ALTER TABLE ' . TABLE_CONTENT_MANAGER . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }

      $check_sliderexpiredate_field = false;
      $check_sliderexpiredate_rows = xtc_db_query('DESCRIBE ' . TABLE_MITS_IMAGESLIDER);
      while ($sliderexpiredate_row = xtc_db_fetch_array($check_sliderexpiredate_rows)) {
        if ($sliderexpiredate_row['Field'] == 'date_scheduled') {
          $check_sliderexpiredate_field = true;
        }
      }
      if ($check_sliderexpiredate_field == false) {
        xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled datetime default NULL');
        xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date datetime default NULL');
      }
    }
    if (isset($_POST['configuration']) && $_POST['configuration']['MODULE_MITS_IMAGESLIDER_STATUS'] == 'true') {
      //xtc_redirect(xtc_href_link(FILENAME_MITS_IMAGESLIDER));
    }
  }

  function display() {
    return array(
      'text' => '<br /><b>' . MODULE_MITS_IMAGESLIDER_UPDATE_TITLE . '</b><br /><label>' . xtc_draw_checkbox_field('imageslider_update', false) . ' ' . MODULE_MITS_IMAGESLIDER_DO_UPDATE . '</label><br/>' .
        '<br /><div align="center">' . xtc_button(BUTTON_SAVE) .
        xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . "</div>"
    );
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    if (xtc_db_num_rows(xtc_db_query("SHOW TABLES LIKE '" . TABLE_MITS_IMAGESLIDER . "'")) && (!xtc_db_num_rows("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_STATUS'"))) {
      xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " CHANGE COLUMN `imagesliders` `mits_imageslider` INT(1) NOT NULL DEFAULT '0'");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `imagesliders_group` VARCHAR(255) NOT NULL DEFAULT 'mits_imageslider'");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) NOT NULL");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
      xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled datetime default NULL');
      xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date datetime default NULL');
      @unlink(DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'imagesliders.php');
      @unlink(DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/application_top.php.txt');
      @unlink(DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/column_left.php.txt');
      @unlink(DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/imagesliders.php');
      @unlink(DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/german.php.txt');
      @unlink(DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/imagesliders.php');
      @unlink(DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/english.php.txt');
      @unlink(DIR_FS_DOCUMENT_ROOT . 'inc/xtc_get_categories_name.inc.php');
    } else {
      xtc_db_query("CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER . " (
					  `imagesliders_id` int(11) NOT NULL auto_increment,
					  `imagesliders_name` varchar(255) NOT NULL default '',
					  `date_scheduled` datetime default NULL,
					  `expires_date` datetime default NULL,
					  `date_added` datetime default NULL,
					  `last_modified` datetime default NULL,
					  `status` tinyint(1) NOT NULL default '0',
					  `sorting` tinyint(1) NOT NULL default '0',
					  `imagesliders_group` varchar(255) NOT NULL default 'mits_imageslider',
					  PRIMARY KEY  (`imagesliders_id`)
					)");
      xtc_db_query("CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER_INFO . " (
					  `imagesliders_id` int(11) NOT NULL,
					  `languages_id` int(11) NOT NULL,
					  `imagesliders_title` varchar(255) NOT NULL,
					  `imagesliders_url` varchar(255) NOT NULL,
					  `imagesliders_url_target` tinyint(1) NOT NULL default '0',
					  `imagesliders_url_typ` tinyint(1) NOT NULL default '0',
					  `imagesliders_description` text,
					  `imagesliders_image` varchar(255) default NULL,
					  `imagesliders_tablet_image` varchar(255) default NULL,
					  `imagesliders_mobile_image` varchar(255) default NULL,
					  `url_clicked` int(5) NOT NULL default '0',
					  `date_last_click` datetime default NULL,
					  PRIMARY KEY  (`imagesliders_id`,`languages_id`)
					)");
      xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `mits_imageslider` INT( 1 ) NOT NULL DEFAULT '0'");
      xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider` = 1");
    }
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_STATUS', 'true',  '6', '1', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_SHOW', 'start',  '6', '2', 'xtc_cfg_select_option(array(\'start\', \'general\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_TYPE', 'Slick tpl_modified',  '6', '4', 'xtc_cfg_select_option(array(\'bxSlider tpl_modified\', \'bxSlider\', \'Slick tpl_modified\', \'Slick\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT', 'false',  '6', '6', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_LOADCSS', 'false',  '6', '7', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_LAZYLOAD', 'false',  '6', '8', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MAX_DISPLAY_IMAGESLIDERS_RESULTS', '20',  '6', '9', NULL, now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_VERSION', '" . $this->version . "',  '6', '0', NULL, now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE', '<div class=\"content_slider cf\">
  <div class=\"slider_home\">  
    ###SLIDERITEM###    
    <div class=\"slider_item\">
      <a href=\"{LINK}\" title=\"{TITLE}\" {LINKTARGET}>
        <picture>
          <source media=\"(max-width:600px)\" data-srcset=\"{MOBILEIMAGE}\">
          <source media=\"(max-width:1023px)\" data-srcset=\"{TABLETIMAGE}\">
          <source data-srcset=\"{MAINIMAGE}\">
          <img class=\"lazyload\" data-src=\"{MAINIMAGE}\" alt=\"{IMAGEALT}\" title=\"{TITLE}\" />
        </picture>        
      </a>
    </div>
    ###SLIDERITEM###
  </div>
</div>',  '6', '5', 'xtc_cfg_textarea(', now())");
    xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
    xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
    xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
  }

  function remove() {
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER);
    xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER_INFO);
    xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `mits_imageslider`");
    xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " DROP imagesliders_group");
    xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " DROP imagesliders_group");
    xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " DROP imagesliders_group");
  }

  function keys() {
    $key = array(
      'MODULE_MITS_IMAGESLIDER_STATUS',
      'MODULE_MITS_IMAGESLIDER_SHOW',
      'MODULE_MITS_IMAGESLIDER_TYPE',
      'MODULE_MITS_IMAGESLIDER_CUSTOM_CODE',
      'MODULE_MITS_IMAGESLIDER_LOADJAVASCRIPT',
      'MODULE_MITS_IMAGESLIDER_LOADCSS',
      'MODULE_MITS_IMAGESLIDER_LAZYLOAD',
      'MAX_DISPLAY_IMAGESLIDERS_RESULTS'
    );

    return $key;
  }
}

?>