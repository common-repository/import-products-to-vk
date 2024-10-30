<?php defined( 'ABSPATH' ) || exit;
/**
 * Получает ID первого фида. Используется на случай если get-параметр feed_id не указан
 * 
 * @since 0.6.0
 *
 * @return string feed ID or (string)''
 */
function ip2vk_get_first_feed_id() {
	$ip2vk_settings_arr = univ_option_get( 'ip2vk_settings_arr' );
	if ( ! empty( $ip2vk_settings_arr ) ) {
		return (string) array_key_first( $ip2vk_settings_arr );
	} else {
		return '';
	}
}

/**
 * Получает ID последнего фида
 * 
 * @since 0.6.0
 *
 * @return string feed ID or (string)''
 */
function ip2vk_get_last_feed_id() {
	$ip2vk_settings_arr = univ_option_get( 'ip2vk_settings_arr' );
	if ( ! empty( $ip2vk_settings_arr ) ) {
		return (string) array_key_last( $ip2vk_settings_arr );
	} else {
		return ip2vk_get_first_feed_id();
	}
}