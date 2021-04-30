<?php
/**
 * Мета-description (для seo)
 */
function get_description()
{
	$cat_desc = is_single() ? get_the_title() . " | " . get_bloginfo('description') : category_description();
	return $cat_desc ? preg_replace('/^\s*(<p>)|(<\/p>)|\s+$/','',$cat_desc) : get_bloginfo('description');
}
/**
 * Главное меню - desktop
 */
function get_menu() {
	$nav_links = nav_links_tree();
	$html = '<ul class="menutop level1">';
	$html .= drill_items($nav_links, true, 0);
	$html .= '</ul>';
	echo $html;
}
function get_children_links($links, $parent_level, $parent_id) {
	$html = '<div class="fusion-submenu-wrapper level'.$parent_level.' menutab'.$parent_id.'" style="width:230px">';
	$html .= '<ul class="level'.$parent_level.'" style="width:230px">';
	$html .= drill_items($links, false, $parent_id);
	$html .= '</ul>';
	$html .= '<div class="drop-bot"></div>';
	$html .= '</div>';
	return $html;
}
function get_single_link_html($id, $href, $text, $daddy_class, $daddy_icon) {
	$class_name = 'menutab'.$id.' '.$daddy_class.' item bullet';
	$html = $href === '#' || (is_home() && $href == '/')
		? '<span class="'.$class_name.' nolink">'
		: '<a class="'.$class_name.'" href="'.SITE_URL.($href == '/' ? '' : '/'.$href).'">';
	$html .= '<span class="menu-shadow"></span>';
	$html .= '<span class="menu-overlay"><span></span></span>';
	$html .= '<span class="menu-text">'.$text.$daddy_icon.'</span>';
	$html .= $href === '#' ? '</span>' : '</a>';
	return $html;
}
function drill_items($items, $root_level = false, $parent_id = null) {
	$html = '';
	foreach ($items as $link) {
		$root_class = '';
		if ($root_level) {
			$root_class = ' parent root';
			if ((is_home() && $link['id'] == 1)) {
				$root_class = ' active root';
			} else {
				global $wp_query;
				$term_slug = null;
				$tax_name = 'chapter_category';
				$cat_path = null;
				$current_page_uri = null;
				if (is_tax()) {
					$term_slug = $wp_query->query_vars['term'];
					$term = get_term_by('slug', $term_slug, $tax_name);
					$cat_path = get_terms_path($term, $tax_name);
					$current_page_uri = $cat_path;
				} elseif (is_single()) {
					$cat_path = $wp_query->query_vars['category_name'];
					$term_slug = $cat_path;
					$current_page_uri = $cat_path . '/' . $wp_query->query_vars['name'];
				}
				list($root_category) = explode('/', $cat_path);
				if (
					($link['href'] == '#' && (isset($link['data-href']) && $link['data-href'] == $root_category))
					|| $link['href'] == $root_category
					|| $link['href'] == $wp_query->query_vars['name']
				) {
					$root_class = ' active root';
					if (isset($link['childs'])) {
						$current_category = nav_links_tree($link['id']);
						define('CURRENT_CATEGORY_TREE', $current_category);
						define('CURRENT_CATEGORY_NAME', $term_slug);
						define('CURRENT_PAGE_URI', $current_page_uri);
					}
				}
			}
			$parent_id++;
		}
		$daddy_class = 'orphan';
		$daddy_icon = '';
		$children = '';
		if (isset($link['childs']) && !empty($link['childs'])) {
			$daddy_class = 'daddy';
			$daddy_icon = '<span class="daddyicon"></span>';
			$children = get_children_links($link['childs'], $link['level'] + 1, $parent_id);
			if (!$root_level) {
				$root_class = ' parent';
			}
		}
		$id = $root_level ? $link['id'] : 0;
		$html .= '<li class="item'.$link['id'].$root_class.'">'. get_single_link_html($id, $link['href'], $link['text'], $daddy_class, $daddy_icon). $children .'</li>';
	}
	return $html;
}
/**
 * Главное меню - mobile
 */
function get_mobile_menu() {
	$nav_links = nav_links_tree();
	$html = '<select data-rt-menu-mobile>';
	$html .= drill_mob_items($nav_links, 1);
	$html .= '</select>';
	echo $html;
}
function drill_mob_items($links, $level) {
	$html = '';
	$symbol = '—';
	$separator = '';
	for ($i = 1; $i < $level; $i++) {
		$separator .= $symbol;
	}
	foreach ($links as $link) {
		$html .= '<option value="'.SITE_URL.($link['href'] == '/' ? '' : '/'.$link['href']).'">'.$separator.' '.$link['text'].'</option>';
		if (isset($link['childs']) && !empty($link['childs'])) {
			$html .= drill_mob_items($link['childs'], $link['level'] + 1);
		}
	}
	return $html;
}
/**
 * Дерево категорий по уровню текущей
 */
function get_category_tree_by_level() {
	$html = '';
	if (defined('CURRENT_PAGE_URI') && defined('CURRENT_CATEGORY_TREE') && defined('CURRENT_CATEGORY_NAME')) {
		$root_cat = CURRENT_CATEGORY_TREE;
		$is_current = $root_cat['href'] == CURRENT_PAGE_URI;
		if (isset($root_cat['childs'])) {
			$html .= expand_category_tree($root_cat['childs'], $is_current);
		}
	}
	echo $html;
}
function expand_category_tree($items, $is_current) {
	$html = '';
	foreach ($items as $item) {
		$active = '';
		$childs = '';
		$node_expanded = false;
		if (!$is_current && isset($item['childs']) && $item['href'] == CURRENT_CATEGORY_NAME) {
			$active = ' active deeper parent';
			$node_expanded = true;
			$childs .= '<ul class="nav-child unstyled small">';
			$childs .= expand_category_tree($item['childs'], $is_current);
			$childs .= '</ul>';
		} elseif (isset($item['childs'])) {
			$active = ' parent';
		}
		if ($item['href'] == CURRENT_PAGE_URI) {
			$active = ' current active';
		}
		$html .= '<li class="item-'.$item['id'].$active.'"><a href="'.SITE_URL.'/'.$item['href'].'">'.$item['text'].'</a>';
		$html .= $node_expanded ? $childs.'</li>' : '</li>';
	}
	return $html;
}
