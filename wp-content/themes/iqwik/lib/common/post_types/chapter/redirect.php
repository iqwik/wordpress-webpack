<?php
/**
 * Получить все родительские terms (таксономии)
 */
function get_terms_path($term, $tax_name, $terms = []) {
	$terms[] = $term->slug;
	if ($term->parent > 0) {
		$parent_term = get_term($term->parent, $tax_name);
		return get_terms_path($parent_term, $tax_name, $terms);
	} else {
		return implode('/', array_reverse($terms));
	}
}
/**
 * Редиректы для url вида: /{название_категории}/{название_публикации}
 */

add_filter('post_link', 'true_post_type_permalink', 20, 3);
add_filter('post_type_link', 'true_post_type_permalink', 20, 3);
function true_post_type_permalink($permalink, $post_id, $leavename) {
	$post_type_name = 'chapter';
	$post_type_slug = 'chapter';
	$tax_name = 'chapter_category';
	$post = get_post($post_id);
	// не делаем никаких изменений, если тип записи не соответствует или если URL не содержит ярлык chapter
	if (strpos($permalink, $post_type_slug) === FALSE || $post->post_type != $post_type_name) {
		return $permalink;
	}
	// получаем все категории, к которым принадлежит данная запись
	$termini = wp_get_object_terms($post->ID, $tax_name);
	$terms_path = get_terms_path($termini[0], $tax_name);
	// и делаем перезапись ссылки, только, если публикация находится хотя бы в одной категории,
	// иначе возвращаем ссылку по умолчанию
	if (!is_wp_error($termini) && !empty($termini) && (is_object($termini[0]) || !empty($terms_path))) {
		$permalink = str_replace($post_type_slug, $terms_path, $permalink);
	}
	return $permalink;
}

add_filter( 'term_link', function ($permalink, $term, $taxonomy) {
	$tax_name = 'chapter_category';
	$tax_slug = 'chapter_category';
	if (strpos($permalink, $tax_slug) === FALSE || $taxonomy != $tax_name) {
		return $permalink;
	}
	$terms_path = get_terms_path($term, $tax_name);
	$permalink = str_replace('/' . $tax_slug, '', $terms_path);
	if (!empty($terms_path)) {
		return $permalink;
	}
}, 20, 3);

add_filter('request', 'true_post_type_request', 1, 1);
function true_post_type_request($query) {
	$post_type_name = 'chapter';
	$tax_name = 'chapter_category';
	if (!isset($query['name']) && isset($query['category_name']) && $query['category_name'] != 'contact') {
		$yarlik = $query['category_name'];
		$term = get_term_by('slug', $yarlik, $tax_name);
		if (!empty($term)) {
			unset($query['category_name']);
			$query[$tax_name] = $yarlik;
		}
	} else if (isset($query['category_name']) && $query['category_name'] == 'contact') {
		$yarlik = $query['category_name'];
		$query['page'] = '';
		$query['chapter'] = 'contact';
		$query['post_type'] = 'chapter';
		$query['name'] = $yarlik;
		unset($query['category_name']);
	} else {
	 // $yarlik = $query['attachment']; 	// - если постоянные ссылки = /%postname%/
	 	$yarlik = $query['name']; 			// - если постоянные ссылки = /%category%/%postname%/
		if (($term = get_term_by('slug', $yarlik, $tax_name)) && !empty($term)) {
			unset($query['name']);
			unset($query['category_name']);
			$query[$tax_name] = $yarlik;
		} else {
			global $wpdb;
			$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$yarlik' AND post_type = '$post_type_name'");
			$termini = wp_get_object_terms($post_id, $tax_name);
			if (isset($yarlik) && $post_id && !is_wp_error($termini) && !empty($termini)) {
				if (isset($query['attachment'])) unset($query['attachment']);
				$query[$post_type_name] = $yarlik;
				$query['post_type'] = $post_type_name;
				$query['name'] = $yarlik;
			}
		}
 	}
	return $query;
}
add_action('template_redirect', 'true_post_type_redirect');
function true_post_type_redirect() {
	$post_type_name = 'chapter';
	$post_type_slug = 'chapter';
	$tax_name = 'chapter_category';

	if ($_SERVER['REQUEST_URI'] == '/chapter/contact/') {
		global $wp_rewrite;
		wp_redirect( site_url() . $wp_rewrite->front . 'contact/', 301);
		exit();
	}

	if (is_tax($tax_name)) {
		global $wp_rewrite;

		$redirect_terms = [
			'greenhouses/how-we-make-greenhouses' => 'greenhouses/how-we-make-greenhouses/greenhouses-design',
			'greenhouses/greenhouses-supplies' => 'greenhouses/greenhouses-supplies/mesh-for-greenhouses',
			'greenhouses/projects' => 'greenhouses/projects/almeria-spain',
			'irrigation' => 'irrigation/irrigation-systems',
			'irrigation/irrigation-company' => 'irrigation/irrigation-company/irrigation-design',
			'water/water-recycling' => 'water/water-recycling/ultraviolet-treatment',
			'technology' => 'technology/vertical-farming',
			'technology/climate-control' => 'technology/climate-control/climate-controller',
			'technology/climate-control/ventilation' => 'technology/climate-control/ventilation/forced',
			'technology/climate-control/heating' => 'technology/climate-control/heating/water-based',
			'technology/climate-control/humification' => 'technology/climate-control/humification/cooling',
			'technology/phytosanitary-treat' => 'technology/phytosanitary-treat/humifito',
			'technology/complements' => 'technology/complements/clipser-hanger',
			'technology/complements/clips' => 'technology/complements/clips/crop-support',
			'technology/complements/trolleys' => 'technology/complements/trolleys/cultivation-work-at-height',
			'technology/advice' => 'technology/advice/consulting',
		];

		$terms_path = get_terms_path(get_queried_object(), $tax_name);

		if (isset($redirect_terms[$terms_path])) {
			$terms_path = $redirect_terms[$terms_path];
		}

		$uri = '/' . $terms_path . '/';

		if (!empty($terms_path) && $_SERVER['REQUEST_URI'] != $uri) {
			wp_redirect( site_url() . $wp_rewrite->front . $terms_path . '/', 301);
			exit();
		}
//		if((is_category() && $tax_name == 'category') || (is_tag() && $tax_name == 'post_tag') || is_tax($tax_name)) {
//			wp_redirect(get_term_link(get_queried_object_id(), $tax_name), 301);
//			exit();
//		}
	}

	if (strpos($_SERVER['REQUEST_URI'], $post_type_slug) === FALSE || strpos($_SERVER['REQUEST_URI'], $tax_name) === FALSE) {
		return;
	}

	if (is_singular($post_type_name)) {
		global $post; // $wp_rewrite
		$termini = wp_get_object_terms($post->ID, $tax_name);
		$terms_path = get_terms_path($termini[0], $tax_name);

		?><pre><?php var_dump($termini);?></pre><?php
		?><pre><?php var_dump(strpos($_SERVER['REQUEST_URI'], $tax_name) === FALSE);?></pre><?php

		if (!is_wp_error($termini) && !empty($termini) && (is_object($termini[0]) && !empty($terms_path))) {
			// wp_redirect( site_url() . '/' . $wp_rewrite->front . '/' . $terms_path . '/' . $post->post_name, 301);
			// можно использовать эту строчку, но строго обязательно должен быть установлен первый хук из этой статьи
		 	wp_redirect(get_permalink($post->ID), 301);
			exit();
		}
	}
}
