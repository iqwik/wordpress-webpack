<?php
include_once TEMPLATEPATH . '/lib/common/prepare_wordpress.php'; // очищаем от лишнего мусора wordpress
include_once TEMPLATEPATH . '/lib/common/import_js_files.php'; // подключение js
include_once TEMPLATEPATH . '/lib/common/post_types/chapter/index.php'; // произвольный тип записи для всех публикация/страниц
include_once TEMPLATEPATH . '/lib/common/admin/add_term_extra_fields.php'; // доп поле с контентом
include_once TEMPLATEPATH . '/lib/common/admin/on_save_post.php'; // доп поле с контентом
