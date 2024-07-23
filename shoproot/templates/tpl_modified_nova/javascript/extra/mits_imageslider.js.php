<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.js.php
 * Date: 19.07.2023
 * Time: 18:03
 *
 * Author: Hetfield
 * Copyright: (c) 2023 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') { ?>
<script>
  <?php require_once DIR_FS_EXTERNAL . 'mits_imageslider/plugins/splide/mits_imageslider.js'; ?>
</script>
<?php
}