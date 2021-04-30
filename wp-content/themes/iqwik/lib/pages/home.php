<?php
/**
 * Слайдер
 * @param $num
 * @param $link
 * @param $img
 * @param $title
 * @param $text
 * @return string
 */
function get_single_slider_html($num, $link, $img, $title, $text) {
	return '<li class="sprocket-features-index-'.$num.'">' .
				'<div class="sprocket-features-img-container" data-slideshow-image>' .
					'<a href="{$link}"><img src="'.$img.'" alt="" style="max-width: 100%; height: auto;"></a>' .
					'<div class="rt-slideshow-overlay"></div>' .
					'<div class="rt-slideshow-overlay2"></div>' .
				'</div>' .
				'<div class="sprocket-features-content" data-slideshow-content>' .
					'<div class="sprocket-features-content-top"></div>' .
					'<h2 class="sprocket-features-title"><a href="'.SITE_URL.'/'.$link.'/">'.$title.'</a></h2>' .
					'<div class="sprocket-features-desc">' .
						'<span>'.$text.'</span>' .
					'</div>' .
					'<a href="'.$link.'" class="readon"><span>…</span></a>' .
					'<div class="sprocket-features-content-bottom"></div>' .
				'</div>' .
			'</li>';
}
function get_slider_nav($count) {
	$html = '<div class="sprocket-features-arrows">' .
				'<span class="arrow next" data-slideshow-next><span>›</span></span>' .
				'<span class="arrow prev" data-slideshow-previous><span>‹</span></span>' .
			'</div>';

	$html .= '<div class="sprocket-features-pagination">';
	$html .= '<ul>';
	for ($i = 1; $i <= $count; $i++) {
		$html .= '<li'.($i === 1 ? ' class="active"' : '').' data-slideshow-pagination="'.$i.'"><span>'.$i.'</span></li>';
	}
	$html .= '</ul>';
	$html .= '</div>';
	return $html;
}
function get_slider() {
	$html = '<div class="sprocket-features layout-slideshow" data-slideshow="288">';
	$html .= '<ul class="sprocket-features-img-list">';
	$items = get_slider_items();
	$i = 1;
	foreach ($items as $item) {
		$img = TEMPLATE_URL . '/assets/img/slider/' . $item['img'];
		$html .= get_single_slider_html($i, $item['href'], $img, $item['title'], $item['text']);
		$i++;
	}
	$html .= '</ul>';
	$html .= get_slider_nav(count($items));
	$html .= '</div>';
	echo $html;
}
/**
 * Услуги
 * @param $items
 * @return string
 */
function get_services_by_item_html($items) {
	$html = '';
	foreach ($items as $item) {
		$src = TEMPLATE_URL . '/assets/img/' . $item['img'];
		$link = SITE_URL . '/' . $item['href'];
		$html .= '<td align="center" valign="middle"><a href="'.$link.'"><img src="'.$src.'" alt="" width="280" height="200" style="margin-right: 5px;"></a></td>';
	}
	return $html;
}
function get_services() {
	$html = '';
	$items = get_services_items();
	$several_arr = array_chunk($items, 4);
	foreach ($several_arr as $arr) {
		$html .= '<tr>' . get_services_by_item_html($arr) . '</tr>';
	}
	echo $html;
}
