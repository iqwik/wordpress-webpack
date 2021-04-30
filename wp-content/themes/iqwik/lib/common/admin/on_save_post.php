<?php
add_action('save_post_chapter', function($post_id)
{
	// проверка
	if (!wp_verify_nonce($_POST['_wpnonce'], __FILE__)) return false;
	// выходим если это автосохранение
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return false;
	// выходим если юзер не имеет право редактировать запись
	if (!current_user_can('edit_post', $post_id)) return false;

	$content = htmlspecialchars($_POST['content']);
	wp_update_post([ 'ID' => $post_id, 'content' => $content, 'post_content' => $content ]);
	return $post_id;
}, 0);
