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
    public string $code;
    public string $name;
    public string $version;
    public mixed $sort_order;
    public string $title;
    public string $description;
    public bool $enabled;
    public mixed $do_update;
    private bool $_check;

    /**
     *
     */
    public function __construct()
    {
        $this->code = 'mits_imageslider';
        $this->name = 'MODULE_' . strtoupper($this->code);
        $this->version = '2.36';

        $this->sort_order = defined($this->name . '_SORT_ORDER') ? constant($this->name . '_SORT_ORDER') : 0;
        $this->enabled = defined($this->name . '_STATUS') && (constant($this->name . '_STATUS') == 'true');

        if (defined($this->name . '_VERSION') && $this->version != constant($this->name . '_VERSION')) {
            $this->do_update = (defined($this->name . '_UPDATE_AVAILABLE_TITLE')) ? constant($this->name . '_UPDATE_AVAILABLE_TITLE') : '';
        } else {
            $this->do_update = '';
        }

        $this->title = (defined($this->name . '_TITLE') ? constant($this->name . '_TITLE') : $this->code) . ' - v' . $this->version . $this->do_update;
        $this->description = '';
        if ($this->do_update != '') {
            $this->description .= '<a class="button btnbox but_green" style="text-align:center;" onclick="this.blur();" href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code . '&action=update') . '">' . constant($this->name . '_UPDATE_MODUL') . '</a><br>';
        }
        $this->description .= defined($this->name . '_DESCRIPTION') ? constant($this->name . '_DESCRIPTION') . '<hr style="margin:10px 0">' : '';

        if (!$this->enabled) {
            $this->description .= '<div style="text-align:center;margin:30px 0"><a class="button but_red" style="text-align:center;" onclick="return confirmLink(\'' . constant($this->name . '_CONFIRM_DELETE_MODUL') . '\', \'\' ,this);" href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=' . $this->code . '&action=custom') . '">' . constant($this->name . '_DELETE_MODUL') . '</a></div><br>';
        } else {
            $this->description .= '<div style="text-align:center;margin:10px 0"><a class="button but_red" style="text-align:center;" onclick="return confirmLink(\'' . constant($this->name . '_GENERATE_VARIANTS') . '\', \'\' ,this);" href="' . xtc_href_link(FILENAME_MITS_IMAGESLIDER_GENERATE_VARIANTS) . '">' . constant(
                $this->name . '_GENERATE_VARIANTS'
              ) . '</a></div>';
            if (defined('FILENAME_MITS_IMAGESLIDER_IMPORT_BANNERS') && defined($this->name . '_IMPORT_BANNERS')) {
                $this->description .= '<div style="text-align:center;margin:10px 0"><a class="button but_green" style="text-align:center;" onclick="this.blur();" href="' . xtc_href_link(FILENAME_MITS_IMAGESLIDER_IMPORT_BANNERS) . '">' . constant($this->name . '_IMPORT_BANNERS') . '</a></div>';
            }	
						$this->description .= '<br>';
        }

        $mitsUpdateClientFile = DIR_FS_CATALOG . 'includes/external/mits_module_update_client/MitsModuleUpdateClient.php';

        if (is_file($mitsUpdateClientFile)) {
            require_once $mitsUpdateClientFile;
            MitsModuleUpdateClient::integrate($this);
        }
    }

    /**
     * @param $file
     * @return void
     */
    public function process($file): void
    {
    }

    /**
     * @return string[]
     */
    public function display(): array
    {
        $display = '';

        if ($this->enabled) {
            if (defined('FILENAME_MITS_IMAGESLIDER_GENERATE_VARIANTS') && defined($this->name . '_GENERATE_VARIANTS')) {
                $display .= '<p><strong>' . constant($this->name . '_GENERATE_VARIANTS') . '</strong><br><small>';
                $display .= defined($this->name . '_GENERATE_VARIANTS_DESC') ? constant($this->name . '_GENERATE_VARIANTS_DESC') : '';
                $display .= '</small></p>';
                $display .= xtc_button_link(constant($this->name . '_GENERATE_VARIANTS'), xtc_href_link(FILENAME_MITS_IMAGESLIDER_GENERATE_VARIANTS));
            }

            if (defined('FILENAME_MITS_IMAGESLIDER_IMPORT_BANNERS') && defined($this->name . '_IMPORT_BANNERS')) {
                $display .= ($display != '' ? '<br><hr>' : '');
                $display .= '<p><strong>' . constant($this->name . '_IMPORT_BANNERS') . '</strong><br><small>';
                $display .= defined($this->name . '_IMPORT_BANNERS_DESC') ? constant($this->name . '_IMPORT_BANNERS_DESC') : '';
                $display .= '</small></p>';
                $display .= xtc_button_link(constant($this->name . '_IMPORT_BANNERS'), xtc_href_link(FILENAME_MITS_IMAGESLIDER_IMPORT_BANNERS));
            }
        }

        return array(
          'text' => $display . '<br /><div align="center">' . xtc_button(BUTTON_SAVE) .
            xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . '</div>'
        );
    }

    /**
     * @return bool
     */
    public function check()
    {
        if (!isset($this->_check)) {
            if (defined($this->name . '_STATUS')) {
                $this->_check = true;
            } else {
                $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'");
                $this->_check = xtc_db_num_rows($check_query) > 0;
            }
        }
        return $this->_check;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->dbChanges();
    }

    /**
     * @return void
     */
    public function update(): void
    {
        global $messageStack;

        $this->dbChanges();
        $this->removeOldFiles();

        $messageStack->add_session(constant($this->name . '_UPDATE_FINISHED'), 'success');
    }

    /**
     * @return void
     */
    public function custom(): void
    {
        global $messageStack;

        $this->remove();
        $this->removeModulfiles();

        $messageStack->add_session(constant($this->name . '_DELETE_FINISHED'), 'success');
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE '" . $this->name . "_%'");

        if ($this->tableExists(TABLE_MITS_IMAGESLIDER)) {
            xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER);
        }
        if ($this->tableExists(TABLE_MITS_IMAGESLIDER_INFO)) {
            xtc_db_query("DROP TABLE " . TABLE_MITS_IMAGESLIDER_INFO);
        }
        if ($this->tableExists('mits_imageslider_import_map')) {
            xtc_db_query("DROP TABLE mits_imageslider_import_map");
        }

        if ($this->columnExists(TABLE_ADMIN_ACCESS, $this->code)) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `" . $this->code . "`");
        }
        if ($this->columnExists(TABLE_ADMIN_ACCESS, 'mits_imageslider_regenerate_variants')) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `mits_imageslider_regenerate_variants`");
        }
        if ($this->columnExists(TABLE_ADMIN_ACCESS, 'mits_imageslider_import_banners')) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " DROP COLUMN `mits_imageslider_import_banners`");
        }
        if ($this->columnExists(TABLE_CATEGORIES, 'imagesliders_group')) {
            xtc_db_query("ALTER TABLE " . TABLE_CATEGORIES . " DROP imagesliders_group");
        }
        if ($this->columnExists(TABLE_PRODUCTS, 'imagesliders_group')) {
            xtc_db_query("ALTER TABLE " . TABLE_PRODUCTS . " DROP imagesliders_group");
        }
        if ($this->columnExists(TABLE_CONTENT_MANAGER, 'imagesliders_group')) {
            xtc_db_query("ALTER TABLE " . TABLE_CONTENT_MANAGER . " DROP imagesliders_group");
        }
    }

    /**
     * @return string[]
     */
    public function keys(): array
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
          $this->name . '_MAX_DISPLAY_RESULTS',
        );

        return $key;
    }

    /**
     * @return void
     */
    private function dbChanges(): void
    {
        xtc_db_query(
          "CREATE TABLE IF NOT EXISTS " . TABLE_MITS_IMAGESLIDER . " (
            `imagesliders_id` int(11) NOT NULL auto_increment,
            `imagesliders_name` varchar(255) NOT NULL default '',
            `date_scheduled` datetime default NULL,
            `expires_date` datetime default NULL,
            `recurring` tinyint(1) NOT NULL default '0',
            `recurring_start_md` char(5) default NULL,
            `recurring_end_md` char(5) default NULL,
            `date_added` datetime default NULL,
            `last_modified` datetime default NULL,
            `status` tinyint(1) NOT NULL default '0',
            `sorting` int(11) NOT NULL default '0',
            `imagesliders_group` varchar(255) NOT NULL default 'mits_imageslider',
            PRIMARY KEY (`imagesliders_id`),
            KEY `idx_mits_imageslider_group_status_dates` (`imagesliders_group`(128),`status`,`recurring`,`date_scheduled`,`expires_date`)
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
            PRIMARY KEY (`imagesliders_id`,`languages_id`)
          )"
        );

        xtc_db_query(
          "CREATE TABLE IF NOT EXISTS mits_imageslider_import_map (
            `source` VARCHAR(32) NOT NULL,
            `source_id` INT(11) NOT NULL,
            `imagesliders_id` INT(11) NOT NULL,
            `date_imported` DATETIME DEFAULT NULL,
            PRIMARY KEY (`source`, `source_id`),
            KEY `idx_mits_imageslider_import_map_slider` (`imagesliders_id`)
          )"
        );

        $this->ensureImagesliderTableSchema();

        if (!$this->columnExists(TABLE_ADMIN_ACCESS, $this->code)) {
            if ($this->columnExists(TABLE_ADMIN_ACCESS, 'imagesliders')) {
                xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " CHANGE COLUMN `imagesliders` `" . $this->code . "` INT(1) NOT NULL DEFAULT '0'");
            } else {
                xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `" . $this->code . "` INT(1) NOT NULL DEFAULT '0'");
            }
        }
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . $this->code . "` = 1 WHERE customers_id != 'groups'");
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `" . $this->code . "` = 0 WHERE customers_id = 'groups'");

        if (!$this->columnExists(TABLE_ADMIN_ACCESS, 'mits_imageslider_regenerate_variants')) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `mits_imageslider_regenerate_variants` INT(1) NOT NULL DEFAULT '0'");
        }
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_regenerate_variants` = 1 WHERE customers_id != 'groups'");
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_regenerate_variants` = 0 WHERE customers_id = 'groups'");

        if (!$this->columnExists(TABLE_ADMIN_ACCESS, 'mits_imageslider_import_banners')) {
            xtc_db_query("ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD `mits_imageslider_import_banners` INT(1) NOT NULL DEFAULT '0'");
        }
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_import_banners` = 1 WHERE customers_id != 'groups'");
        xtc_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET `mits_imageslider_import_banners` = 0 WHERE customers_id = 'groups'");

        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_name` `imagesliders_name` VARCHAR(255) NOT NULL DEFAULT ''");
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_title` `imagesliders_title` VARCHAR(255) NOT NULL");
        xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_image` `imagesliders_image` VARCHAR(255) DEFAULT NULL");

        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'imagesliders_group')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `imagesliders_group` VARCHAR(255) NOT NULL DEFAULT 'mits_imageslider'");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'date_scheduled')) {
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN date_scheduled DATETIME DEFAULT NULL');
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'expires_date')) {
            xtc_db_query('ALTER TABLE ' . TABLE_MITS_IMAGESLIDER . ' ADD COLUMN expires_date DATETIME DEFAULT NULL');
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'recurring')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `recurring` TINYINT(1) NOT NULL DEFAULT '0' AFTER `expires_date`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'recurring_start_md')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `recurring_start_md` CHAR(5) DEFAULT NULL AFTER `recurring`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER, 'recurring_end_md')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD COLUMN `recurring_end_md` CHAR(5) DEFAULT NULL AFTER `recurring_start_md`");
        }
        if (!$this->indexExists(TABLE_MITS_IMAGESLIDER, 'idx_mits_imageslider_group_status_dates')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD INDEX `idx_mits_imageslider_group_status_dates` (`imagesliders_group`(128),`status`,`recurring`,`date_scheduled`,`expires_date`)");
        }

        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_mobile_image')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_tablet_image')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image` VARCHAR(255) NULL AFTER `imagesliders_image`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_image_width')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_image_height')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_image_width`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_tablet_image_width')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_tablet_image_height')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_tablet_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_tablet_image_width`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_mobile_image_width')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_width` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_mobile_image_height')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_mobile_image_height` FLOAT NOT NULL DEFAULT '0' AFTER `imagesliders_mobile_image_width`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_linktitle')) {
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD COLUMN `imagesliders_linktitle` VARCHAR(255) NULL AFTER `imagesliders_title`");
        }
        if (!$this->columnExists(TABLE_MITS_IMAGESLIDER_INFO, 'imagesliders_alt')) {
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

        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MAX_DISPLAY_IMAGESLIDERS_RESULTS'");
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_MITS_IMAGESLIDER_RESULTS'");

        if (!defined($this->name . '_STATUS')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_STATUS', 'true', 6, 1, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        }

        if (!defined($this->name . '_SHOW')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_SHOW', 'general', 6, 2, 'xtc_cfg_select_option(array(\'start\', \'general\'), ', now())");
        }

        if (!defined($this->name . '_TYPE')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TYPE', 'Splide tpl_modified_nova', 6, 4, 'xtc_cfg_select_option(array(\'Splide tpl_modified_nova\', \'Splide\', \'Slick tpl_modified\', \'Slick\', \'bxSlider tpl_modified\', \'bxSlider\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ', now())");
        }

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

        if (!defined($this->name . '_CUSTOM_CODE')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_CUSTOM_CODE', '" . xtc_db_input($new_default_custom_code) . "', 6, 5, 'xtc_cfg_textarea(', now())");
        } else {
            $custom_code_key = $this->name . '_CUSTOM_CODE';
            $custom_code_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . xtc_db_input($custom_code_key) . "'");
            if (xtc_db_num_rows($custom_code_query)) {
                $custom_code = xtc_db_fetch_array($custom_code_query);
                $cur = trim(str_replace(array("\r\n", "\r"), "\n", (string)$custom_code['configuration_value']));
                $old = trim(str_replace(array("\r\n", "\r"), "\n", $old_default_custom_code));
                if ($cur === $old) {
                    xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . xtc_db_input($new_default_custom_code) . "' WHERE configuration_key = '" . xtc_db_input($custom_code_key) . "'");
                }
            }
        }

        if (!defined($this->name . '_LOADJAVASCRIPT')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADJAVASCRIPT', 'false', 6, 6, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        }

        if (!defined($this->name . '_LOADCSS')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LOADCSS', 'false', 6, 7, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        }

        if (!defined($this->name . '_LAZYLOAD')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_LAZYLOAD', 'false', 6, 8, 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        }

        if (!defined($this->name . '_MOBILEWIDTH')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MOBILEWIDTH', '600', 6, 9, NULL, now())");
        }

        if (!defined($this->name . '_TABLETWIDTH')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_TABLETWIDTH', '1023', 6, 10, NULL, now())");
        }

        if (!defined($this->name . '_MAX_DISPLAY_RESULTS')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_MAX_DISPLAY_RESULTS', '20', 6, 20, NULL, now())");
        }

        xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET set_function = 'xtc_cfg_select_option(array(\'Splide tpl_modified_nova\', \'Splide\', \'Slick tpl_modified\', \'Slick\', \'bxSlider tpl_modified\', \'bxSlider\', \'NivoSlider\', \'FlexSlider\', \'jQuery.innerfade\', \'custom\'), ' WHERE configuration_key = '" . $this->name . "_TYPE'");

        if (!defined($this->name . '_VERSION')) {
            xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_VERSION', '" . $this->version . "', 6, 99, NULL, now())");
        } else {
            xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $this->version . "' WHERE configuration_key = '" . $this->name . "_VERSION'");
        }
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
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function columnExists(string $table, string $column): bool
    {
        $res = xtc_db_query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
        return xtc_db_num_rows($res) > 0;
    }

    /**
     * @param string $table
     * @param string $index
     * @return bool
     */
    private function indexExists(string $table, string $index): bool
    {
        $res = xtc_db_query("SHOW INDEX FROM {$table} WHERE Key_name = '" . xtc_db_input($index) . "'");
        return xtc_db_num_rows($res) > 0;
    }

    /**
     * @param string $table
     * @return bool
     */
    private function primaryKeyExists(string $table): bool
    {
        $res = xtc_db_query("SHOW KEYS FROM " . $table . " WHERE Key_name = 'PRIMARY'");
        return xtc_db_num_rows($res) > 0;
    }

    /**
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function columnHasAutoIncrement(string $table, string $column): bool
    {
        $res = xtc_db_query("SHOW COLUMNS FROM " . $table . " LIKE '" . xtc_db_input($column) . "'");
        if (xtc_db_num_rows($res) < 1) {
            return false;
        }

        $row = xtc_db_fetch_array($res);
        return (isset($row['Extra']) && stripos($row['Extra'], 'auto_increment') !== false);
    }

    /**
     * @return void
     */
    private function ensureImagesliderTableSchema(): void
    {
        $this->repairImagesliderZeroIds();

        if ($this->tableExists(TABLE_MITS_IMAGESLIDER)) {
            if (!$this->primaryKeyExists(TABLE_MITS_IMAGESLIDER) && $this->countRows(TABLE_MITS_IMAGESLIDER, "`imagesliders_id` = 0") <= 1) {
                xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " ADD PRIMARY KEY (`imagesliders_id`)");
            }

            if (!$this->columnHasAutoIncrement(TABLE_MITS_IMAGESLIDER, 'imagesliders_id') && $this->countRows(TABLE_MITS_IMAGESLIDER, "`imagesliders_id` = 0") <= 1) {
                xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `imagesliders_id` `imagesliders_id` INT(11) NOT NULL AUTO_INCREMENT");
            }

            if ($this->columnExists(TABLE_MITS_IMAGESLIDER, 'sorting')) {
                xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER . " CHANGE `sorting` `sorting` INT(11) NOT NULL DEFAULT '0'");
            }
        }

        if ($this->tableExists(TABLE_MITS_IMAGESLIDER_INFO)) {
            if (!$this->primaryKeyExists(TABLE_MITS_IMAGESLIDER_INFO)) {
                xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " ADD PRIMARY KEY (`imagesliders_id`, `languages_id`)");
            }

            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `imagesliders_id` `imagesliders_id` INT(11) NOT NULL");
            xtc_db_query("ALTER TABLE " . TABLE_MITS_IMAGESLIDER_INFO . " CHANGE `languages_id` `languages_id` INT(11) NOT NULL");
        }
    }

    /**
     * @param string $table
     * @param string $where
     * @return int
     */
    private function countRows(string $table, string $where = '1'): int
    {
        $res = xtc_db_query("SELECT COUNT(*) AS total FROM " . $table . " WHERE " . $where);
        $row = xtc_db_fetch_array($res);
        return (int)($row['total'] ?? 0);
    }

    /**
     * @return int
     */
    private function getMaxImagesliderId(): int
    {
        $res = xtc_db_query("SELECT MAX(`imagesliders_id`) AS max_id FROM " . TABLE_MITS_IMAGESLIDER);
        $row = xtc_db_fetch_array($res);
        return (int)($row['max_id'] ?? 0);
    }

    /**
     * @return int
     */
    private function getSingleImagesliderIdWithoutInfo(): int
    {
        if (!$this->tableExists(TABLE_MITS_IMAGESLIDER) || !$this->tableExists(TABLE_MITS_IMAGESLIDER_INFO)) {
            return 0;
        }

        $ids = array();
        $res = xtc_db_query(
          "SELECT m.`imagesliders_id`
" .
          "  FROM " . TABLE_MITS_IMAGESLIDER . " m
" .
          "  LEFT JOIN " . TABLE_MITS_IMAGESLIDER_INFO . " i
" .
          "    ON i.`imagesliders_id` = m.`imagesliders_id`
" .
          " WHERE i.`imagesliders_id` IS NULL
" .
          " GROUP BY m.`imagesliders_id`
" .
          " ORDER BY m.`imagesliders_id` ASC"
        );

        while ($row = xtc_db_fetch_array($res)) {
            $ids[] = (int)$row['imagesliders_id'];
            if (count($ids) > 1) {
                return 0;
            }
        }

        return count($ids) === 1 ? $ids[0] : 0;
    }

    /**
     * @return void
     */
    private function repairImagesliderZeroIds(): void
    {
        if (!$this->tableExists(TABLE_MITS_IMAGESLIDER)) {
            return;
        }

        $zero_main_count = $this->countRows(TABLE_MITS_IMAGESLIDER, "`imagesliders_id` = 0");

        if ($zero_main_count === 1) {
            $new_id = $this->getMaxImagesliderId() + 1;

            xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER . " SET `imagesliders_id` = " . (int)$new_id . " WHERE `imagesliders_id` = 0 LIMIT 1");

            if ($this->tableExists(TABLE_MITS_IMAGESLIDER_INFO)) {
                xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER_INFO . " SET `imagesliders_id` = " . (int)$new_id . " WHERE `imagesliders_id` = 0");
            }

            if ($this->tableExists('mits_imageslider_import_map')) {
                xtc_db_query("UPDATE mits_imageslider_import_map SET `imagesliders_id` = " . (int)$new_id . " WHERE `imagesliders_id` = 0");
            }
        }

        if (!$this->tableExists(TABLE_MITS_IMAGESLIDER_INFO) || $this->countRows(TABLE_MITS_IMAGESLIDER_INFO, "`imagesliders_id` = 0") < 1) {
            return;
        }

        $target_id = $this->getSingleImagesliderIdWithoutInfo();

        if ($target_id < 1 && $this->countRows(TABLE_MITS_IMAGESLIDER) === 1) {
            $res = xtc_db_query("SELECT `imagesliders_id` FROM " . TABLE_MITS_IMAGESLIDER . " LIMIT 1");
            $row = xtc_db_fetch_array($res);
            $possible_id = (int)($row['imagesliders_id'] ?? 0);

            if ($possible_id > 0 && $this->countRows(TABLE_MITS_IMAGESLIDER_INFO, "`imagesliders_id` = " . (int)$possible_id) < 1) {
                $target_id = $possible_id;
            }
        }

        if ($target_id > 0) {
            xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER_INFO . " SET `imagesliders_id` = " . (int)$target_id . " WHERE `imagesliders_id` = 0");

            if ($this->tableExists('mits_imageslider_import_map')) {
                xtc_db_query("UPDATE mits_imageslider_import_map SET `imagesliders_id` = " . (int)$target_id . " WHERE `imagesliders_id` = 0");
            }
        }
    }

    /**
     * @return void
     */
    protected function removeOldFiles(): void
    {
        $admin_dir = defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/';
        $old_files_array = array(
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/application_top.php.txt',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/column_left.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/imagesliders.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/german/admin/german.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/imagesliders.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/english/admin/english.php.txt',
          DIR_FS_DOCUMENT_ROOT . 'inc/xtc_get_categories_name.inc.php',
          DIR_FS_EXTERNAL . 'mits_imageslider/functions/mits_get_categories_name.inc.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/application_bottom/mits_imageslider.php',
        );

        if (count($old_files_array) > 0) {
            foreach ($old_files_array as $delete_file) {
                if (is_file($delete_file)) {
                    unlink($delete_file);
                }
            }
        }
    }

    /**
     * @param $dir
     * @return void
     */
    protected function deleteDirectory($dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        if (!is_dir($dir)) {
            unlink($dir);
            return;
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

        rmdir($dir);
    }

    /**
     * @return void
     */
    protected function removeModulfiles(): void
    {
        $admin_dir = defined('DIR_ADMIN') ? DIR_ADMIN : 'admin/';

        $remove_files_array = array(
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'mits_imageslider_regenerate_variants.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'mits_imageslider_import_banners.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/application_top/application_top_end/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/filenames/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/footer/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/menu/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/modules/add_db_fields/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/modules/new_category/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/extra/modules/new_product/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . $admin_dir . 'includes/modules/system/mits_imageslider.php',
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
          DIR_FS_DOCUMENT_ROOT . 'lang/french/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/french/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/french/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/italian/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/italian/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/italian/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/spanish/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/spanish/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/spanish/modules/system/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/dutch/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/dutch/extra/admin/mits_imageslider.php',
          DIR_FS_DOCUMENT_ROOT . 'lang/dutch/modules/system/mits_imageslider.php',
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
