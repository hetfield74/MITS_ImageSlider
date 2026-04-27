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

class mits_imageslider
{
    public $code;
    public $name;
    public $version;
    public $sort_order;
    public $title;
    public $description;
    public $enabled = false;
    public $do_update;
    private $_check = null;

    public function __construct()
    {
        $this->code = 'mits_imageslider';
        $this->name = 'MODULE_' . strtoupper($this->code);
        $this->version = '2.26';
        $this->sort_order = defined($this->name . '_SORT_ORDER') ? constant($this->name . '_SORT_ORDER') : 0;
        $this->enabled = defined($this->name . '_STATUS') && (constant($this->name . '_STATUS') == 'true');

        $version_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_VERSION'");
        if (xtc_db_num_rows($version_query)) {
            $version = xtc_db_fetch_array($version_query, true);
            $this->do_update = ($version['configuration_value'] != $this->version && defined($this->name . '_UPDATE_AVAILABLE_TITLE')) ? constant($this->name . '_UPDATE_AVAILABLE_TITLE') : '';
        } elseif ($this->enabled) {
            $this->do_update = (defined($this->name . '_UPDATE_AVAILABLE_TITLE')) ? constant($this->name . '_UPDATE_AVAILABLE_TITLE') : '';
        } else {
            $this->do_update = '';
        }

        $this->title = (defined($this->name . '_TITLE') ? constant($this->name . '_TITLE') : $this->code) . ' - v' . $this->version . $this->do_update;
        $this->description = '';
        if ($this->do_update != '') {
            $this->description .= '<a class="button btnbox but_green" style="text-align:center;" onclick="this.blur();" href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code . '&action=update') . '">' . constant($this->name . '_UPDATE_MODUL') . '</a><br>';
        }
        $this->description .= defined($this->name . '_DESCRIPTION') ? constant($this->name . '_DESCRIPTION') : '';
        if (!$this->enabled) {
            $this->description .= '<div style="text-align:center;margin:30px 0"><a class="button but_red" style="text-align:center;" onclick="return confirmLink(\'' . constant($this->name . '_CONFIRM_DELETE_MODUL') . '\', \'\' ,this);" href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=' . $this->code . '&action=custom') . '">' . constant(
                $this->name . '_DELETE_MODUL'
              ) . '</a></div><br>';
        } else {
            $this->description .= '<div style="text-align:center;margin:10px 0"><a class="button but_red" style="text-align:center;" onclick="return confirmLink(\'' . constant($this->name . '_GENERATE_VARIANTS') . '\', \'\' ,this);" href="' . xtc_href_link(FILENAME_MITS_IMAGESLIDER_GENERATE_VARIANTS) . '">' . constant(
                $this->name . '_GENERATE_VARIANTS'
              ) . '</a></div><br>';
        }
    }

    function process($file)
    {
    }

    function display()
    {
        return array(
          'text' => '<br><div style="text-align: center">' . xtc_button(BUTTON_SAVE) .
            xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . "</div>"
        );
    }

    function check()
    {
        if (!isset($this->_check)) {
            if (defined($this->name . '_STATUS')) {
                $this->_check = true;
            } else {
                $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'");
                $this->_check = xtc_db_num_rows($check_query);
            }
        }
        return $this->_check;
    }

    function install()
    {
        if (xtc_db_num_rows(xtc_db_query("SHOW TABLES LIKE '" . TABLE_MITS_IMAGESLIDER . "'")) && (!xtc_db_num_rows("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'"))) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " CHANGE COLUMN `imagesliders` `mits_imageslider` INT(1) NOT NULL DEFAULT '0'");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `imagesliders_group` VARCHAR(255) NOT NULL DEFAULT 'mits_imageslider'");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) NOT NULL");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled DATETIME DEFAULT NULL');
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date DATETIME DEFAULT NULL');
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_linktitle` VARCHAR(255) NULL AFTER `imagesliders_title`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_alt` VARCHAR(255) NULL AFTER `imagesliders_title`");
            $this->removeOldFiles();
        } else {
            xtc_db_query(
              "CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER . " (
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
					)"
            );
            xtc_db_query(
              "CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER_INFO . " (
					  `imagesliders_id` INT(11) NOT NULL,
					  `languages_id` INT(11) NOT NULL,
					  `imagesliders_title` VARCHAR(255) NOT NULL,
					  `imagesliders_alt` VARCHAR(255) NULL,
					  `imagesliders_linktitle` VARCHAR(255) NULL,
					  `imagesliders_url` VARCHAR(255) NOT NULL,
					  `imagesliders_url_target` TINYINT(1) NOT NULL DEFAULT '0',
					  `imagesliders_url_typ` TINYINT(1) NOT NULL DEFAULT '0',
					  `imagesliders_description` TEXT,
					  `imagesliders_image` VARCHAR(255) DEFAULT NULL,
					  `imagesliders_image_width` FLOAT NOT NULL DEFAULT '0',
					  `imagesliders_image_height` FLOAT NOT NULL DEFAULT '0',
					  `imagesliders_tablet_image` VARCHAR(255) DEFAULT NULL,
					  `imagesliders_tablet_image_width` FLOAT NOT NULL DEFAULT '0',
					  `imagesliders_tablet_image_height` FLOAT NOT NULL DEFAULT '0',
					  `imagesliders_mobile_image` VARCHAR(255) DEFAULT NULL,
					  `imagesliders_mobile_image_height` FLOAT NOT NULL DEFAULT '0',
					  `imagesliders_mobile_image_width` FLOAT NOT NULL DEFAULT '0',
					  `url_clicked` int(5) NOT NULL DEFAULT '0',
					  `date_last_click` datetime DEFAULT NULL,
					  PRIMARY KEY  (`imagesliders_id`,`languages_id`)
					)"
            );
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `" . $this->code . "` INT(1) NOT NULL DEFAULT '0'");
            xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . $this->code . "` = 1 WHERE customers_id != 'groups'");
        }
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_STATUS', 'true', 6, 1, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_SHOW', 'general', 6, 2, 'xtc_cfg_select_option(array(\'start\', \'general\'), ', now())");
        xtc_db_query(
          "INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TYPE', 'Splide tpl_modified_nova', 6, 4, 'xtc_cfg_select_option(array(\'Splide tpl_modified_nova\', \'Splide\', \'Slick tpl_modified\', \'Slick\', \'bxSlider tpl_modified\', \'bxSlider\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ', now())"
        );
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADJAVASCRIPT', 'false', 6, 6, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADCSS', 'false', 6, 7, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LAZYLOAD', 'false', 6, 8, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MOBILEWIDTH', '600', 6, 9, NULL, now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TABLETWIDTH', '1023', 6, 10, NULL, now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MAX_DISPLAY_RESULTS', '20', 6, 20, NULL, now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_VERSION', '" . $this->version . "', 6, 99, NULL, now())");
        xtc_db_query(
          "INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_CUSTOM_CODE', '<div class=\"content_slider cf\">
  <div class=\"slider_home\">  
    ###SLIDERITEM###    
    <div class=\"slider_item\">
      <a href=\"{LINK}\" title=\"{TITLE}\" {LINKTARGET}>
        {PICTURESET} 
      </a>
    </div>
    ###SLIDERITEM###
  </div>
</div>', 6, 5, 'xtc_cfg_textarea(', now())"
        );
        xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
        xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");
        xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " ADD COLUMN imagesliders_group VARCHAR(255) NULL");

        xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `mits_imageslider_regenerate_variants` INT(1) NOT NULL DEFAULT '0'");
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_regenerate_variants` = 1 WHERE customers_id != 'groups'");
    }


    public function update()
    {
        global $messageStack;

        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . strtolower($this->code) . "` = 0 WHERE customers_id = 'groups'");

        $version_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_VERSION'");
        if (xtc_db_num_rows($version_query)) {
            xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $this->version . "' WHERE configuration_key = '" . $this->name . "_VERSION'");
        } else {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_VERSION', '" . $this->version . "', 6, 99, NULL, now())");
        }

        $max_result_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
        if (xtc_db_num_rows($max_result_query)) {
            xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
        }

        $max_result_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_RESULTS'");
        if (xtc_db_num_rows($max_result_query)) {
            xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_RESULTS'");
        }

        if (!defined($this->name . '_MAX_DISPLAY_RESULTS')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MAX_DISPLAY_RESULTS', '20', 6, 2, NULL, now())");
        }

        $custom_code_key = $this->name . "_CUSTOM_CODE";

        $old_default_custom_code = '<div class="content_slider cf">
  <div class="slider_home">  
    ###SLIDERITEM###    
    <div class="slider_item">
      <a href="{LINK}" title="{TITLE}" {LINKTARGET}>
        <picture>
          <source media="(max-width:600px)" data-srcset="{MOBILEIMAGE}">
          <source media="(max-width:1023px)" data-srcset="{TABLETIMAGE}">
          <source data-srcset="{MAINIMAGE}">
          <img class="lazyload" data-src="{MAINIMAGE}" alt="{IMAGEALT}" title="{TITLE}" />
        </picture>        
      </a>
    </div>
    ###SLIDERITEM###
  </div>
</div>';
        $new_default_custom_code = '<div class="content_slider cf">
  <div class="slider_home">  
    ###SLIDERITEM###    
    <div class="slider_item">
      <a href="{LINK}" title="{TITLE}" {LINKTARGET}>
        {PICTURESET}
      </a>
    </div>
    ###SLIDERITEM###
  </div>
</div>';
        $chk_custom_code_key = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . xtc_db_input($custom_code_key) . "'");
        if (!xtc_db_num_rows($chk_custom_code_key)) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . xtc_db_input($custom_code_key) . "', '" . xtc_db_input($new_default_custom_code) . "', '6', '5', 'xtc_cfg_textarea(', now())");
        } else {
            $custom_code = xtc_db_fetch_array($chk_custom_code_key);
            $cur = trim(str_replace(array("\r\n", "\r"), "\n", (string)$custom_code['configuration_value']));
            $old = trim(str_replace(array("\r\n", "\r"), "\n", (string)$old_default_custom_code));
            if ($cur === $old) {
                xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . xtc_db_input($new_default_custom_code) . "' WHERE configuration_key = '" . xtc_db_input($custom_code_key) . "'");
            }
        }

        if (!defined($this->name . '_LAZYLOAD')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LAZYLOAD', 'false', 6, 8, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        }

        if (!defined($this->name . '_MOBILEWIDTH')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MOBILEWIDTH', '600', 6, 9, NULL, now())");
        }
        if (!defined($this->name . '_TABLETWIDTH')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TABLETWIDTH', '1023', 6, 10, NULL, now())");
        }

        xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET set_function = 'xtc_cfg_select_option(array(\'Splide tpl_modified_nova\', \'Splide\', \'Slick tpl_modified\', \'Slick\', \'bxSlider tpl_modified\', \'bxSlider\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ' WHERE configuration_key = '" . $this->name . "_TYPE'");

        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) NOT NULL");

        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_mobile_image')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
        }

        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_image_width')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_linktitle` VARCHAR(255) NULL AFTER `imagesliders_title`");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_alt` VARCHAR(255) NULL AFTER `imagesliders_title`");
        }

        if (!$this->columnExists(TABLE_PRODUCTS, 'imagesliders_group')) {
            xtc_db_query('ALTER TABLE ' . TABLE_PRODUCTS . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
        }
        if (!$this->columnExists(TABLE_CATEGORIES, 'imagesliders_group')) {
            xtc_db_query('ALTER TABLE ' . TABLE_CATEGORIES . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
        }
        if (!$this->columnExists(TABLE_CONTENT_MANAGER, 'imagesliders_group')) {
            xtc_db_query('ALTER TABLE ' . TABLE_CONTENT_MANAGER . ' ADD COLUMN imagesliders_group VARCHAR(255) NULL');
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'date_scheduled')) {
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled DATETIME DEFAULT NULL');
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'expires_date')) {
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date DATETIME DEFAULT NULL');
        }
        if (!$this->columnExists(TABLE_ADMIN_ACCESS, 'mits_imageslider_regenerate_variants')) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `mits_imageslider_regenerate_variants` INT(1) NOT NULL DEFAULT '0'");
            xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_regenerate_variants` = 1 WHERE customers_id != 'groups'");
        }

        $this->removeOldFiles();

        $messageStack->add_session(constant($this->name . '_UPDATE_FINISHED'), 'success');
    }


    function custom()
    {
        global $messageStack;

        $this->remove();
        $this->removeModulfiles();

        $messageStack->add_session(constant($this->name . '_DELETE_FINISHED'), 'success');
    }

    function remove()
    {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE '" . $this->name . "_%'");
        xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER);
        xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER_INFO);
        xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `" . $this->code . "`");
        xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " DROP imagesliders_group");
        xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " DROP imagesliders_group");
        xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " DROP imagesliders_group");
    }

    function keys()
    {
        $key = array(
          $this->name . '_STATUS',
          $this->name . '_SHOW',
          $this->name . '_TYPE',
          $this->name . '_CUSTOM_CODE',
          $this->name . '_LOADJAVASCRIPT',
          $this->name . '_LOADCSS',
          $this->name . '_LAZYLOAD',
          $this->name . '_MOBILEWIDTH',
          $this->name . '_TABLETWIDTH',
          $this->name . '_MAX_DISPLAY_RESULTS'
        );

        return $key;
    }

    /**
     * @param string $table
     * @return bool
     */
    private function tableExists(string $table): bool
    {
        $q = xtc_db_query("SHOW TABLES LIKE '" . xtc_db_input($table) . "'");
        return xtc_db_num_rows($q) > 0;
    }

    /**
     * @param $table
     * @param $column
     * @return bool
     */
    private function columnExists($table, $column): bool
    {
        $res = xtc_db_query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
        return xtc_db_num_rows($res) > 0;
    }

    /**
     * @return void
     */
    protected function removeOldFiles(): void
    {
        $old_files_array = array(
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/application_top.php.txt',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/column_left.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/imagesliders.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/german.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/imagesliders.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/english.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'inc/xtc_get_categories_name.inc.php',
          DIR_FS_EXTERNAL . 'mits_imageslider/functions/mits_get_categories_name.inc.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/application_bottom/mits_imageslider.php',
        );

        foreach ($old_files_array as $delete_file) {
            if (is_file($delete_file)) {
                unlink($delete_file);
            }
        }
    }

    /**
     * @param $dir
     * @return bool
     */
    protected function deleteDirectory($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($dir);
    }


    /**
     * @return void
     */
    protected function removeModulfiles(): void
    {
        $remove_files_array = array(
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'mits_imageslider_regenerate_variants.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/application_top/application_top_end/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/filenames/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/footer/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/menu/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/modules/add_db_fields/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/modules/new_category/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/extra/modules/new_product/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . (defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/') . 'includes/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/external/smarty/plugins/function.getImageSlider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/application_bottom/10_mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/database_tables/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/default/categories_content/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/default/categories_smarty/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/functions/MITS_get_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/header/header_body/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/modules/product_info_end/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/modules/product_listing_begin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/shop_content_end/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'includes/extra/wysiwyg/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'templates/tpl_modified_nova/javascript/extra/mits_imageslider.js.php',
        );

        foreach ($remove_files_array as $delete_file) {
            if (is_file($delete_file)) {
                unlink($delete_file);
            }
        }

        $this->deleteDirectory(DIR_FS_DOCUMENT_ROOT . 'includes/external/mits_imageslider');
        $this->deleteDirectory(DIR_FS_DOCUMENT_ROOT . 'images/imagesliders');
    }

}
