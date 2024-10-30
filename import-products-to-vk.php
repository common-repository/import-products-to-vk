<?php
/**
 * Plugin Name: Import Products to VK
 * Requires Plugins: woocommerce
 * Plugin URI: https://icopydoc.ru/category/documentation/import-products-to-vk/
 * Description: Plugin for importing products from WooCommerce online store to vk.com group. Helps to increase sales.
 * Version: 0.7.5
 * Requires at least: 4.7
 * Requires PHP: 7.4.0
 * Author: Maxim Glazunov
 * Author URI: https://icopydoc.ru
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: import-products-to-vk
 * Domain Path: /languages
 * Tags: vk, import, products, export, woocommerce
 * WC requires at least: 3.0.0
 * WC tested up to: 9.3.3
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Copyright 2023-2024 (Author emails: djdiplomat@yandex.ru, support@icopydoc.ru)
 */
defined( 'ABSPATH' ) || exit;

$nr = false;
// Check php version
if ( version_compare( phpversion(), '7.4.0', '<' ) ) { // не совпали версии
	add_action( 'admin_notices', function () {
		warning_notice( 'notice notice-error',
			sprintf(
				'<strong style="font-weight: 700;">%1$s</strong> %2$s 7.4.0 %3$s %4$s',
				'Import Products to VK',
				__( 'plugin requires a php version of at least', 'import-products-to-vk' ),
				__( 'You have the version installed', 'import-products-to-vk' ),
				phpversion()
			)
		);
	} );
	$nr = true;
}

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', [] ) ) )
	&& ! ( is_multisite()
		&& array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', [] ) ) )
) {
	add_action( 'admin_notices', function () {
		warning_notice(
			'notice notice-error',
			sprintf(
				'<strong style="font-weight: 700;">Import Products to VK</strong> %1$s',
				__( 'requires WooCommerce installed and activated', 'import-products-to-vk' )
			)
		);
	} );
	$nr = true;
} else {
	// поддержка HPOS
	add_action( 'before_woocommerce_init', function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );
}

/**
 * Print a notice in the admin Plugins page. Usually used in a @hook 'admin_notices'
 * 
 * @since 0.1.0
 * 
 * @param string $class - Optional
 * @param string $message - Optional
 * 
 * @return void
 */
if ( ! function_exists( 'warning_notice' ) ) {
	function warning_notice( $class = 'notice', $message = '' ) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
}

// Define constants
define( 'IP2VK_PLUGIN_VERSION', '0.7.5' );

$upload_dir = wp_get_upload_dir();
// https://site.ru/wp-content/uploads
define( 'IP2VK_SITE_UPLOADS_URL', $upload_dir['baseurl'] );

// /home/site.ru/public_html/wp-content/uploads
define( 'IP2VK_SITE_UPLOADS_DIR_PATH', $upload_dir['basedir'] );

// https://site.ru/wp-content/uploads/import-products-to-vk
define( 'IP2VK_PLUGIN_UPLOADS_DIR_URL', $upload_dir['baseurl'] . '/import-products-to-vk' );

// /home/site.ru/public_html/wp-content/uploads/import-products-to-vk
define( 'IP2VK_PLUGIN_UPLOADS_DIR_PATH', $upload_dir['basedir'] . '/import-products-to-vk' );
unset( $upload_dir );

// https://site.ru/wp-content/plugins/import-products-to-vk/
define( 'IP2VK_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/import-products-to-vk/
define( 'IP2VK_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

// /home/p135/www/site.ru/wp-content/plugins/import-products-to-vk/import-products-to-vk.php
define( 'IP2VK_PLUGIN_MAIN_FILE_PATH', __FILE__ );

// import-products-to-vk - псевдоним плагина
define( 'IP2VK_PLUGIN_SLUG', wp_basename( dirname( __FILE__ ) ) );

// import-products-to-vk/import-products-to-vk.php - полный псевдоним плагина (папка плагина + имя главного файла)
define( 'IP2VK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// $nr = apply_filters('IP2VK_f_nr', $nr);

// load translation
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'import-products-to-vk', false, dirname( IP2VK_PLUGIN_BASENAME ) . '/languages/' );
} );

if ( false === $nr ) {
	unset( $nr );
	require_once IP2VK_PLUGIN_DIR_PATH . '/packages.php';
	register_activation_hook( __FILE__, [ 'IP2VK', 'on_activation' ] );
	register_deactivation_hook( __FILE__, [ 'IP2VK', 'on_deactivation' ] );
	add_action( 'plugins_loaded', [ 'IP2VK', 'init' ], 10 ); // активируем плагин
	define( 'IP2VK_ACTIVE', true );
}