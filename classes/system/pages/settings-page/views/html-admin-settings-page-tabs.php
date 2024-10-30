<?php
/**
 * Print tabs
 * 
 * @version 0.6.0 (24-01-2024)
 * @see     
 * @package 
 * 
 * @param $view_arr['tabs_arr']
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="nav-tab-wrapper" style="margin-bottom: 10px;">
	<?php
	foreach ( $view_arr['tabs_arr'] as $tab => $name ) {
		if ( $tab === $view_arr['tab_name'] ) {
			$class = ' nav-tab-active';
		} else {
			$class = '';
		}
		if ( isset( $_REQUEST['ip2vk_submit_add_new_feed'] ) ) {
			// если нажата кнопка "Добавить фид"
			$nf = '&feed_id=1'; // . ip2vk_get_last_feed_id(); // * пока в плагине 1 фид эту ф-ю можно не юзать
		} else if ( isset( $_GET['feed_id'] ) ) {
			$nf = '&feed_id=' . sanitize_text_field( $_GET['feed_id'] );
		} else {
			$nf = '&feed_id=1'; // . ip2vk_get_first_feed_id(); // * пока в плагине 1 фид эту ф-ю можно не юзать
		}
		printf(
			'<a class="nav-tab%1$s" href="?page=ip2vk-import&tab=%2$s%3$s">%4$s</a>',
			$class, $tab, $nf, $name
		);
	}
	?>
</div>