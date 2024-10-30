<?php defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
if ( is_multisite() ) {
	delete_blog_option( get_current_blog_id(), 'ip2vk_version' );
	delete_blog_option( get_current_blog_id(), 'ip2vk_keeplogs' );
	delete_blog_option( get_current_blog_id(), 'ip2vk_disable_notices' );
	delete_blog_option( get_current_blog_id(), 'ip2vk_groups_content' );

	delete_blog_option( get_current_blog_id(), 'ip2vk_settings_arr' );
	// delete_blog_option(get_current_blog_id(), 'ip2vk_registered_groups_arr');
} else {
	delete_option( 'ip2vk_version' );
	delete_option( 'ip2vk_keeplogs' );
	delete_option( 'ip2vk_disable_notices' );
	delete_option( 'ip2vk_groups_content' );

	delete_option( 'ip2vk_settings_arr' );
	// delete_option('ip2vk_registered_groups_arr');
}