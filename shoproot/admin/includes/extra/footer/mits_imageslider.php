<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 20.07.2020
 * Time: 11:40
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {
  if (strstr($PHP_SELF, FILENAME_CONTENT_MANAGER)) {
    if (isset($_GET['coID'])  && !empty($_GET['coID'])) {
      $slidergroups_array = array(
        array('id' => '', 'text' => '---'),
      );

      $slidergroups_query = xtc_db_query("SELECT DISTINCT imagesliders_group FROM " . TABLE_MITS_IMAGESLIDER . " ORDER BY imagesliders_group");
      while ($slidergroups = xtc_db_fetch_array($slidergroups_query)) {
        $slidergroups_array[] = array('id' => $slidergroups['imagesliders_group'], 'text' => strtoupper($slidergroups['imagesliders_group']));
      }

      $slidergroup_query = xtc_db_query("SELECT imagesliders_group FROM " . TABLE_CONTENT_MANAGER . " WHERE content_group = " . (int)$_GET['coID']);
      $slidergroup = xtc_db_fetch_array($slidergroup_query)
      ?>
  <table class="tableConfig borderall">
    <tr>
      <td class="dataTableConfig col-left" style="width:260px;"><?php echo TEXT_IMAGESLIDERS_GROUP; ?></td>
      <td class="dataTableConfig col-single-right"><?php echo xtc_draw_pull_down_menu('imagesliders_group', $slidergroups_array, ((isset($slidergroup['imagesliders_group'])) ? $slidergroup['imagesliders_group'] : '')); ?></td>
    </tr>
  </table>
  <script>
    $(document).ready(function() {
      $("[name='imagesliders_group']").closest("tr").detach().insertAfter($("[name='sort_order']").closest("tr"));
    });
  </script>
  <?php
    }
  }
}