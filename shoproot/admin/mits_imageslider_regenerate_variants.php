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

@set_time_limit(120);

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

function mits_imageslider_variants_t($constant, $fallback)
{
    return defined($constant) ? constant($constant) : $fallback;
}

function mits_imageslider_variants_format_datetime($datetime): string
{
    $timestamp = strtotime((string)$datetime);
    if ($timestamp === false) {
        return (string)$datetime;
    }

    return date('d.m.Y H:i:s', $timestamp);
}

function mits_imageslider_variants_session_key(): string
{
    return 'mits_imageslider_regenerate_variants_run';
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

function mits_imageslider_variant_label($existsBefore, $existsAfter): string
{
    if ($existsAfter && !$existsBefore) {
        return '<span class="ok">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_CREATED', 'neu erzeugt') . '</span>';
    }
    if ($existsAfter && $existsBefore) {
        return '<span class="ok">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_UPDATED', 'vorhanden/aktualisiert') . '</span>';
    }
    return '<span class="warn">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_MISSING', 'fehlt') . '</span>';
}

function mits_imageslider_variant_file_exists($relPath): bool
{
    return defined('DIR_FS_CATALOG_IMAGES') && $relPath !== '' && is_file(DIR_FS_CATALOG_IMAGES . ltrim($relPath, '/'));
}

function mits_imageslider_variant_state($relPath, $profile): array
{
    $relPath = ltrim((string)$relPath, '/');
    $profiles = function_exists('mits_imageslider_profiles') ? mits_imageslider_profiles() : array();
    if (!isset($profiles[$profile])) {
        $profile = 'desktop';
    }
    $cfg = isset($profiles[$profile]) ? $profiles[$profile] : array(
      'max_base_width' => 0,
      'srcset_widths'  => array(),
    );

    $dirRel = dirname($relPath);
    $dirRel = ($dirRel === '.' ? '' : $dirRel);
    $base = pathinfo($relPath, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
    if ($ext === 'jpeg' || $ext === 'jpe') {
        $ext = 'jpg';
    }

    $width = 0;
    $height = 0;
    $type = null;
    if (defined('DIR_FS_CATALOG_IMAGES') && is_file(DIR_FS_CATALOG_IMAGES . $relPath)) {
        $info = @getimagesize(DIR_FS_CATALOG_IMAGES . $relPath);
        if (is_array($info)) {
            $width = (int)$info[0];
            $height = (int)$info[1];
            $type = (int)$info[2];
        }
    }

    $fallbackExt = $ext;
    if (!in_array($fallbackExt, array('jpg', 'png'), true)) {
        $fallbackExt = ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) ? 'png' : 'jpg';
    }

    $fallbackRel = ($dirRel !== '' ? $dirRel . '/' : '') . $base . '.' . $fallbackExt;
    if ($width <= 0 && mits_imageslider_variant_file_exists($fallbackRel)) {
        $info = @getimagesize(DIR_FS_CATALOG_IMAGES . $fallbackRel);
        if (is_array($info)) {
            $width = (int)$info[0];
            $height = (int)$info[1];
        }
    }

    $maxBaseWidth = isset($cfg['max_base_width']) ? (int)$cfg['max_base_width'] : 0;
    if ($maxBaseWidth > 0 && $width > $maxBaseWidth) {
        $width = $maxBaseWidth;
    }

    $webpRel = ($dirRel !== '' ? $dirRel . '/' : '') . $base . '.webp';
    $srcsetRelDir = ($dirRel !== '' ? $dirRel . '/' : '') . 'srcset';

    $fallbackSrcset = array();
    $webpSrcset = array();
    foreach ((array)($cfg['srcset_widths'] ?? array()) as $w) {
        $w = (int)$w;
        if ($w <= 0) {
            continue;
        }
        if ($width > 0 && $w >= $width) {
            continue;
        }
        $fallbackSrcsetRel = $srcsetRelDir . '/' . $base . '-' . $w . '.' . $fallbackExt;
        $webpSrcsetRel = $srcsetRelDir . '/' . $base . '-' . $w . '.webp';
        $fallbackSrcset[$w] = array(
          'rel'    => $fallbackSrcsetRel,
          'exists' => mits_imageslider_variant_file_exists($fallbackSrcsetRel),
        );
        $webpSrcset[$w] = array(
          'rel'    => $webpSrcsetRel,
          'exists' => mits_imageslider_variant_file_exists($webpSrcsetRel),
        );
    }

    return array(
      'profile'         => $profile,
      'fallback_ext'    => $fallbackExt,
      'fallback'        => $fallbackRel,
      'fallback_exists' => mits_imageslider_variant_file_exists($fallbackRel),
      'webp'            => $webpRel,
      'webp_supported'  => function_exists('imagewebp'),
      'webp_exists'     => mits_imageslider_variant_file_exists($webpRel),
      'srcset_dir'      => $srcsetRelDir,
      'srcset_exists'   => defined('DIR_FS_CATALOG_IMAGES') && is_dir(DIR_FS_CATALOG_IMAGES . $srcsetRelDir),
      'srcset_fallback' => $fallbackSrcset,
      'srcset_webp'     => $webpSrcset,
    );
}

function mits_imageslider_variant_details_html($field, $relBefore, $profile, array $before, array $after): string
{
    $lines = array();
    $lines[] = '<strong>' . htmlspecialchars($field) . '</strong> (' . htmlspecialchars($profile) . '): ' . htmlspecialchars((string)$relBefore);

    $lines[] = '&nbsp;&nbsp;&bull; ' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_DETAIL_FALLBACK', 'Fallback') . ' ' . strtoupper($after['fallback_ext']) . ': <code>' . htmlspecialchars($after['fallback']) . '</code> ' . mits_imageslider_variant_label(!empty($before['fallback_exists']), !empty($after['fallback_exists']));

    if (!empty($after['webp_supported'])) {
        $lines[] = '&nbsp;&nbsp;&bull; WebP: <code>' . htmlspecialchars($after['webp']) . '</code> ' . mits_imageslider_variant_label(!empty($before['webp_exists']), !empty($after['webp_exists']));
    } else {
        $lines[] = '&nbsp;&nbsp;&bull; WebP: <span class="warn">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_NOT_SUPPORTED', 'vom Server nicht unterst&uuml;tzt') . '</span>';
    }

    $fallbackWidths = array_keys($after['srcset_fallback']);
    if (count($fallbackWidths) > 0) {
        $parts = array();
        foreach ($after['srcset_fallback'] as $w => $item) {
            $beforeExists = isset($before['srcset_fallback'][$w]['exists']) ? (bool)$before['srcset_fallback'][$w]['exists'] : false;
            $afterExists = isset($item['exists']) ? (bool)$item['exists'] : false;
            $parts[] = (int)$w . 'w ' . mits_imageslider_variant_label($beforeExists, $afterExists);
        }
        $lines[] = '&nbsp;&nbsp;&bull; srcset ' . strtoupper($after['fallback_ext']) . ': ' . implode(', ', $parts);
    } else {
        $lines[] = '&nbsp;&nbsp;&bull; srcset ' . strtoupper($after['fallback_ext']) . ': <span class="small">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_NOT_NEEDED', 'nicht n&ouml;tig / Bild kleiner als Zielbreiten') . '</span>';
    }

    if (!empty($after['webp_supported'])) {
        $webpWidths = array_keys($after['srcset_webp']);
        if (count($webpWidths) > 0) {
            $parts = array();
            foreach ($after['srcset_webp'] as $w => $item) {
                $beforeExists = isset($before['srcset_webp'][$w]['exists']) ? (bool)$before['srcset_webp'][$w]['exists'] : false;
                $afterExists = isset($item['exists']) ? (bool)$item['exists'] : false;
                $parts[] = (int)$w . 'w ' . mits_imageslider_variant_label($beforeExists, $afterExists);
            }
            $lines[] = '&nbsp;&nbsp;&bull; srcset WebP: ' . implode(', ', $parts);
        } else {
            $lines[] = '&nbsp;&nbsp;&bull; srcset WebP: <span class="small">' . mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STATUS_NOT_NEEDED', 'nicht n&ouml;tig / Bild kleiner als Zielbreiten') . '</span>';
        }
    }

    return implode('<br>', $lines);
}

function mits_imageslider_variants_total(): int
{
    $total_q = xtc_db_query("SELECT COUNT(*) AS cnt FROM " . TABLE_MITS_IMAGESLIDER_INFO . " WHERE (imagesliders_image != '' OR imagesliders_tablet_image != '' OR imagesliders_mobile_image != '')");
    $total_row = xtc_db_fetch_array($total_q);
    return (int)($total_row['cnt'] ?? 0);
}

function mits_imageslider_variants_fetch_batch($lastId, $lastLang, $limit)
{
    $lastId = (int)$lastId;
    $lastLang = (int)$lastLang;
    $limit = max(1, min(100, (int)$limit));

    $whereCursor = '';
    if ($lastId > 0 || $lastLang > 0) {
        $whereCursor = " AND (imagesliders_id > " . $lastId . " OR (imagesliders_id = " . $lastId . " AND languages_id > " . $lastLang . "))";
    }

    return xtc_db_query(
      "SELECT imagesliders_id, languages_id,
              imagesliders_image, imagesliders_image_width, imagesliders_image_height,
              imagesliders_tablet_image, imagesliders_tablet_image_width, imagesliders_tablet_image_height,
              imagesliders_mobile_image, imagesliders_mobile_image_width, imagesliders_mobile_image_height
         FROM " . TABLE_MITS_IMAGESLIDER_INFO . "
        WHERE (imagesliders_image != '' OR imagesliders_tablet_image != '' OR imagesliders_mobile_image != '')
              " . $whereCursor . "
        ORDER BY imagesliders_id ASC, languages_id ASC
        LIMIT " . (int)$limit
    );
}

function mits_imageslider_variants_process_row($row): array
{
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

        $beforeState = mits_imageslider_variant_state($rel, $profile);

        $new_rel = $rel;
        if (function_exists('mits_imageslider_generate_variants_from_relative')) {
            $maybe_new = mits_imageslider_generate_variants_from_relative($rel, $profile);
            if (is_string($maybe_new) && $maybe_new !== '') {
                $new_rel = $maybe_new;
            }
        }

        $afterState = mits_imageslider_variant_state($new_rel, $profile);

        $log[] = sprintf(MITS_IMAGESLIDER_VARIANTS_LOG_GENERATED, $field, htmlspecialchars($new_rel));
        $log[] = mits_imageslider_variant_details_html($field, $rel, $profile, $beforeState, $afterState);

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

    $mainRel = trim((string)($row['imagesliders_image'] ?? ''));
    if ($mainRel !== '' && function_exists('mits_imageslider_generate_auto_fallback_from_relative')) {
        foreach (array(
          array('field' => 'imagesliders_tablet_image', 'profile' => 'tablet'),
          array('field' => 'imagesliders_mobile_image', 'profile' => 'mobile'),
        ) as $autoVariant) {
            if (trim((string)($row[$autoVariant['field']] ?? '')) !== '') {
                continue;
            }
            $autoRel = mits_imageslider_generate_auto_fallback_from_relative($mainRel, $autoVariant['profile']);
            if ($autoRel !== '') {
                $log[] = sprintf(
                  '%s: automatische %s-Fallback-Varianten aus dem Hauptbild erzeugt: %s',
                  htmlspecialchars($autoVariant['field']),
                  htmlspecialchars($autoVariant['profile']),
                  htmlspecialchars($autoRel)
                );
                $log[] = mits_imageslider_variant_details_html($autoVariant['field'], $autoRel, $autoVariant['profile'], array(), mits_imageslider_variant_state($autoRel, $autoVariant['profile']));
            }
        }
    }

    $updated = false;
    if (count($updates) > 0) {
        xtc_db_perform(
          TABLE_MITS_IMAGESLIDER_INFO,
          $updates,
          'update',
          "imagesliders_id = " . (int)$id . " AND languages_id = " . (int)$lang
        );
        $updated = true;
    }

    return array(
      'id'      => $id,
      'lang'    => $lang,
      'updated' => $updated,
      'skipped' => (count($log) === 0),
      'log'     => $log,
    );
}

$sessionKey = mits_imageslider_variants_session_key();
$action = isset($_GET['action']) ? (string)$_GET['action'] : '';
$runActive = false;
$batchMessages = array();
$batchProcessed = 0;
$batchUpdated = 0;
$batchSkipped = 0;
$hasNext = false;
$doneNow = false;

if ($action === 'stop') {
    unset($_SESSION[$sessionKey]);
    xtc_redirect(basename(__FILE__));
}

if ($action === 'start' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 20;
    $limit = max(1, min(100, $limit));

    $_SESSION[$sessionKey] = array(
      'running'   => true,
      'total'     => mits_imageslider_variants_total(),
      'processed' => 0,
      'updated'   => 0,
      'skipped'   => 0,
      'last_id'   => 0,
      'last_lang' => 0,
      'limit'     => $limit,
      'started'   => date('Y-m-d H:i:s'),
      'finished'  => '',
    );

    xtc_redirect(basename(__FILE__) . '?run=1');
}

if (isset($_GET['run']) && isset($_SESSION[$sessionKey]) && !empty($_SESSION[$sessionKey]['running'])) {
    $runActive = true;
    $run = $_SESSION[$sessionKey];
    $limit = isset($run['limit']) ? max(1, min(100, (int)$run['limit'])) : 20;

    $batch_q = mits_imageslider_variants_fetch_batch($run['last_id'] ?? 0, $run['last_lang'] ?? 0, $limit);

    while ($row = xtc_db_fetch_array($batch_q)) {
        $result = mits_imageslider_variants_process_row($row);
        $batchMessages[] = $result;
        $batchProcessed++;
        if ($result['updated']) {
            $batchUpdated++;
        }
        if ($result['skipped']) {
            $batchSkipped++;
        }
        $run['last_id'] = (int)$result['id'];
        $run['last_lang'] = (int)$result['lang'];
    }

    $run['processed'] = (int)($run['processed'] ?? 0) + $batchProcessed;
    $run['updated'] = (int)($run['updated'] ?? 0) + $batchUpdated;
    $run['skipped'] = (int)($run['skipped'] ?? 0) + $batchSkipped;

    if ($batchProcessed > 0) {
        $testNext = mits_imageslider_variants_fetch_batch($run['last_id'] ?? 0, $run['last_lang'] ?? 0, 1);
        $hasNext = xtc_db_num_rows($testNext) > 0;
    } else {
        $hasNext = false;
    }

    if (!$hasNext) {
        $run['running'] = false;
        $run['finished'] = date('Y-m-d H:i:s');
        $doneNow = true;
    }

    $_SESSION[$sessionKey] = $run;
}

$currentRun = isset($_SESSION[$sessionKey]) && is_array($_SESSION[$sessionKey]) ? $_SESSION[$sessionKey] : array();
$total = isset($currentRun['total']) ? (int)$currentRun['total'] : mits_imageslider_variants_total();
$processedTotal = isset($currentRun['processed']) ? (int)$currentRun['processed'] : 0;
$updatedTotal = isset($currentRun['updated']) ? (int)$currentRun['updated'] : 0;
$skippedTotal = isset($currentRun['skipped']) ? (int)$currentRun['skipped'] : 0;
$progressPercent = ($total > 0) ? min(100, round(($processedTotal / $total) * 100, 1)) : 100;
$autoContinue = ($runActive && $hasNext);

require_once(DIR_WS_INCLUDES . 'head.php');
if ($autoContinue) {
    echo '<meta http-equiv="refresh" content="1;url=' . htmlspecialchars(basename(__FILE__)) . '?run=1">' . PHP_EOL;
}
?>
<script type="text/javascript" src="includes/general.js"></script>
<style>
  code { background:#f3f3f3; padding:2px 4px; border-radius:4px }
  .box { border:1px solid #ddd; border-radius:8px; padding:14px; margin:12px 0; background:#fff }
  .ok { color:#0a7a0a }
  .warn { color:#a15b00 }
  .err { color:#b00020 }
  .small { font-size:12px; color:#444 }
  ul { margin:8px 0 0 18px }
  a.btn, .btn { display:inline-block; background:#66aa99; color:#fff; text-decoration:none; padding:8px 12px; border-radius:6px; font-size:14px; border:0; cursor:pointer }
  a.btn2, .btn2 { display:inline-block; background:#fff; color:#66aa99; border:1px solid #66aa99; text-decoration:none; padding:8px 12px; border-radius:6px; font-size:14px; cursor:pointer }
  .progress-wrap { background:#eee; border-radius:8px; height:24px; overflow:hidden; margin:10px 0 }
  .progress-bar { background:#66aa99; height:24px; line-height:24px; color:#fff; text-align:center; min-width:42px }
  .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px }
  @media (max-width: 800px) { .grid { grid-template-columns:1fr } }
</style></head>
<body>
<!-- header //-->
<?php require_once(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table class="tableBody">
  <tr>
      <?php
      if (USE_ADMIN_TOP_MENU == 'false') {
          echo '<td class="columnLeft2">' . PHP_EOL;
          echo '<!-- left_navigation //-->' . PHP_EOL;
          require_once(DIR_WS_INCLUDES . 'column_left.php');
          echo '<!-- left_navigation eof //-->' . PHP_EOL;
          echo '</td>' . PHP_EOL;
      }
      ?>
    <td class="boxCenter" width="100%" valign="top">
      <h1><?php echo MITS_IMAGESLIDER_VARIANTS_HEADING; ?></h1>

      <div class="box">
        <p><?php echo MITS_IMAGESLIDER_VARIANTS_INTRO; ?></p>
        <ul>
          <li><?php echo MITS_IMAGESLIDER_VARIANTS_FEATURE_WEBP; ?></li>
          <li><?php echo MITS_IMAGESLIDER_VARIANTS_FEATURE_FALLBACK; ?></li>
          <li><?php echo MITS_IMAGESLIDER_VARIANTS_FEATURE_SRCSET; ?></li>
          <li><?php echo MITS_IMAGESLIDER_VARIANTS_FEATURE_DB; ?></li>
        </ul>
        <p><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_AUTO_INTRO', 'Die Verarbeitung l&auml;uft automatisch in kleinen Batches weiter, bis alle Sliderbilder fertig sind. Das Browserfenster bitte ge&ouml;ffnet lassen.'); ?></p>
        <div style="text-align:center;margin:16px 0 4px 0;">
          <a href="https://imageslider.merz-it-service.de/readme_mits_imageslider_regenerate_variants.html" target="_blank" onclick="window.open('https://imageslider.merz-it-service.de/readme_mits_imageslider_regenerate_variants.html', '<?php echo addslashes(MITS_IMAGESLIDER_VARIANTS_DOC_WINDOW_TITLE); ?>', 'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600'); return false">
            <strong><u><?php echo MITS_IMAGESLIDER_VARIANTS_DOC_LINK_TEXT; ?></u></strong>
          </a>
        </div>
      </div>

      <div class="box">
        <div class="grid">
          <div>
            <div><strong><?php echo MITS_IMAGESLIDER_VARIANTS_TOTAL; ?>:</strong> <?php echo (int)$total; ?> <?php echo MITS_IMAGESLIDER_VARIANTS_RECORDS; ?></div>
            <div><strong><?php echo MITS_IMAGESLIDER_VARIANTS_PROCESSED; ?>:</strong> <?php echo (int)$processedTotal; ?></div>
            <div><strong><?php echo MITS_IMAGESLIDER_VARIANTS_DB_UPDATES; ?>:</strong> <?php echo (int)$updatedTotal; ?></div>
            <div><strong><?php echo MITS_IMAGESLIDER_VARIANTS_SKIPPED; ?>:</strong> <?php echo (int)$skippedTotal; ?></div>
          </div>
          <div>
            <?php if (!empty($currentRun['started'])): ?>
              <div><strong><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_STARTED', 'Gestartet'); ?>:</strong> <?php echo htmlspecialchars(mits_imageslider_variants_format_datetime($currentRun['started'])); ?></div>
            <?php endif; ?>
            <?php if (!empty($currentRun['finished'])): ?>
              <div><strong><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_FINISHED', 'Beendet'); ?>:</strong> <?php echo htmlspecialchars(mits_imageslider_variants_format_datetime($currentRun['finished'])); ?></div>
            <?php endif; ?>
            <?php if (!empty($currentRun['limit'])): ?>
              <div><strong><?php echo MITS_IMAGESLIDER_VARIANTS_BATCH; ?>:</strong> <?php echo (int)$currentRun['limit']; ?> <?php echo MITS_IMAGESLIDER_VARIANTS_RECORDS; ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="progress-wrap">
          <div class="progress-bar" style="width:<?php echo (float)$progressPercent; ?>%;"><?php echo htmlspecialchars((string)$progressPercent); ?>%</div>
        </div>

        <?php if ($autoContinue): ?>
          <p class="small ok"><strong><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_RUNNING', 'L&auml;uft'); ?>:</strong> <?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_AUTO_REFRESH', 'Der n&auml;chste Batch startet automatisch.'); ?></p>
          <p><a class="btn2" href="<?php echo htmlspecialchars(basename(__FILE__)); ?>?action=stop"><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_BTN_STOP', 'Abbrechen'); ?></a></p>
        <?php elseif ($doneNow || (!empty($currentRun) && empty($currentRun['running']) && $processedTotal > 0)): ?>
          <p class="ok"><strong><?php echo MITS_IMAGESLIDER_VARIANTS_DONE; ?></strong></p>
          <p><a class="btn2" href="<?php echo htmlspecialchars(basename(__FILE__)); ?>?action=stop"><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_BTN_RESET', 'Neue Verarbeitung starten'); ?></a></p>
        <?php else: ?>
          <?php echo xtc_draw_form('imagesliders_variants_start', basename(__FILE__) . '?action=start', '', 'post'); ?>
            <p class="small"><strong><?php echo MITS_IMAGESLIDER_VARIANTS_NOTE_LABEL; ?>:</strong> <?php echo MITS_IMAGESLIDER_VARIANTS_NOTE; ?></p>
            <label>
              <?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_BATCH_SIZE', 'Datens&auml;tze pro Batch'); ?>:
              <input type="number" name="limit" value="20" min="1" max="100" style="width:80px;">
            </label>
            <button class="btn" type="submit"><?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_BTN_START_AUTO', 'Alle Bilder automatisch regenerieren'); ?></button>
          </form>
        <?php endif; ?>
      </div>

      <?php if ($runActive): ?>
        <div class="box">
          <strong><?php echo MITS_IMAGESLIDER_VARIANTS_BATCH_RESULT; ?></strong>
          <?php echo MITS_IMAGESLIDER_VARIANTS_PROCESSED; ?>=<?php echo (int)$batchProcessed; ?>,
          <?php echo MITS_IMAGESLIDER_VARIANTS_DB_UPDATES; ?>=<?php echo (int)$batchUpdated; ?>,
          <?php echo MITS_IMAGESLIDER_VARIANTS_SKIPPED; ?>=<?php echo (int)$batchSkipped; ?>
        </div>

        <?php foreach ($batchMessages as $m): ?>
          <div class="box">
            <div><strong>ID:</strong> <?php echo (int)$m['id']; ?> <strong>Lang:</strong> <?php echo (int)$m['lang']; ?></div>
            <?php if (count($m['log']) > 0): ?>
              <ul>
                <?php foreach ($m['log'] as $line): ?>
                  <li><?php echo $line; ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <div class="small"><?php echo MITS_IMAGESLIDER_VARIANTS_NO_ACTIONS; ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

        <?php if ($autoContinue): ?>
          <noscript>
            <div class="box warn">
              <?php echo mits_imageslider_variants_t('MITS_IMAGESLIDER_VARIANTS_NOSCRIPT', 'JavaScript/Meta-Refresh ist deaktiviert. Bitte den n&auml;chsten Batch manuell starten.'); ?>
              <br><a class="btn" href="<?php echo htmlspecialchars(basename(__FILE__)); ?>?run=1"><?php echo MITS_IMAGESLIDER_VARIANTS_NEXT_BATCH; ?></a>
            </div>
          </noscript>
        <?php endif; ?>
      <?php endif; ?>

    </td>
  </tr>
</table>

<!-- footer //-->
<?php require_once(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //--><br />
</body></html>
<?php
require_once(DIR_WS_INCLUDES . 'application_bottom.php');
