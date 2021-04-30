<?php
/**
 * Author: Artem Zubarev *
 **/

/******************************************************** *************************************************************/
/****************************************************** CONSTs ********************************************************/
/******************************************************** *************************************************************/
define('TEMPLATE_URL', get_bloginfo('template_url'));
define('SITE_URL', get_site_url());
define('SITE_NAME', get_bloginfo('name'));
define('ADMIN_EMAIL', get_option('admin_email'));
include_once 'version.php';
define('TEMPLATE_PARTS', 'templates/_parts/');
$tpl_top = [
	'greenhouses-fabric' => true,
	'drip-irrigation' => true,
];
// set into wp-config.php
//define( 'WP_POST_REVISIONS', 0 ); // disable revision
//// SMTP
//define( 'SMTP_USER', 'example@mail.com' );
//define( 'SMTP_PASS', '123456' );
//define( 'SMTP_HOST', 'smtp.mail.com' );
//define( 'SMTP_FROM', 'example@mail.com' );
//define( 'SMTP_NAME', 'Website' );
//define( 'SMTP_PORT', 465 );
//define( 'SMTP_SECURE', 'ssl' );
//define( 'SMTP_AUTH', true );
//define( 'SMTP_DEBUG', 0 ); // Levels (0, 1, 2)

/**************************************************** SMTP ************************************************************/
function send_smtp_phpmailer($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = SMTP_AUTH;
    $phpmailer->Port       = SMTP_PORT;
    $phpmailer->Username   = SMTP_USER;
    $phpmailer->Password   = SMTP_PASS;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->From       = SMTP_FROM;
    $phpmailer->FromName   = SMTP_NAME;
    $phpmailer->SMTPDebug  = SMTP_DEBUG;
}
add_action( 'phpmailer_init', 'send_smtp_phpmailer' );
/******************************************* prepare WORDPRESS ********************************************************/
include_once TEMPLATEPATH . '/lib/common/index.php';
/******************************************* Custom functions *********************************************************/
include_once TEMPLATEPATH . '/lib/pages/index.php';
/**************************************************** Ajax ************************************************************/
// регистрируем основную ссылку на ajax
add_action('wp_footer', function() {
?>
<script type="text/javascript">window.wp_data = <?php echo json_encode(['ajax_url' => admin_url('admin-ajax.php')]);?>;</script>
<?php
});
include_once  TEMPLATEPATH . '/lib/ajax/feedback.php';
