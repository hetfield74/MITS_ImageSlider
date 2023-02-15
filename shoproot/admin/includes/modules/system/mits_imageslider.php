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
    $this->name = 'MODULE_' . strtoupper($this->code);
    $this->version = '2.08';
    $this->sort_order = defined($this->name . '_SORT_ORDER') ? constant($this->name . '_SORT_ORDER') : 0;
    $this->enabled = (defined($this->name . '_STATUS') && (constant($this->name . '_STATUS') == 'true') ? true : false);
    $version_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_VERSION'");
    if (xtc_db_num_rows($version_query)) {
      $version = xtc_db_fetch_array($version_query, true);
      $this->do_update = ($version['configuration_value'] != $this->version && defined($this->name . '_UPDATE_AVAILABLE_TITLE')) ? constant($this->name . '_UPDATE_AVAILABLE_TITLE') : '';
    } elseif ($this->enabled) {
      $this->do_update = (defined($this->name . '_UPDATE_AVAILABLE_TITLE')) ? constant($this->name . '_UPDATE_AVAILABLE_TITLE') : '';
    } else {
      $this->do_update = '';
    }
    $this->title = defined($this->name . '_TITLE') ? constant($this->name . '_TITLE') . ' - v' . $this->version . $this->do_update : $this->code . ' - v' . $this->version . $this->do_update;
    $this->description = defined($this->name . '_DESCRIPTION') ? constant($this->name . '_DESCRIPTION') : '';
  }

  function process($file) {
    if (isset($_POST['imageslider_update']) && $_POST['imageslider_update'] == true) {
      xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . strtolower($this->code) . "` = 0 WHERE customers_id = 'groups'");

      $version_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_VERSION'");
      if (xtc_db_num_rows($version_query)) {
        xtc_db_query("UPDATE ".TABLE_CONFIGURATION." SET configuration_value = '" . $this->version . "' WHERE configuration_key = '" . $this->name . "_VERSION'");
      } else {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_VERSION', '" . $this->version . "', 6, 99, NULL, now())");
      }

      $max_result_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
      if (xtc_db_num_rows($max_result_query)) {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
      }

      $max_result_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_RESULTS'");
      if (xtc_db_num_rows($max_result_query)) {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
      }

      $max_result_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_MAX_DISPLAY_RESULTS'");
      if (!xtc_db_num_rows($max_result_query)) {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MAX_DISPLAY_RESULTS', '20', 6, 2, NULL, now())");
      }

      if (!defined($this->name . '_CUSTOM_CODE')) {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_CUSTOM_CODE', '<div class=\"content_slider cf\">
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
      }

      if (!defined($this->name . '_LAZYLOAD')) {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LAZYLOAD', 'false', 6, 8, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
      }

      xtc_db_query("UPDATE ".TABLE_CONFIGURATION."
                       SET set_function = 'xtc_cfg_select_option(array(\'bxSlider tpl_modified\', \'bxSlider\', \'Slick tpl_modified\', \'Slick\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), '
                     WHERE configuration_key = '" . $this->name . "_TYPE'");

      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
      xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) NOT NULL");

      $check_mobilimg_field = false;
      $check_mobilimg_field_rows = xtc_db_query('DESCRIBE ' . TABLE_MITS_IMAGESLIDER_INFO);
      while ($check_mobilimg_field_row = xtc_db_fetch_array($check_mobilimg_field_rows)) {
        if ($check_mobilimg_field_row['Field'] == 'imagesliders_mobile_image') {
          $check_mobilimg_field = true;
        }
      }
      if ($check_mobilimg_field === false) {
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
      if ($check_proslidergroup_field === false) {
        xtc_db_query('ALTER TABLE ' . TABLE_PRODUCTS . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }
      $check_catslidergroup_field = false;
      $check_catslidergroup_rows = xtc_db_query('DESCRIBE ' . TABLE_CATEGORIES);
      while ($catslidergroup_row = xtc_db_fetch_array($check_catslidergroup_rows)) {
        if ($catslidergroup_row['Field'] == 'imagesliders_group') {
          $check_catslidergroup_field = true;
        }
      }
      if ($check_catslidergroup_field === false) {
        xtc_db_query('ALTER TABLE ' . TABLE_CATEGORIES . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }
      $check_cmsslidergroup_field = false;
      $check_cmsslidergroup_rows = xtc_db_query('DESCRIBE ' . TABLE_CONTENT_MANAGER);
      while ($cmsslidergroup_row = xtc_db_fetch_array($check_cmsslidergroup_rows)) {
        if ($cmsslidergroup_row['Field'] == 'imagesliders_group') {
          $check_cmsslidergroup_field = true;
        }
      }
      if ($check_cmsslidergroup_field === false) {
        xtc_db_query('ALTER TABLE ' . TABLE_CONTENT_MANAGER . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
      }

      $check_sliderexpiredate_field = false;
      $check_sliderexpiredate_rows = xtc_db_query('DESCRIBE ' . TABLE_MITS_IMAGESLIDER);
      while ($sliderexpiredate_row = xtc_db_fetch_array($check_sliderexpiredate_rows)) {
        if ($sliderexpiredate_row['Field'] == 'date_scheduled') {
          $check_sliderexpiredate_field = true;
        }
      }
      if ($check_sliderexpiredate_field === false) {
        xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled datetime default NULL');
        xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date datetime default NULL');
      }
    }
  }

  function display() {
    $do_update = ($this->do_update != '') ? $this->do_update . '<br />' : '';
    return array(
      'text' => '<br /><b>' . MODULE_MITS_IMAGESLIDER_UPDATE_TITLE . '</b><br />
        ' . $do_update . '
         <label>' . xtc_draw_checkbox_field('imageslider_update', false) . ' ' . MODULE_MITS_IMAGESLIDER_DO_UPDATE . '</label><br/>' .
        '<br /><div align="center">' . xtc_button(BUTTON_SAVE) .
        xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . "</div>"
    );
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    if (xtc_db_num_rows(xtc_db_query("SHOW TABLES LIKE '" . TABLE_MITS_IMAGESLIDER . "'")) && (!xtc_db_num_rows("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'"))) {
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
      xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `" . $this->code . "` INT(1) NOT NULL DEFAULT '0'");
      xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . $this->code . "` = 1 WHERE customers_id != 'groups'");
    }
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_STATUS', 'true', 6, 1, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_SHOW', 'start', 6, 2, 'xtc_cfg_select_option(array(\'start\', \'general\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TYPE', 'Slick tpl_modified', 6, 4, 'xtc_cfg_select_option(array(\'bxSlider tpl_modified\', \'bxSlider\', \'Slick tpl_modified\', \'Slick\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADJAVASCRIPT', 'false', 6, 6, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADCSS', 'false', 6, 7, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LAZYLOAD', 'false', 6, 8, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MAX_DISPLAY_RESULTS', '20', 6, 9, NULL, now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_VERSION', '" . $this->version . "', 6, 99, NULL, now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_CUSTOM_CODE', '<div class=\"content_slider cf\">
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
</div>', 6, 5, 'xtc_cfg_textarea(', now())");
    xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
    xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
    xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
  }

  function remove() {
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE '" . $this->name . "_%'");
    xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER);
    xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER_INFO);
    xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `" . $this->code . "`");
    xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " DROP imagesliders_group");
    xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " DROP imagesliders_group");
    xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " DROP imagesliders_group");
  }

  function keys() {
    $key = array(
      $this->name . '_STATUS',
      $this->name . '_SHOW',
      $this->name . '_TYPE',
      $this->name . '_CUSTOM_CODE',
      $this->name . '_LOADJAVASCRIPT',
      $this->name . '_LOADCSS',
      $this->name . '_LAZYLOAD',
      $this->name . '_MAX_DISPLAY_RESULTS'
    );

    return $key;
  }
}
