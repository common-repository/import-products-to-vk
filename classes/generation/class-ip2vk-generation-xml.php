<?php
/**
 * Starts import products
 *
 * @package                 Import Products to VK
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 0.7.4 (12-08-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @return         
 *
 * @depends                 class:      IP2VK_Error_Log
 *                                      IP2VK_Get_Unit
 *                                      IP2VK_Api
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

class IP2VK_Generation_XML {
	/**
	 * Feed ID
	 * @var string
	 */
	protected $feed_id;

	/**
	 * Starts import products using VK.com API
	 * 
	 * @param string $feed_id - Required
	 */
	public function __construct( $feed_id ) {
		$this->feed_id = (string) $feed_id;
	}

	/**
	 * Run import products
	 * 
	 * @return void
	 */
	public function run() {
		$syncing_with_vk = common_option_get( 'syncing_with_vk', false, $this->get_feed_id(), 'ip2vk' );
		if ( $syncing_with_vk === 'disabled' ) {
			common_option_upd( 'status_sborki', '-1', 'no', $this->get_feed_id(), 'ip2vk' );
			new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; %2$s; Файл: %3$s; %4$s: %5$s',
				$this->get_feed_id(),
				'Останавливаем сборку тк включён глобальный запрет на импорт',
				'class-ip2vk-generation-xml.php',
				__( 'line', 'import-products-to-vk' ),
				__LINE__
			) );
		}

		$step_export = (int) common_option_get( 'step_export', false, $this->get_feed_id(), 'ip2vk' );
		$status_sborki = (int) common_option_get( 'status_sborki', false, $this->get_feed_id(), 'ip2vk' );
		// $last_element = (int)common_option_get('last_element', 0, $this->get_feed_id());	
		new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; $status_sborki = %2$s; Файл: %3$s; %4$s: %5$s',
			$this->get_feed_id(),
			$status_sborki,
			'class-ip2vk-generation-xml.php',
			__( 'line', 'import-products-to-vk' ),
			__LINE__
		) );

		switch ( $status_sborki ) {
			case -1: // сборка завершена
				new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; case -1; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
				wp_clear_scheduled_hook( 'ip2vk_cron_sborki', [ $this->get_feed_id() ] );
				break;
			case 1: // импорт категорий
				new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Первый шаг. Импорт категорий. Файл: class-ipytw-import-xml.php; Строка: ' . __LINE__ );
				$behavior_cats = common_option_get( 'behavior_cats', false, $this->get_feed_id(), 'ip2vk' );
				switch ( $behavior_cats ) {
					case 'upd_once':
						new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: $behavior_cats = upd_once. Обновляем категории и выставляем upd_off; Файл: class-ipytw-import-xml.php; Строка: ' . __LINE__ );
						$this->run_api_categories_sync();
						common_option_upd( 'behavior_cats', 'upd_off', 'no', $this->get_feed_id(), 'ip2vk' );
						break;
					case 'upd_on':
						new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: $behavior_cats = upd_on. Обновляем категории; Файл: class-ipytw-import-xml.php; Строка: ' . __LINE__ );
						$this->run_api_categories_sync();
						break;
					case 'upd_off':
						new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; NOTICE: $behavior_cats = upd_off. Категории обновлять не нужно; Файл: class-ipytw-import-xml.php; Строка: ' . __LINE__ );
						break;
					default:
						$this->run_api_categories_sync();
						common_option_upd( 'behavior_cats', 'upd_off', 'no', $this->get_feed_id(), 'ip2vk' );
				}
				common_option_upd( 'status_sborki', '2', 'no', $this->get_feed_id(), 'ip2vk' );
				break;
			default:
				new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; case default; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
				if ( $status_sborki == 2 ) {
					$offset = 0;
				} else if ( $status_sborki == 3 ) {
					$offset = $step_export;
				} else {
					$offset = ( ( $status_sborki - 1 ) * $step_export ) - $step_export;
				}
				$args = [ 
					'post_type' => 'product',
					'post_status' => 'publish',
					'posts_per_page' => $step_export,
					'offset' => $offset,
					'relation' => 'AND',
					'orderby' => 'ID'
				];
				$args = apply_filters( 'ip2vk_f_query_arg', $args, $this->get_feed_id() );
				new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; %2$s $args =>; Файл: %3$s; %4$s: %5$s',
					$this->get_feed_id(),
					'Импорт всех товаров',
					'class-ip2vk-generation-xml.php',
					__( 'line', 'import-products-to-vk' ),
					__LINE__
				) );
				new IP2VK_Error_Log( $args );

				$featured_query = new \WP_Query( $args );
				$prod_id_arr = [];
				if ( $featured_query->have_posts() ) {
					for ( $i = 0; $i < count( $featured_query->posts ); $i++ ) {
						$prod_id_arr[ $i ]['ID'] = $featured_query->posts[ $i ]->ID;
						$prod_id_arr[ $i ]['post_modified_gmt'] = $featured_query->posts[ $i ]->post_modified_gmt;
					}
					wp_reset_query(); /* Remember to reset */
					unset( $featured_query ); // чутка освободим память					
					$this->run_api( $prod_id_arr );
					$status_sborki++;
					new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; status_sborki увеличен на ' . $step_export . ' и равен ' . $status_sborki . '; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
					common_option_upd( 'status_sborki', $status_sborki, 'no', $this->get_feed_id(), 'ip2vk' );
				} else { // если постов нет, останавливаем импорт
					$this->stop();
				}
			// end default
		} // end switch($status_sborki)
		return; // final return from public function phase()
	}

	/**
	 * Stop import
	 * 
	 * @return void
	 */
	public function stop() {
		if ( 'once' === common_option_get( 'run_cron', false, $this->get_feed_id(), 'ip2vk' ) ) {
			// если был одноразовый импорт - переводим переключатель в `отключено`
			common_option_upd( 'run_cron', 'disabled', 'no', $this->get_feed_id(), 'ip2vk' );
			common_option_upd( 'status_cron', 'disabled', 'no', $this->get_feed_id(), 'ip2vk' );
		}
		common_option_upd( 'status_sborki', '-1', 'no', $this->get_feed_id(), 'ip2vk' );
		wp_clear_scheduled_hook( 'ip2vk_cron_sborki', [ $this->get_feed_id() ] );
	}

	public function run_api( $ids_arr ) {
		$api = new IP2VK_Api();
		for ( $i = 0; $i < count( $ids_arr ); $i++ ) {
			$product_id = (int) $ids_arr[ $i ]['ID'];
			$answer_arr = $api->product_sync( $product_id );
			if ( true == $answer_arr['status'] ) {
				new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; товара с $product_id = ' . $product_id . ' успешно импортирован; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
			} else {
				new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; ошибка добавления товара с $product_id = ' . $product_id . '; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
				// new IP2VK_Error_Log( $answer_arr );
			}
		}
	}

	/**
	 * Runs importing categories
	 * 
	 * @return void
	 */
	public function run_api_categories_sync() {
		$api = new IP2VK_Api();
		$product_cat_arr = get_terms( [ 
			'taxonomy' => 'product_cat',
			'orderby' => 'name',
			'hide_empty' => false
		] );
		if ( $product_cat_arr ) {
			new IP2VK_Error_Log( 'FEED № ' . $this->get_feed_id() . '; Категории на сайте есть. Приступим к созданию каталогов; Файл: class-ip2vk-generation-xml.php; Строка: ' . __LINE__ );
			foreach ( $product_cat_arr as $category ) {
				if ( get_term_meta( $category->term_id, 'thumbnail_id', true ) == '' ) {
					$args_arr = [ 
						'category_name' => $category->name
					];
				} else {
					$category_thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$fullsize_path = get_attached_file( $category_thumbnail_id );
					$args_arr = [ 
						'category_name' => $category->name,
						'category_pic_url' => $fullsize_path,
						'category_pic_id' => $category_thumbnail_id
					];
				}
				$answer_arr = $api->category_sync( $category->term_id, $args_arr );
				if ( true == $answer_arr['status'] ) {
					// категория успешно импортирована					
				} else {
					new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; ERROR: %2$s %3$s; Файл: %4$s; %5$s: %6$s',
						$this->get_feed_id(),
						'Не получилось добавить категорию с $category->term_id =',
						$category->term_id,
						'class-ip2vk-generation-xml.php',
						__( 'line', 'import-products-to-vk' ),
						__LINE__
					) );
				}

				/* для тестов раскоментить break ниже */
				// break;
			}
		}
	}

	// проверим, нужно ли отправлять запрос к API при обновлении поста
	public function check_ufup( $post_id ) {
		$ip2vk_ufup = common_option_get( 'syncing_with_vk', false, $this->get_feed_id(), 'ip2vk' );
		if ( $ip2vk_ufup === 'enabled' ) {
			$status_sborki = (int) common_option_get( 'status_sborki', false, $this->get_feed_id(), 'ip2vk' );
			if ( $status_sborki > -1 ) { // если идет сборка фида - пропуск
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Get feed ID
	 * 
	 * @return string
	 */
	protected function get_feed_id() {
		return $this->feed_id;
	}
}