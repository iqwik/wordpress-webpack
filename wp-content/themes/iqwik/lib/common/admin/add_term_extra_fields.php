<?php
add_action('admin_head', 'hidden_term_description');
function hidden_term_description() {
	print '<style>.term-description-wrap{display:none;}</style>';
}
add_action('chapter_category_add_form_fields', 'chapter_category_extra_field');
add_action ('chapter_category_edit_form_fields', 'chapter_category_extra_field');
function chapter_category_extra_field($term) {
	$term_id = $term->term_id;
	$term_content = get_term_meta($term_id, 'content', 1);
	$term_content = htmlspecialchars_decode($term_content);
	/*?><pre><?php var_dump($term_content);?></pre><?php*/
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta_content">Контент:</label></th>
		<td>
			<?php wp_editor($term_content, 'termmetacontent', [
				'wpautop'           => 0,
				'media_buttons'     => 1,
				'textarea_name'     => 'term_meta_content',
				'textarea_rows'     => 20,
				'tabindex'          => null,
				'editor_css'        => '',
				'editor_class'      => '',
				'teeny'             => 1,
				'dfw'               => 1,
				'tinymce'           => 1,
				'quicktags'         => 1,
				'drag_drop_upload'  => 1
			]);?>
		</td>
	</tr>
	<br />
	<br />
	<?php
}

add_action('create_chapter_category', 'save_chapter_category_extra_fields', 10, 2);
add_action('edited_chapter_category', 'save_chapter_category_extra_fields', 10, 2);
function save_chapter_category_extra_fields($term_id) {
	if (isset($_POST['term_meta_content'])) {
		$term_content = htmlspecialchars($_POST['term_meta_content']);
		update_term_meta($term_id, 'content', $term_content);
	}
}
