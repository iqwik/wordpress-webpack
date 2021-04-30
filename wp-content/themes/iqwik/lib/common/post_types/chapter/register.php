<?php
if (!function_exists('chapter_type'))
{
	function chapter_type()
	{
		$post_type = 'chapter';
		register_taxonomy($post_type.'_category', [ $post_type ], [
			'label'                 => 'Категории',
			'labels'                => [
				'name'              => 'Категории',
				'singular_name'     => 'Категория',
				'search_items'      => 'Искать категорию',
				'all_items'         => 'Все категории',
				'parent_item'       => 'Родительская категория',
				'parent_item_colon' => 'Родительская категория:',
				'edit_item'         => 'Ред. категорию',
				'update_item'       => 'Обновить',
				'add_new_item'      => 'Добавить',
				'new_item_name'     => 'Новая категория',
				'menu_name'         => 'Категории',
			],
			'description'           => 'Категории',
			'public'                => true,
			'hierarchical'          => true,
//            'has_archive'           => false,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'show_tagcloud'         => false,
//            'show_admin_column'     => true,
		]);
		register_post_type($post_type, [
			'labels'             => [
				'name'                	=> __( 'Публикация', $post_type ),
				'singular_name'       	=> __( 'Публикация', $post_type ),
				'menu_name'           	=> __( 'Публикации', $post_type ),
				'parent_item_colon'   	=> __( 'Род.:', $post_type ),
				'all_items'           	=> __( 'Все', $post_type ),
				'view_item'           	=> __( 'Просмотр публикации на сайте', $post_type ),
				'add_new_item'        	=> __( 'Новая публикация', $post_type ),
				'add_new'             	=> __( 'Добавить', $post_type ),
				'edit_item'           	=> __( 'Редактировать', $post_type ),
				'update_item'         	=> __( 'Обновить', $post_type ),
				'search_items'        	=> __( 'Искать', $post_type ),
				'not_found'           	=> __( 'не найдено', $post_type ),
				'not_found_in_trash'  	=> __( 'В корзине пусто', $post_type ),
				'featured_image'    	=> __( 'Изображение', $post_type ),
				'set_featured_image' 	=> __( 'Загрузить изображение', $post_type ),
				'remove_featured_image' => __( 'Удалить изображение', $post_type ),
				'use_featured_image'    => __( 'Использовать как основное изображение', $post_type )
			],
			'public'             => true,
			'supports'           => [ 'title', 'thumbnail', 'editor' ],
			'has_archive'        => true,
			'hierarchical'       => true,
//            'publicly_queryable' => false, // если хотим запретить создавать отдельную страницу
			'taxonomies'         => [ $post_type ],
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_position'      => 3,
			'menu_icon'          => 'dashicons-archive',
			'capability_type'    => 'post',
		]);

	}
	add_action('init', 'chapter_type');
}
