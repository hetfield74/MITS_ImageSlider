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
      'TEXT_IMAGESLIDERS_GROUP' => 'MITS ImageSlider-Group:
        <span class="tooltip"><img src="images/icons/tooltip_icon.png"  style="border:0;">
          <em>Here you can assign an existing MITS ImageSlider group, which is displayed in the frontend. The prerequisite is that the corresponding template files have been added.</em>
        </span>',

      'MITS_IMAGESLIDER_VARIANTS_HEADING'          => 'MITS ImageSlider – Regenerate image variants',
      'MITS_IMAGESLIDER_VARIANTS_INTRO'            => 'This admin tool generates additional image variants for existing ImageSlider images to improve performance (Pagespeed), especially:',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_WEBP'     => 'WebP versions (if the server supports WebP via GD)',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_FALLBACK' => 'Fallback files (JPG/PNG) in a consistent form',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_SRCSET'   => 'Multiple resolutions (srcset derivatives) in a subfolder srcset/',
      'MITS_IMAGESLIDER_VARIANTS_FEATURE_DB'       => 'Optional: update database path fields and Width/Height (clean dimensions / less CLS)',
      'MITS_IMAGESLIDER_VARIANTS_PURPOSE'          => 'The tool is intended to optimize existing slider images afterwards without having to upload them again.',
      'MITS_IMAGESLIDER_VARIANTS_DOC_LINK_TEXT'    => 'Manual and further information for this tool',
      'MITS_IMAGESLIDER_VARIANTS_DOC_WINDOW_TITLE' => 'Manual and further information for this tool',

      'MITS_IMAGESLIDER_VARIANTS_TOTAL'   => 'Total',
      'MITS_IMAGESLIDER_VARIANTS_RECORDS' => 'records',
      'MITS_IMAGESLIDER_VARIANTS_BATCH'   => 'Batch',
      'MITS_IMAGESLIDER_VARIANTS_MODE'    => 'Mode',

      'MITS_IMAGESLIDER_VARIANTS_BTN_SCAN'    => 'Scan (check only)',
      'MITS_IMAGESLIDER_VARIANTS_BTN_EXECUTE' => 'Execute (generate variants)',
      'MITS_IMAGESLIDER_VARIANTS_NOTE_LABEL'  => 'Note',
      'MITS_IMAGESLIDER_VARIANTS_NOTE'        => 'In <code>execute</code> mode, files are generated/overwritten. DB updates happen only after confirmation.',

      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_MODE'           => 'Execute mode',
      'MITS_IMAGESLIDER_VARIANTS_PLEASE_CONFIRM'         => 'Please confirm to allow DB updates.',
      'MITS_IMAGESLIDER_VARIANTS_BTN_CONFIRM_DB'         => 'I have a backup and want to run DB updates',
      'MITS_IMAGESLIDER_VARIANTS_NO_CONFIRM_NOTE'        => 'Without confirmation, variants are generated (if the helper writes files), but DB paths/dimensions are not updated.',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED'      => 'Execute confirmed',
      'MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED_TEXT' => 'DB paths and width/height will be updated if something changes.',

      'MITS_IMAGESLIDER_VARIANTS_BATCH_RESULT' => 'Batch result',
      'MITS_IMAGESLIDER_VARIANTS_PROCESSED'    => 'processed',
      'MITS_IMAGESLIDER_VARIANTS_DB_UPDATES'   => 'DB updates',
      'MITS_IMAGESLIDER_VARIANTS_SKIPPED'      => 'skipped',
      'MITS_IMAGESLIDER_VARIANTS_NO_ACTIONS'   => 'No actions.',
      'MITS_IMAGESLIDER_VARIANTS_NEXT_BATCH'   => 'Next batch',
      'MITS_IMAGESLIDER_VARIANTS_DONE'         => 'Done.',

      'MITS_IMAGESLIDER_VARIANTS_YES' => '<span class="ok">yes</span>',
      'MITS_IMAGESLIDER_VARIANTS_NO'  => '<span class="warn">no</span>',

      'MITS_IMAGESLIDER_VARIANTS_ERR_MODULE_DISABLED' => 'Module is not enabled.',
      'MITS_IMAGESLIDER_VARIANTS_ERR_HELPER_MISSING'  => 'Variant helper not found. Expected:',

      'MITS_IMAGESLIDER_VARIANTS_LOG_MISSING'      => '%s: missing %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SKIP_EXT'     => '%s: skip (ext) %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_SCAN'         => '%s: %s | webp=%s | srcset_dir=%s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_GENERATED'    => '%s: generated variants for %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_PATH_UPDATED' => '%s: path updated %s -> %s',
      'MITS_IMAGESLIDER_VARIANTS_LOG_DIMS'         => '%s: dims %dx%d -> %dx%d'
    );

    foreach ($lang_array as $key => $val) {
        defined($key) || define($key, $val);
    }
}
