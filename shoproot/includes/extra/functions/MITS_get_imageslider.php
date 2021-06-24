<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 24.06.2021
 * Time: 15:17
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

function MITS_get_imageslider($group = 'mits_imageslider') {
  if (defined('MODULE_MITS_IMAGESLIDER_STATUS') && MODULE_MITS_IMAGESLIDER_STATUS == 'true') {

    $date_sliders_query = xtc_db_query("SELECT imagesliders_id, expires_date, date_scheduled FROM " . TABLE_MITS_IMAGESLIDER . " WHERE imagesliders_group = '" . xtc_db_input($group) . "'");
    if (xtc_db_num_rows($date_sliders_query)) {
      while ($date_sliders = xtc_db_fetch_array($date_sliders_query)) {
        if (xtc_not_null($date_sliders['date_scheduled'])) {
          if (date('Y-m-d H:i:s') >= $date_sliders['date_scheduled']) {
            xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER . " SET status = 0 WHERE imagesliders_id = " . (int)$date_sliders['imagesliders_id']);
          }
        }
        if (xtc_not_null($date_sliders['expires_date'])) {
          if (date('Y-m-d H:i:s') >= $date_sliders['expires_date']) {
            xtc_db_query("UPDATE " . TABLE_MITS_IMAGESLIDER . " SET status = 1 WHERE imagesliders_id = " . (int)$date_sliders['imagesliders_id']);
          }
        }
      }
    }

    $group = strtolower($group);

    require_once(DIR_FS_INC . 'xtc_get_products_name.inc.php');
    require_once(DIR_FS_EXTERNAL . 'mits_imageslider/functions/mits_get_categories_name.inc.php');

    $mits_imagesliders_query = xtDBquery("SELECT * FROM " . TABLE_MITS_IMAGESLIDER . " i, " . TABLE_MITS_IMAGESLIDER_INFO . " ii
													 WHERE ii.languages_id = " . (int)$_SESSION['languages_id'] . "
													 AND i.imagesliders_id = ii.imagesliders_id
													 AND ii.imagesliders_image != ''
													 AND i.status = 0
													 AND i.imagesliders_group = '" . xtc_db_input($group) . "'
													 ORDER BY i.sorting, i.imagesliders_id ASC");
    if (xtc_db_num_rows($mits_imagesliders_query, true)) {
      $mits_imagesliders_string = '';
      $sliderdata = array();
      while ($mits_imageslider_data = xtc_db_fetch_array($mits_imagesliders_query, true)) {

        switch ($mits_imageslider_data['imagesliders_url_target']) {
          case 1:
            $target = ' target="_blank"';
            break;
          case 2:
            $target = ' target="_top"';
            break;
          case 3:
            $target = ' target="_self"';
            break;
          case 4:
            $target = ' target="_parent"';
            break;
          default:
            $target = '';
        }

        switch ($mits_imageslider_data['imagesliders_url_typ']) {
          case 0:
            $url = $mits_imageslider_data['imagesliders_url'];
            break;
          case 1:
            $url = xtc_href_link($mits_imageslider_data['imagesliders_url']);
            break;
          case 2:
            $url = xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$mits_imageslider_data['imagesliders_url']);
            break;
          case 3:
            $url = xtc_href_link(FILENAME_DEFAULT, xtc_category_link((int)$mits_imageslider_data['imagesliders_url'], mits_get_categories_name((int)$mits_imageslider_data['imagesliders_url'])));
            break;
          case 4:
            $url = xtc_href_link(FILENAME_CONTENT, 'coID=' . (int)$mits_imageslider_data['imagesliders_url']);
            break;
          default:
            $url = xtc_href_link(FILENAME_DEFAULT);
        }

        $mobile_bild = ($mits_imageslider_data['imagesliders_mobile_image'] != '') ? DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'] : '';
        $tablet_bild = ($mits_imageslider_data['imagesliders_tablet_image'] != '') ? DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'] : '';
        $hauptbild = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'];

        if ($mobile_bild != '') {
          $bild = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_mobile_image'];
        } elseif ($tablet_bild != '') {
          $bild = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_tablet_image'];
        } else {
          $bild = DIR_WS_BASE . DIR_WS_IMAGES . $mits_imageslider_data['imagesliders_image'];
        }

        $sliderdata[] = array(
          'id'          => $mits_imageslider_data['imagesliders_id'],
          'link'        => $url,
          'target'      => $target,
          'haupt_bild'  => $hauptbild,
          'tablet_bild' => $tablet_bild,
          'mobile_bild' => $mobile_bild,
          'bild'        => $bild,
          'bildset'     => $mobile_bild . $tablet_bild . $hauptbild,
          'titel'       => strip_tags(str_replace(array('"', "'"), array('&quot;', '&apos;'), $mits_imageslider_data['imagesliders_title'])),
          'alt'         => strip_tags(str_replace(array('"', "'"), array('&quot;', '&apos;'), $mits_imageslider_data['imagesliders_title'])),
          'text'        => $mits_imageslider_data['imagesliders_description']
        );

        $datasrc = (defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD') && MODULE_MITS_IMAGESLIDER_LAZYLOAD == 'true') ? 'data-' : '';
        $lazyloadclass = (defined('MODULE_MITS_IMAGESLIDER_LAZYLOAD') && MODULE_MITS_IMAGESLIDER_LAZYLOAD == 'true') ? 'class="lazyload" ' : '';

      }


      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'bxSlider') {
        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string .= '
					<ul class="mits_bxslider">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
						<li>
							<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
								' . $img . '
							</a>
						</li>';
          }
          $mits_imagesliders_string .= '
					</ul>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'bxSlider tpl_modified') {
        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string .= '<div class="content_banner cf">
					<ul class="bxcarousel_slider">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" data-srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" data-srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source data-srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img class="unveil" src="' . DIR_WS_BASE . 'templates/' . CURRENT_TEMPLATE . '/css/images/loading.gif" data-src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img class="unveil" src="' . DIR_WS_BASE . 'templates/' . CURRENT_TEMPLATE . '/css/images/loading.gif" data-src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
						<li>
							<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
								' . $img . '
							</a>
						</li>';
          }
          $mits_imagesliders_string .= '
					</ul>
				</div>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Slick tpl_modified') {

        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string = (sizeof($sliderdata) == 1) ? '<div class="mits_sliderimage"><div>' : '<div class="content_slider cf">
              <div class="slider_home">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            //$slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="slick-desc">' . $sliderdata[$i]['text'] . '</div>' : '');
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
						<div class="slider_item">
							<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
                ' . $img . '
							</a>
							' . $slidertext . '
						</div>';
          }
          $mits_imagesliders_string .= '</div>
					</div>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'Slick') {

        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string = (sizeof($sliderdata) == 1) ? '<div class="mits_sliderimage">' : '<div class="mits_slickslider">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            $slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="slick-desc">' . $sliderdata[$i]['text'] . '</div>' : '');
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
						<div>
							<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
                ' . $img . '
							</a>
							' . $slidertext . '
						</div>';
          }
          $mits_imagesliders_string .= '
					</div>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'NivoSlider') {
        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string .= '
					<div class="slider-wrapper theme-default">
						<div class="ribbon"></div>';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            $mits_imagesliders_string .= chr(9) . '<div id="' . $sliderdata[$i]['id'] . '" class="nivo-html-caption">' . chr(13);
            if ($sliderdata[$i]['titel'] != '') $mits_imagesliders_string .= chr(9) . chr(9) . '<h3>' . strip_tags($sliderdata[$i]['titel']) . '</h3>' . chr(13);
            if ($sliderdata[$i]['text'] != '') $mits_imagesliders_string .= chr(9) . chr(9) . '<div>' . strip_tags($sliderdata[$i]['text']) . '</div>' . chr(13);
            $mits_imagesliders_string .= chr(9) . '</div>' . chr(13);
          }
          $mits_imagesliders_string .= '
						<div class="nivoSlider mits_nivoSlider">' . chr(13);
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
            <a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
              ' . $img . '         
            </a>' . chr(13);
          }
          $mits_imagesliders_string .= '
						</div>
					</div>' . chr(13);
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'FlexSlider') {
        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string .= '
					<div class="flex-container">
						<div class="flexslider">
							<ul class="slides">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            $slidertext = (($sliderdata[$i]['text'] != '') ? '<div class="flex-caption"><div class="flex-caption-header">' . $sliderdata[$i]['titel'] . '</div><div>' . $sliderdata[$i]['text'] . '</div></div>' : '<div class="flex-caption"><div class="flex-caption-header">' . $sliderdata[$i]['titel'] . '</div></div>');
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
								<li>
									<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
										' . $img . '
										' . $slidertext . '
									</a>
								</li>';
          }
          $mits_imagesliders_string .= '
							</ul>
						</div>
					</div>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'jQuery.innerfade') {
        if (sizeof($sliderdata) > 0) {
          $mits_imagesliders_string .= '				
					<div class="mits_imageslider">
						<ul class="imageslider">';
          for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
            if ($sliderdata[$i]['mobile_bild'] != '' || $sliderdata[$i]['mobile_bild'] != '') {
              $img = '<picture>';
              if ($sliderdata[$i]['mobile_bild'] != '') $img .= '<source media="(max-width:600px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['mobile_bild'] . '">';
              if ($sliderdata[$i]['tablet_bild'] != '') $img .= '<source media="(max-width:1023px)" ' . $datasrc . 'srcset="' . $sliderdata[$i]['tablet_bild'] . '">';
              $img .= '<source ' . $datasrc . 'srcset="' . $sliderdata[$i]['haupt_bild'] . '">';
              $img .= '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
              $img .= '</picture>';
            } else {
              $img = '<img ' . $lazyloadclass . $datasrc . 'src="' . $sliderdata[$i]['haupt_bild'] . '" alt="' . $sliderdata[$i]['alt'] . '" title="' . $sliderdata[$i]['titel'] . '" />';
            }
            $mits_imagesliders_string .= '
							<li>
								<a href="' . $sliderdata[$i]['link'] . '" title="' . $sliderdata[$i]['titel'] . '"' . $sliderdata[$i]['target'] . '>
									' . $img . '
								</a>';
            if ($sliderdata[$i]['text'] != '') $mits_imagesliders_string .= '<div class="slidercontent"><div class="slidercontentinner">' . $sliderdata[$i]['text'] . '</div></div>';
            $mits_imagesliders_string .= '
							</li>';
          }
          $mits_imagesliders_string .= '
						</ul>
						<ul class="imageslider-nav">
							<li class="slideprev"><a class="prev" href="#">' . PREVNEXT_BUTTON_PREV . '</a></li>
							<li class="slidepause"><a class="pause" href="#">Pause</a></li>
							<li class="slidenext"><a class="next" href="#">' . PREVNEXT_BUTTON_NEXT . '</a></li>
						</ul>
						<div class="innerfadeclear"></div>
					</div>';
        }
      }

      if (defined('MODULE_MITS_IMAGESLIDER_TYPE') && MODULE_MITS_IMAGESLIDER_TYPE == 'custom'
        && defined('MODULE_MITS_IMAGESLIDER_CUSTOM_CODE') && MODULE_MITS_IMAGESLIDER_CUSTOM_CODE != ''
      ) {
        $customcode = explode('###SLIDERITEM###', MODULE_MITS_IMAGESLIDER_CUSTOM_CODE);
        if (is_array($customcode)) {
          $customcode_top = (isset($customcode[0]) && !empty($customcode[0])) ? $customcode[0] : '';
          $customcode_slider = (isset($customcode[1]) && !empty($customcode[1])) ? $customcode[1] : '';
          $customcode_bottom = (isset($customcode[2]) && !empty($customcode[2])) ? $customcode[2] : '';

          if (sizeof($sliderdata) > 0 && $customcode_slider != '') {
            $mits_imagesliders_string .= $customcode_top;
            for ($i = 0, $n = sizeof($sliderdata); $i < $n; $i++) {
              $customcode_slider_item = $customcode_slider;
              $customcode_slider_item = str_replace('{ID}', $sliderdata[$i]['id'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{LINK}', $sliderdata[$i]['link'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{LINKTARGET}', $sliderdata[$i]['target'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{MAINIMAGE}', $sliderdata[$i]['haupt_bild'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{TABLETIMAGE}', $sliderdata[$i]['tablet_bild'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{MOBILEIMAGE}', $sliderdata[$i]['mobile_bild'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{IMAGE}', $sliderdata[$i]['bild'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{IMAGESET}', $sliderdata[$i]['bildset'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{TITLE}', $sliderdata[$i]['titel'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{IMAGEALT}', $sliderdata[$i]['alt'], $customcode_slider_item);
              $customcode_slider_item = str_replace('{TEXT}', $sliderdata[$i]['text'], $customcode_slider_item);
              $mits_imagesliders_string .= $customcode_slider_item;
            }
            $mits_imagesliders_string .= $customcode_bottom;
          }
        }
      }

      if (!empty($mits_imagesliders_string)) {
        $version = (defined('MODULE_MITS_IMAGESLIDER_VERSION') && MODULE_MITS_IMAGESLIDER_VERSION != '') ? ' v' . MODULE_MITS_IMAGESLIDER_VERSION : '';
        return '<!-- MITS Imageslider' . $version . ' (c)2008-2020 by Hetfield - www.MerZ-IT-SerVice.de - Begin --><div class="mits_imageslider_container">' . $mits_imagesliders_string . '</div><!-- MITS Imageslider' . $version . ' (c)2008-2020 by Hetfield - www.MerZ-IT-SerVice.de - End -->';
      } else {
        return false;
      }

    }
  }
}

?>