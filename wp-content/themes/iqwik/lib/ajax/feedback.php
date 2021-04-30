<?php
function send_feedback() {
	$result = [ 'status' => 500, 'error' => ':(' ];
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce($_POST['send_feedback_nonce'], 'send_feedback')) {

		$fio = isset($_POST['fio']) ? htmlspecialchars(strip_tags($_POST['fio'])) : '';
		$email = isset($_POST['email']) ? htmlspecialchars(strip_tags($_POST['email'])) : '';
		$tel = isset($_POST['tel']) ? htmlspecialchars(strip_tags($_POST['tel'])) : '';
		$comment = isset($_POST['comment']) ? htmlspecialchars(strip_tags($_POST['comment'])) : '';
		$city = isset($_POST['city']) ? htmlspecialchars(strip_tags($_POST['city'])) : '';

		if (!empty($fio) && !empty($email)) {
			$subject = 'Новый запрос';
			$content = "Клиент: {$fio}\n";
			$content .= "E-mail: {$email}\n";
			$content .= "Телефон: {$tel}\n";
			$content .= "Комментарий: {$comment}\n";
			if (!empty($city)) {
				$content .= "Страна/Город: {$city}\n";
			}
			$content .= 'Дата заявки: '.date('d.m.Y в H:i:s')."\n";
			if (wp_mail(ADMIN_EMAIL, $subject, $content)) {
				$result['status'] = 200;
				$result['error'] = 0;
			} else {
				$result['error'] = 'Ошибка при отправке email';
			}
		} else {
			$result['error'] = 'что-то не так с ФИО / Email';
		}
	}
	wp_send_json($result);
	die();
}
add_action('wp_ajax_send_feedback', 'send_feedback');
add_action('wp_ajax_nopriv_send_feedback', 'send_feedback');
