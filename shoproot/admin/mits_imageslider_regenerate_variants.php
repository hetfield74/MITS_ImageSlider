<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider_regenerate_variants.php
 * Date: 16.02.2026
 * Time: 11:47
 *
 * Author: Hetfield
 * Copyright: (c) 2026 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

require_once('includes/application_top.php');

if (!defined('MODULE_MITS_IMAGESLIDER_STATUS') || MODULE_MITS_IMAGESLIDER_STATUS !== 'true') {
    echo '<h1>' . MITS_BOX_IMAGESLIDER . '</h1><p>' . MITS_IMAGESLIDER_VARIANTS_ERR_MODULE_DISABLED . '</p>';
    require_once(DIR_WS_INCLUDES . 'application_bottom.php');
    exit;
}

$helperLoaded = false;
if (defined('DIR_FS_EXTERNAL') && is_file(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php')) {
    require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/images.php');
    $helperLoaded = true;
}

if (!$helperLoaded || !function_exists('mits_imageslider_generate_variants_from_relative')) {
    echo '<h1>' . MITS_BOX_IMAGESLIDER . '</h1><p style="color:red">' . sprintf(MITS_IMAGESLIDER_VARIANTS_ERR_HELPER_MISSING, '<code>' . (defined('DIR_FS_EXTERNAL') ? DIR_FS_EXTERNAL : 'DIR_FS_EXTERNAL') . 'mits_imageslider/functions/images.php</code>') . '</p>';
    require_once(DIR_WS_INCLUDES . 'application_bottom.php');
    exit;
}

function mits_is_processable_ext($relPath): bool
{
    $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
    return in_array($ext, array('jpg', 'jpeg', 'jpe', 'png', 'gif', 'webp'), true);
}

function mits_safe_img_dims($relPath): array
{
    if (!defined('DIR_FS_CATALOG_IMAGES')) {
        return array(0, 0);
    }
    $abs = DIR_FS_CATALOG_IMAGES . ltrim($relPath, '/');
    if (!is_file($abs)) {
        return array(0, 0);
    }
    $gi = @getimagesize($abs);
    if (!is_array($gi)) {
        return array(0, 0);
    }
    return array((int)$gi[0], (int)$gi[1]);
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'scan'; // scan|execute
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 25;
$offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

$do_execute = ($mode === 'execute');
$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
$can_execute = ($do_execute && $confirm === 'YES');

$total_q = xtc_db_query("SELECT COUNT(*) AS cnt FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE (imagesliders_image != '' OR imagesliders_tablet_image != '' OR imagesliders_mobile_image != '')");
$total_row = xtc_db_fetch_array($total_q);
$total = (int)($total_row['cnt'] ?? 0);

$batch_q = xtc_db_query(
  "SELECT imagesliders_id, languages_id,
          imagesliders_image, imagesliders_image_width, imagesliders_image_height,
          imagesliders_tablet_image, imagesliders_tablet_image_width, imagesliders_tablet_image_height,
          imagesliders_mobile_image, imagesliders_mobile_image_width, imagesliders_mobile_image_height
   FROM " . TABLE_MITS_IMAGESLIDER_INFO . "
   WHERE (imagesliders_image != '' OR imagesliders_tablet_image != '' OR imagesliders_mobile_image != '')
   ORDER BY imagesliders_id ASC, languages_id ASC
   LIMIT " . (int)$limit . " OFFSET " . (int)$offset
);

$processed = 0;
$updated = 0;
$skipped = 0;
$messages = array();

while ($row = xtc_db_fetch_array($batch_q)) {
    $processed++;
    $id = (int)$row['imagesliders_id'];
    $lang = (int)$row['languages_id'];

    $updates = array();
    $log = array();
    $changed = false;

    $variants = array(
      array('field' => 'imagesliders_image', 'w' => 'imagesliders_image_width', 'h' => 'imagesliders_image_height', 'profile' => 'desktop'),
      array('field' => 'imagesliders_tablet_image', 'w' => 'imagesliders_tablet_image_width', 'h' => 'imagesliders_tablet_image_height', 'profile' => 'tablet'),
      array('field' => 'imagesliders_mobile_image', 'w' => 'imagesliders_mobile_image_width', 'h' => 'imagesliders_mobile_image_height', 'profile' => 'mobile'),
    );

    foreach ($variants as $v) {
        $field = $v['field'];
        $wField = $v['w'];
        $hField = $v['h'];
        $profile = $v['profile'];

        $rel = trim((string)($row[$field] ?? ''));
        if ($rel === '') {
            continue;
        }

        $rel_norm = ltrim($rel, '/');

        if (!mits_is_processable_ext($rel_norm)) {
            $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_SKIP_EXT, $field, htmlspecialchars($rel));
            continue;
        }

        if (!defined('DIR_FS_CATALOG_IMAGES') || !is_file(DIR_FS_CATALOG_IMAGES . $rel_norm)) {
            $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_MISSING, $field, htmlspecialchars($rel));
            continue;
        }

        if ($mode === 'scan') {
            $base = pathinfo($rel_norm, PATHINFO_FILENAME);
            $dir = dirname($rel_norm);
            $dir = ($dir === '.' ? '' : $dir);

            $webp = ($dir !== '' ? $dir . '/' : '') . $base . '.webp';
            $srcset_dir = ($dir !== '' ? $dir . '/' : '') . 'srcset';

            $has_webp = is_file(DIR_FS_CATALOG_IMAGES . $webp);
            $has_srcset = is_dir(DIR_FS_CATALOG_IMAGES . $srcset_dir);

            $log[] = sprintf(
              MITS_IMAGESLIDER_VARIANTS_LOG_SCAN,
              $field,
              htmlspecialchars($rel),
              ($has_webp ? MITS_IMAGESLIDER_VARIANTS_YES : MITS_IMAGESLIDER_VARIANTS_NO),
              ($has_srcset ? MITS_IMAGESLIDER_VARIANTS_YES : MITS_IMAGESLIDER_VARIANTS_NO)
            );
            continue;
        }

        if ($mode === 'execute' && !$can_execute) {
            $ext = strtolower(pathinfo($rel_norm, PATHINFO_EXTENSION));
            if (in_array($ext, array('jpeg','jpe','gif','webp'), true)) {
                $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_SKIP_EXT, $field, htmlspecialchars($rel) . ' (confirm required)');
                continue;
            }
        }

        $new_rel = $rel;

        if ($mode === 'execute') {
            if (function_exists('mits_imageslider_generate_variants_from_relative')) {
                $maybe_new = mits_imageslider_generate_variants_from_relative($rel, $profile);
                if (is_string($maybe_new) && $maybe_new !== '') {
                    $new_rel = $maybe_new;
                }
            }

            $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_GENERATED, $field, htmlspecialchars($new_rel));
        }

        if ($new_rel !== $rel) {
            $updates[$field] = $new_rel;
            $changed = true;
            $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_PATH_UPDATED, $field, htmlspecialchars($rel), htmlspecialchars($new_rel));
        }

        list($nw, $nh) = mits_safe_img_dims($new_rel);
        if ($nw > 0 && $nh > 0) {
            $oldw = (int)($row[$wField] ?? 0);
            $oldh = (int)($row[$hField] ?? 0);

            if ($oldw !== $nw || $oldh !== $nh) {
                $updates[$wField] = $nw;
                $updates[$hField] = $nh;
                $changed = true;
                $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_DIMS, $field, $oldw, $oldh, $nw, $nh);
            }
        }
    }

    if ($mode === 'execute' && $can_execute && count($updates) > 0) {
        xtc_db_perform(
          TABLE_MITS_IMAGESLIDER_INFO,
          $updates,
          'update',
          "imagesliders_id = " . (int)$id . " AND languages_id = " . (int)$lang
        );
        $updated++;
    }

    if (count($log) === 0) {
        $skipped++;
    }

    $messages[] = array('id' => $id, 'lang' => $lang, 'log' => $log);
}

$nextOffset = $offset + $limit;
$hasNext = ($nextOffset < $total);

require_once(DIR_WS_INCLUDES . 'head.php');
?>
<script type="text/javascript" src="includes/general.js"></script>
<style>
  code {
    background: #f3f3f3;
    padding: 2px 4px;
    border-radius: 4px
  }

  .box {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 14px;
    margin: 12px 0
  }

  .ok {
    color: #0a7a0a
  }

  .warn {
    color: #a15b00
  }

  .err {
    color: #b00020
  }

  .grid {
    display: grid;
    grid-template-columns:1fr 1fr;
    gap: 10px
  }

  a.btn, .btn {
    display: inline-block;
    background: #0b5fff;
    color: #fff;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
  }

  a.btn2, .btn2 {
    display: inline-block;
    background: #6a9;
    color: #fff;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
  }

  a.btn3, .btn3 {
    display: inline-block;
    background: #fff;
    color: #0b5fff;
    border: 1px solid #0b5fff;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
  }

  .small {
    font-size: 12px;
    color: #444
  }

  ul {
    margin: 8px 0 0 18px
  }
</style></head>
<body>
<!-- header //-->
<?php
require_once(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table class="tableBody">
  <tr>
      <?php
      //left_navigation
      if (USE_ADMIN_TOP_MENU == 'false') {
          echo '<td class="columnLeft2">' . PHP_EOL;
          echo '<!-- left_navigation //-->' . PHP_EOL;
          require_once(DIR_WS_INCLUDES . 'column_left.php');
          echo '<!-- left_navigation eof //-->' . PHP_EOL;
          echo '</td>' . PHP_EOL;
      }
      $css_class = 'boxCenter';
      ?>
    <!-- body_text //-->
    <td class="<?php
    echo $css_class; ?>" width="100%" valign="top">
      <h1><?php
          echo MITS_IMAGESLIDER_VARIANTS_HEADING; ?></h1>

      <div class="box">
        <div class="grid">
          <div>
            <p>
                <?php
                echo MITS_IMAGESLIDER_VARIANTS_INTRO; ?>
            </p>
            <ul>
              <li><?php
                  echo MITS_IMAGESLIDER_VARIANTS_FEATURE_WEBP; ?></li>
              <li><?php
                  echo MITS_IMAGESLIDER_VARIANTS_FEATURE_FALLBACK; ?></li>
              <li><?php
                  echo MITS_IMAGESLIDER_VARIANTS_FEATURE_SRCSET; ?></li>
              <li><?php
                  echo MITS_IMAGESLIDER_VARIANTS_FEATURE_DB; ?></li>
            </ul>
            <p>
                <?php
                echo MITS_IMAGESLIDER_VARIANTS_PURPOSE; ?>
            </p>
            <div style="text-align:center;margin:20px 0;">
              <a href="https://imageslider.merz-it-service.de/readme_mits_imageslider_regenerate_variants.html" target="_blank" onclick="window.open('https://imageslider.merz-it-service.de/readme_mits_imageslider_regenerate_variants.html', '<?php
              echo addslashes(MITS_IMAGESLIDER_VARIANTS_DOC_WINDOW_TITLE); ?>', 'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600'); return false">
                <strong><u><?php
                        echo MITS_IMAGESLIDER_VARIANTS_DOC_LINK_TEXT; ?></u></strong>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="box">
        <div class="grid">
          <div>
            <div><strong><?php
                    echo MITS_IMAGESLIDER_VARIANTS_TOTAL; ?></strong> <?php
                echo (int)$total; ?> <?php
                echo MITS_IMAGESLIDER_VARIANTS_RECORDS; ?></div>
            <div><strong><?php
                    echo MITS_IMAGESLIDER_VARIANTS_BATCH; ?></strong> offset=<?php
                echo (int)$offset; ?>, limit=<?php
                echo (int)$limit; ?></div>
            <div class="small"><?php
                echo MITS_IMAGESLIDER_VARIANTS_MODE; ?> <code><?php
                    echo htmlspecialchars($mode); ?></code></div>
          </div>
          <div>
            <a class="btn3" href="<?php
            echo htmlspecialchars(basename(__FILE__)); ?>?mode=scan&limit=<?php
            echo (int)$limit; ?>&offset=<?php
            echo (int)$offset; ?>"><?php
                echo MITS_IMAGESLIDER_VARIANTS_BTN_SCAN; ?></a>
            <a class="btn2" href="<?php
            echo htmlspecialchars(basename(__FILE__)); ?>?mode=execute&limit=<?php
            echo (int)$limit; ?>&offset=<?php
            echo (int)$offset; ?>"><?php
                echo MITS_IMAGESLIDER_VARIANTS_BTN_EXECUTE; ?></a>
          </div>
        </div>
        <p class="small">
          <strong><?php
              echo MITS_IMAGESLIDER_VARIANTS_NOTE_LABEL; ?></strong>
            <?php
            echo MITS_IMAGESLIDER_VARIANTS_NOTE; ?>
        </p>
      </div>

        <?php
        if ($mode === 'execute' && !$can_execute): ?>
          <div class="box warn">
            <strong><?php
                echo MITS_IMAGESLIDER_VARIANTS_EXECUTE_MODE; ?></strong>
              <?php
              echo MITS_IMAGESLIDER_VARIANTS_PLEASE_CONFIRM; 
							echo xtc_draw_form('imagesliders', basename(__FILE__), '', 'post');
							?>
            <form method="post" style="margin-top:10px">
              <input type="hidden" name="confirm" value="YES">
              <button class="btn" type="submit"><?php
                  echo MITS_IMAGESLIDER_VARIANTS_BTN_CONFIRM_DB; ?></button>
            </form>
            <p class="small"><?php
                echo MITS_IMAGESLIDER_VARIANTS_NO_CONFIRM_NOTE; ?></p>
          </div>
        <?php
        elseif ($mode === 'execute' && $can_execute): ?>
          <div class="box ok">
            <strong><?php
                echo MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED; ?></strong>
              <?php
              echo MITS_IMAGESLIDER_VARIANTS_EXECUTE_CONFIRMED_TEXT; ?>
          </div>
        <?php
        endif; ?>

      <div class="box">
        <div>
          <strong><?php
              echo MITS_IMAGESLIDER_VARIANTS_BATCH_RESULT; ?></strong>
            <?php
            echo MITS_IMAGESLIDER_VARIANTS_PROCESSED; ?>=<?php
            echo (int)$processed; ?>,
            <?php
            echo MITS_IMAGESLIDER_VARIANTS_DB_UPDATES; ?>=<?php
            echo (int)$updated; ?>,
            <?php
            echo MITS_IMAGESLIDER_VARIANTS_SKIPPED; ?>=<?php
            echo (int)$skipped; ?>
        </div>
      </div>

        <?php
        foreach ($messages as $m): ?>
          <div class="box">
            <div><strong>ID:</strong> <?php
                echo (int)$m['id']; ?> <strong>Lang:</strong> <?php
                echo (int)$m['lang']; ?></div>
              <?php
              if (count($m['log']) > 0): ?>
                <ul>
                    <?php
                    foreach ($m['log'] as $line): ?>
                      <li><?php
                          echo $line; ?></li>
                    <?php
                    endforeach; ?>
                </ul>
              <?php
              else: ?>
                <div class="small"><?php
                    echo MITS_IMAGESLIDER_VARIANTS_NO_ACTIONS; ?></div>
              <?php
              endif; ?>
          </div>
        <?php
        endforeach; ?>

      <div class="box">
          <?php
          if ($hasNext): ?>
            <a class="btn" href="<?php
            echo htmlspecialchars(basename(__FILE__)); ?>?mode=<?php
            echo htmlspecialchars($mode); ?>&limit=<?php
            echo (int)$limit; ?>&offset=<?php
            echo (int)$nextOffset; ?>">
                <?php
                echo MITS_IMAGESLIDER_VARIANTS_NEXT_BATCH; ?> (offset <?php
                echo (int)$nextOffset; ?>)
            </a>
          <?php
          else: ?>
            <strong class="ok"><?php
                echo MITS_IMAGESLIDER_VARIANTS_DONE; ?></strong>
          <?php
          endif; ?>
      </div>

    </td>
  </tr>
</table>

<!-- footer //-->
<?php
require_once(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //--><br />
</body></html>
<?php
require_once(DIR_WS_INCLUDES . 'application_bottom.php'); ?>