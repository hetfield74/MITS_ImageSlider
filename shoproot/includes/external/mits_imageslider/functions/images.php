<?php
/**
 * --------------------------------------------------------------
 * File: images.php
 * Date: 16.02.2026
 * Time: 11:47
 *
 * Author: Hetfield
 * Copyright: (c) 2026 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */


if (!function_exists('mits_imageslider_images_root')) {
    function mits_imageslider_images_root(): string
    {
        if (defined('DIR_FS_CATALOG_IMAGES')) {
            return rtrim(DIR_FS_CATALOG_IMAGES, '/\\') . '/';
        }
        if (defined('DIR_FS_CATALOG') && defined('DIR_WS_IMAGES')) {
            return rtrim(DIR_FS_CATALOG, '/\\') . '/' . trim(DIR_WS_IMAGES, '/\\') . '/';
        }
        return './';
    }
}

if (!function_exists('mits_imageslider_abs_image_path')) {
    function mits_imageslider_abs_image_path($relative_path): string
    {
        $relative_path = ltrim((string)$relative_path, '/\\');
        return mits_imageslider_images_root() . $relative_path;
    }
}

if (!function_exists('mits_imageslider_normalize_ext')) {
    function mits_imageslider_normalize_ext($ext): string
    {
        $ext = strtolower((string)$ext);
        if ($ext === 'jpeg' || $ext === 'jpe') {
            return 'jpg';
        }
        return $ext;
    }
}

if (!function_exists('mits_imageslider_profiles')) {
    function mits_imageslider_profiles(): array
    {
        return array(
          'mobile'  => array(
            'max_base_width' => 750,
            'srcset_widths'  => array(320, 480, 640, 750),
            'jpg_quality'    => 85,
            'webp_quality'   => 82,
            'png_compress'   => 6,
          ),
          'tablet'  => array(
            'max_base_width' => 1280,
            'srcset_widths'  => array(768, 1024, 1280),
            'jpg_quality'    => 85,
            'webp_quality'   => 82,
            'png_compress'   => 6,
          ),
          'desktop' => array(
            'max_base_width' => 2560,
            'srcset_widths'  => array(1280, 1600, 1920, 2560),
            'jpg_quality'    => 85,
            'webp_quality'   => 82,
            'png_compress'   => 6,
          ),
        );
    }
}

if (!function_exists('mits_imageslider_can_process_with_gd')) {
    function mits_imageslider_can_process_with_gd(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatetruecolor');
    }
}

if (!function_exists('mits_imageslider_get_image_info')) {
    function mits_imageslider_get_image_info($abs_path): ?array
    {
        if (!is_file($abs_path)) {
            return null;
        }
        $info = @getimagesize($abs_path);
        if (!is_array($info) || !isset($info[2])) {
            return null;
        }
        return array(
          'width'  => (int)$info[0],
          'height' => (int)$info[1],
          'type'   => (int)$info[2],
        );
    }
}

if (!function_exists('mits_imageslider_ImageProcessor')) {
    function mits_imageslider_ImageProcessor($abs_path, $type)
    {
        if (!is_file($abs_path)) {
            return null;
        }
        switch ((int)$type) {
            case IMAGETYPE_JPEG:
                return function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($abs_path) : null;
            case IMAGETYPE_PNG:
                return function_exists('imagecreatefrompng') ? @imagecreatefrompng($abs_path) : null;
            case IMAGETYPE_GIF:
                return function_exists('imagecreatefromgif') ? @imagecreatefromgif($abs_path) : null;
            case (defined('IMAGETYPE_WEBP') ? IMAGETYPE_WEBP : -1):
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($abs_path) : null;
            default:
                return null;
        }
    }
}

if (!function_exists('mits_imageslider_prepare_dest_canvas')) {
    function mits_imageslider_prepare_dest_canvas($type, $w, $h)
    {
        $dest = imagecreatetruecolor($w, $h);

        if (in_array((int)$type, array(IMAGETYPE_PNG, (defined('IMAGETYPE_WEBP') ? IMAGETYPE_WEBP : -1), IMAGETYPE_GIF), true)) {
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefilledrectangle($dest, 0, 0, $w, $h, $transparent);
        }

        return $dest;
    }
}

if (!function_exists('mits_imageslider_resize_resource')) {
    function mits_imageslider_resize_resource($src, $src_type, $target_w, $target_h)
    {
        $target_w = (int)$target_w;
        $target_h = (int)$target_h;
        if ($target_w <= 0 || $target_h <= 0) {
            return null;
        }

        $dest = mits_imageslider_prepare_dest_canvas($src_type, $target_w, $target_h);
        @imagecopyresampled($dest, $src, 0, 0, 0, 0, $target_w, $target_h, imagesx($src), imagesy($src));
        return $dest;
    }
}

if (!function_exists('mits_imageslider_save_image')) {
    function mits_imageslider_save_image($img, $abs_dest, $format, $quality_or_compress = null): bool
    {
        $format = mits_imageslider_normalize_ext($format);
        $abs_dest_dir = dirname($abs_dest);
        if (!is_dir($abs_dest_dir)) {
            @mkdir($abs_dest_dir, 0775, true);
        }

        ob_start();
        $ok = false;
        switch ($format) {
            case 'jpg':
                if (function_exists('imagejpeg')) {
                    @imageinterlace($img, true);
                    $q = is_numeric($quality_or_compress) ? (int)$quality_or_compress : 85;
                    $q = max(0, min(100, $q));
                    $ok = @imagejpeg($img, $abs_dest, $q);
                }
                break;

            case 'png':
                if (function_exists('imagepng')) {
                    $c = is_numeric($quality_or_compress) ? (int)$quality_or_compress : 6;
                    $c = max(0, min(9, $c));
                    @imagealphablending($img, false);
                    @imagesavealpha($img, true);
                    $ok = @imagepng($img, $abs_dest, $c);
                }
                break;

            case 'webp':
                if (function_exists('imagewebp')) {
                    $q = is_numeric($quality_or_compress) ? (int)$quality_or_compress : 82;
                    $q = max(0, min(100, $q));
                    $ok = @imagewebp($img, $abs_dest, $q);
                }
                break;
        }
        ob_end_clean();

        if ($ok) {
            @chmod($abs_dest, 0644);
        }

        return $ok;
    }
}

/**
 * Generate WebP + fallback + srcset variants.
 *
 * @param string $relative_path Path relative to /images/ (e.g. imagesliders/de/mobile/foo.jpg)
 * @param string $profile One of: desktop|tablet|mobile
 * @return string Fallback relative path (jpg/png) to store in DB.
 */
if (!function_exists('mits_imageslider_generate_variants_from_relative')) {
    function mits_imageslider_generate_variants_from_relative($relative_path, $profile = 'desktop')
    {
        $relative_path = (string)$relative_path;
        if ($relative_path === '') {
            return $relative_path;
        }
        if (!mits_imageslider_can_process_with_gd()) {
            return $relative_path;
        }

        $profiles = mits_imageslider_profiles();
        if (!isset($profiles[$profile])) {
            $profile = 'desktop';
        }
        $cfg = $profiles[$profile];

        $abs_original = mits_imageslider_abs_image_path($relative_path);
        if (!is_file($abs_original)) {
            return $relative_path;
        }

        $info = mits_imageslider_get_image_info($abs_original);
        if ($info === null) {
            return $relative_path;
        }
        $type = (int)$info['type'];

        $supported_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
        if (defined('IMAGETYPE_WEBP')) {
            $supported_types[] = IMAGETYPE_WEBP;
        }
        if (!in_array($type, $supported_types, true)) {
            return $relative_path;
        }

        if ($type === IMAGETYPE_GIF) {
            return $relative_path;
        }

        $dir_rel = dirname($relative_path);
        $dir_rel = ($dir_rel === '.' ? '' : $dir_rel);
        $base = pathinfo($relative_path, PATHINFO_FILENAME);
        $ext = mits_imageslider_normalize_ext(pathinfo($relative_path, PATHINFO_EXTENSION));

        $fallback_ext = $ext;
        if (!in_array($fallback_ext, array('jpg', 'png'), true)) {
            $fallback_ext = in_array($type, array(IMAGETYPE_PNG, IMAGETYPE_GIF), true) ? 'png' : 'jpg';
        }

        $fallback_rel = ($dir_rel !== '' ? $dir_rel . '/' : '') . $base . '.' . $fallback_ext;
        $abs_fallback = mits_imageslider_abs_image_path($fallback_rel);

        if ($fallback_rel !== $relative_path) {
            $src = mits_imageslider_ImageProcessor($abs_original, $type);
            if (!is_resource($src) && !($src instanceof \GdImage)) {
                return $relative_path;
            }

            $ok = mits_imageslider_save_image(
              $src,
              $abs_fallback,
              $fallback_ext,
              ($fallback_ext === 'jpg') ? $cfg['jpg_quality'] : $cfg['png_compress']
            );

            if (is_resource($src) || $src instanceof \GdImage) {
                @imagedestroy($src);
            }

            if ($ok) {
                @unlink($abs_original);
            } else {
                return $relative_path;
            }
        } else {
            $abs_fallback = $abs_original;
        }

        $info = mits_imageslider_get_image_info($abs_fallback);
        if ($info === null) {
            return $fallback_rel;
        }

        $src_type = (int)$info['type'];
        $src_w = (int)$info['width'];
        $src_h = (int)$info['height'];

        $src_img = mits_imageslider_ImageProcessor($abs_fallback, $src_type);
        if (!is_resource($src_img) && !($src_img instanceof \GdImage)) {
            return $fallback_rel;
        }

        $max_base_w = (int)$cfg['max_base_width'];
        if ($max_base_w > 0 && $src_w > $max_base_w) {
            $ratio = $max_base_w / $src_w;
            $new_w = $max_base_w;
            $new_h = (int)max(1, round($src_h * $ratio));

            $resized = mits_imageslider_resize_resource($src_img, $src_type, $new_w, $new_h);
            if ($resized) {
                if (is_resource($src_img) || $src_img instanceof \GdImage) {
                    @imagedestroy($src_img);
                }
                $src_img = $resized;
                $src_w = $new_w;
                $src_h = $new_h;

                mits_imageslider_save_image(
                  $src_img,
                  $abs_fallback,
                  $fallback_ext,
                  ($fallback_ext === 'jpg') ? $cfg['jpg_quality'] : $cfg['png_compress']
                );
            }
        }

        $abs_webp = mits_imageslider_abs_image_path(($dir_rel !== '' ? $dir_rel . '/' : '') . $base . '.webp');
        if (function_exists('imagewebp')) {
            mits_imageslider_save_image($src_img, $abs_webp, 'webp', $cfg['webp_quality']);
        }

        $srcset_rel_dir = ($dir_rel !== '' ? $dir_rel . '/' : '') . 'srcset';
        $abs_srcset_dir = mits_imageslider_abs_image_path($srcset_rel_dir);
        if (!is_dir($abs_srcset_dir)) {
            @mkdir($abs_srcset_dir, 0775, true);
        }

        foreach ((array)$cfg['srcset_widths'] as $w) {
            $w = (int)$w;
            if ($w <= 0) {
                continue;
            }
            if ($w >= $src_w) {
                continue;
            }

            $h = (int)max(1, round($src_h * ($w / $src_w)));
            $der = mits_imageslider_resize_resource($src_img, $src_type, $w, $h);
            if (!$der) {
                continue;
            }

            $fallback_var_rel = $srcset_rel_dir . '/' . $base . '-' . $w . '.' . $fallback_ext;
            $abs_fallback_var = mits_imageslider_abs_image_path($fallback_var_rel);
            mits_imageslider_save_image(
              $der,
              $abs_fallback_var,
              $fallback_ext,
              ($fallback_ext === 'jpg') ? $cfg['jpg_quality'] : $cfg['png_compress']
            );

            if (function_exists('imagewebp')) {
                $webp_var_rel = $srcset_rel_dir . '/' . $base . '-' . $w . '.webp';
                $abs_webp_var = mits_imageslider_abs_image_path($webp_var_rel);
                mits_imageslider_save_image($der, $abs_webp_var, 'webp', $cfg['webp_quality']);
            }

            if (is_resource($der) || $der instanceof \GdImage) {
                @imagedestroy($der);
            }
        }

        if (is_resource($src_img) || $src_img instanceof \GdImage) {
            @imagedestroy($src_img);
        }

        return $fallback_rel;
    }
}

/**
 * Delete fallback, WebP and srcset variants for a given relative path.
 *
 * @param string $relative_path Path relative to /images/
 */
if (!function_exists('mits_imageslider_delete_variants_from_relative')) {
    function mits_imageslider_delete_variants_from_relative($relative_path): void
    {
        $relative_path = (string)$relative_path;
        if ($relative_path === '') {
            return;
        }

        $dir_rel = dirname($relative_path);
        $dir_rel = ($dir_rel === '.' ? '' : $dir_rel);
        $base = pathinfo($relative_path, PATHINFO_FILENAME);
        $ext = mits_imageslider_normalize_ext(pathinfo($relative_path, PATHINFO_EXTENSION));

        $abs_base = mits_imageslider_abs_image_path($relative_path);
        if (is_file($abs_base)) {
            @unlink($abs_base);
        }

        $webp_rel = ($dir_rel !== '' ? $dir_rel . '/' : '') . $base . '.webp';
        $abs_webp = mits_imageslider_abs_image_path($webp_rel);
        if (is_file($abs_webp)) {
            @unlink($abs_webp);
        }

        $srcset_rel_dir = ($dir_rel !== '' ? $dir_rel . '/' : '') . 'srcset';
        $abs_srcset_dir = mits_imageslider_abs_image_path($srcset_rel_dir);
        if (is_dir($abs_srcset_dir)) {
            foreach (array('webp', $ext, 'jpg', 'png') as $e) {
                $e = mits_imageslider_normalize_ext($e);
                $pattern = $abs_srcset_dir . '/' . $base . '-*.' . $e;
                foreach ((array)glob($pattern) as $f) {
                    if (is_file($f)) {
                        @unlink($f);
                    }
                }
            }

            $left = (array)glob($abs_srcset_dir . '/*');
            if (count($left) === 0) {
                @rmdir($abs_srcset_dir);
            }
        }
    }
}

/**
 * Build a srcset string for a given fallback image.
 *
 * @param string $fallback_rel Relative fallback file (jpg/png)
 * @param string $profile mobile|tablet|desktop
 * @param string $format fallback|webp
 * @param int|null $base_width Width of base image (optional)
 * @return string srcset value
 */
if (!function_exists('mits_imageslider_build_srcset')) {
    function mits_imageslider_build_srcset($fallback_rel, $profile = 'desktop', $format = 'fallback', $base_width = null): string
    {
        $fallback_rel = (string)$fallback_rel;
        if ($fallback_rel === '') {
            return '';
        }

        $profiles = mits_imageslider_profiles();
        if (!isset($profiles[$profile])) {
            $profile = 'desktop';
        }
        $cfg = $profiles[$profile];

        $dir_rel = dirname($fallback_rel);
        $dir_rel = ($dir_rel === '.' ? '' : $dir_rel);
        $base = pathinfo($fallback_rel, PATHINFO_FILENAME);
        $fallback_ext = mits_imageslider_normalize_ext(pathinfo($fallback_rel, PATHINFO_EXTENSION));

        $srcset_rel_dir = ($dir_rel !== '' ? $dir_rel . '/' : '') . 'srcset';

        $items = array();

        $candidate_widths = (array)$cfg['srcset_widths'];
        sort($candidate_widths);
        foreach ($candidate_widths as $w) {
            $w = (int)$w;
            if ($w <= 0) {
                continue;
            }

            $rel = $srcset_rel_dir . '/' . $base . '-' . $w . '.' . (($format === 'webp') ? 'webp' : $fallback_ext);
            $abs = mits_imageslider_abs_image_path($rel);
            if (is_file($abs)) {
                $url = (defined('DIR_WS_BASE') && defined('DIR_WS_IMAGES'))
                  ? DIR_WS_BASE . DIR_WS_IMAGES . $rel
                  : $rel;
                $items[] = $url . ' ' . $w . 'w';
            }
        }

        $base_rel = $fallback_rel;
        if ($format === 'webp') {
            $base_rel = ($dir_rel !== '' ? $dir_rel . '/' : '') . $base . '.webp';
        }
        $abs_base = mits_imageslider_abs_image_path($base_rel);
        if (is_file($abs_base)) {
            $url_base = (defined('DIR_WS_BASE') && defined('DIR_WS_IMAGES'))
              ? DIR_WS_BASE . DIR_WS_IMAGES . $base_rel
              : $base_rel;

            $bw = null;
            if (is_numeric($base_width) && (int)$base_width > 0) {
                $bw = (int)$base_width;
            } else {
                $info = mits_imageslider_get_image_info($abs_base);
                if ($info !== null && (int)$info['width'] > 0) {
                    $bw = (int)$info['width'];
                }
            }

            if ($bw !== null && count($items) > 0 && $bw <= ((int)$cfg['max_base_width'] + 50)) {
                $items[] = $url_base . ' ' . $bw . 'w';
            }

            if (count($items) === 0) {
                return $url_base;
            }
        }

        return implode(', ', $items);
    }
}
