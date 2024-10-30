<?php
/**
 * Interface Hoocked
 *
 * @package                 Import Products to VK
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 0.7.5 (25-10-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     https://vk.com/dev/market
 * 
 * @param        
 *
 * @depends                 classes:    IP2VK_Error_Log
 *                                      IP2VK_Api
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                          constants:  
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

final class IP2VK_Interface_Hoocked {

	/**
	 * Interface Hoocked.
	 */
	public function __construct() {
		$this->init_hooks();
		$this->init_classes();
	}

	/**
	 * Initialization hooks
	 * 
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'save_post', [ $this, 'save_post_product' ], 50, 3 );
		add_action( 'woocommerce_product_duplicate', [ $this, 'product_duplicate' ], 50, 3 );
		/* Мета-поля для категорий товаров */
		add_action( "product_cat_edit_form_fields", [ $this, 'add_meta_product_cat' ], 10, 1 );
		add_action( 'edited_product_cat', [ $this, 'save_meta_product_cat' ], 10, 1 );
		add_action( 'create_product_cat', [ $this, 'save_meta_product_cat' ], 10, 1 );

		// https://wpruse.ru/woocommerce/custom-fields-in-products/
		// https://wpruse.ru/woocommerce/custom-fields-in-variations/
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'added_wc_tabs' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'added_tabs_panel' ], 10, 1 );
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'shop_add_variable_custom_fields' ], 10, 3 );

		add_filter( 'ip2vk_f_save_if_empty', [ $this, 'flag_save_if_empty' ], 10, 2 );

		add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_to_product_sync_info' ], 99, 1 );
		add_action( 'woocommerce_variation_options', [ $this, 'add_to_product_variation_sync_info' ], 99, 3 );
	}

	/**
	 * Initialization classes
	 * 
	 * @return void
	 */
	public function init_classes() {
		return;
	}

	/**
	 * Позволяет добавить дополнительные поля на страницу редактирования элементов таксономии (термина).
	 * Function for `(taxonomy)_edit_form_fields` action-hook.
	 * 
	 * @param WP_Term $tag Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function add_meta_product_cat( $term ) {
		$obj = new IP2VK_Api();
		$result = $obj->get_vk_categories();
		if ( true == $result['status'] ) :
			$vk_categories_obj = $result['categories_obj'];
			if ( property_exists( $vk_categories_obj, 'items' ) ) :
				$vk_categories_arr = $vk_categories_obj->items;
				$ip2vk_vk_category_id = esc_attr( get_term_meta( $term->term_id, 'ip2vk_vk_category_id', true ) );
				?>
				<tr class="form-field term-parent-wrap">
					<th scope="row" valign="top">
						<label>
							<?php esc_html_e( 'Category VK.com', 'import-products-to-vk' ); ?>
						</label>
					</th>
					<td>
						<select name="ip2vk_cat_meta[ip2vk_vk_category_id]" id="ip2vk_vk_category_id">
							<option value="" <?php selected( $ip2vk_vk_category_id, '' ); ?> disabled="disabled">
								<?php
								esc_html_e( 'You must select', 'import-products-to-vk' );
								?>!
							</option>
							<?php $this->get_vk_categories_view( $vk_categories_arr, $ip2vk_vk_category_id ); ?>
						</select><br />
						<p class="description">
							<?php esc_html_e( 'Required element', 'import-products-to-vk' ); ?> <strong>category_id</strong>
						</p>
					</td>
				</tr>
				<?php
			endif;
		endif;
	}

	/**
	 * Сохранение данных в БД. Function for `create_(taxonomy)` and `edited_(taxonomy)` action-hooks.
	 * 
	 * @param int $term_id
	 * 
	 * @return void
	 */
	public function save_meta_product_cat( $term_id ) {
		if ( ! isset( $_POST['ip2vk_cat_meta'] ) ) {
			return;
		}
		$ip2vk_cat_meta = array_map( 'sanitize_text_field', $_POST['ip2vk_cat_meta'] );
		foreach ( $ip2vk_cat_meta as $key => $value ) {
			if ( empty( $value ) ) {
				delete_term_meta( $term_id, $key );
				continue;
			}
			update_term_meta( $term_id, $key, sanitize_text_field( $value ) );
		}
		return;
	}

	/**
	 * Summary of get_vk_categories_view2
	 * 
	 * @param array $vk_categories_arr
	 * @param int|string $ip2vk_vk_category_id
	 * @param string $sep
	 * 
	 * @return void
	 */
	public function get_vk_categories_view( $vk_categories_arr, $ip2vk_vk_category_id, $sep = '' ) {
		// сортируем массив по алфавиту
		usort( $vk_categories_arr, function ($a, $b) {
			if ( $a->name > $b->name ) {
				return 1;
			} elseif ( $a->name < $b->name ) {
				return -1;
			}
			return 0;
		} );
		for ( $i = 0; $i < count( $vk_categories_arr ); $i++ ) {
			printf( '<option value="%1$s" %2$s>%3$s (vk_id = %1$s)</option>',
				esc_attr( $vk_categories_arr[ $i ]->id ),
				selected( $ip2vk_vk_category_id, $vk_categories_arr[ $i ]->id, false ),
				esc_html( $sep . $vk_categories_arr[ $i ]->name )
			);
			if ( ! empty( $vk_categories_arr[ $i ]->children ) ) {
				$this->get_vk_categories_view( $vk_categories_arr[ $i ]->children, $ip2vk_vk_category_id, $sep . '--' );
			}
		}
	}

	/**
	 * Summary of added_wc_tabs
	 * 
	 * @param array $tabs
	 * 
	 * @return array
	 */
	public function added_wc_tabs( $tabs ) {
		$tabs['ip2vk_special_panel'] = [ 
			'label' => __( 'Import Products to VK', 'import-products-to-vk' ), // название вкладки
			'target' => 'ip2vk_added_wc_tabs', // идентификатор вкладки
			'class' => [ 'hide_if_grouped' ], // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 70 // приоритет вывода
		];
		return $tabs;
	}

	/**
	 * Summary of added_tabs_panel
	 * 
	 * @return void
	 */
	public function added_tabs_panel() {
		global $post; ?>
		<div id="ip2vk_added_wc_tabs" class="panel woocommerce_options_panel">
			<?php do_action( 'ip2vk_before_options_group', $post ); ?>
			<div class="options_group">
				<h2>
					<strong>
						<?php esc_html_e( 'Individual product settings for export to VK.com', 'import-products-to-vk' ); ?>
					</strong>
				</h2>
				<?php
				do_action( 'ip2vk_prepend_options_group', $post );
				do_action( 'ip2vk_append_options_group', $post );

				woocommerce_wp_text_input( [ 
					'id' => '_ip2vk_prod_id_on_vk',
					'label' => __( 'Product ID on VK.com', 'import-products-to-vk' ),
					'description' => sprintf( '%s. %s',
						__(
							'This field is filled in automatically during the product synchronization process',
							'import-products-to-vk'
						),
						__( 'It is recommended to change its value only in special cases', 'import-products-to-vk' )
					),
					'desc_tip' => 'true',
					'type' => 'text'
				] );
				?>
			</div>
			<?php do_action( 'ip2vk_after_options_group', $post ); ?>
		</div>
		<?php
	}

	/**
	 * Function for `woocommerce_product_after_variable_attributes` action-hook.
	 * 
	 * @param  $loop           
	 * @param  $variation_data 
	 * @param  $variation      
	 *
	 * @return void
	 */
	public function shop_add_variable_custom_fields( $loop, $variation_data, $variation ) {
		woocommerce_wp_text_input( [ 
			'id' => '_ip2vk_prod_id_on_vk[' . $variation->ID . ']',
			'custom_attributes' => [],
			'value' => get_post_meta( $variation->ID, '_ip2vk_prod_id_on_vk', true ),
			'label' => __( 'Product ID on VK.com', 'import-products-to-vk' ),
			'description' => sprintf( '%s. %s',
				__(
					'This field is filled in automatically during the product synchronization process',
					'import-products-to-vk'
				),
				__( 'It is recommended to change its value only in special cases', 'import-products-to-vk' )
			),
			'desc_tip' => 'true',
			'type' => 'text'
		] );
	}

	/**
	 * Сохраняем данные блока, когда пост сохраняется
	 * 
	 * @param int $post_id
	 * @param object $post
	 * @param bool $update (true — это обновление записи; false — это добавление новой записи)
	 * 
	 * @return void
	 */
	public function save_post_product( $post_id, $post, $update ) {
		if ( $post->post_type !== 'product' ) {
			return; // если это не товар вукомерц
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return; // если это ревизия
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return; // если это автосохранение ничего не делаем
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return; // проверяем права юзера
		}

		$post_meta_arr = [ '_ip2vk_prod_id_on_vk' ];
		$post_meta_arr = apply_filters( 'ip2vk_f_post_meta_arr', $post_meta_arr );
		if ( ! empty( $post_meta_arr ) ) {
			$this->save_post_meta( $post_meta_arr, $post_id );
		}

		// если экспорт глобально запрещён
		$syncing_with_vk = common_option_get( 'syncing_with_vk', false, '1', 'ip2vk' );
		if ( $syncing_with_vk === 'disabled' ) {
			new IP2VK_Error_Log( sprintf( 'NOTICE: Не синхроним post_id = %1$s. %2$s; Файл: %3$s; %4$s: %5$s',
				$post_id,
				'Включён глобальный запрет на импорт!',
				'class-ip2vk-interface-hoocked.php',
				__( 'line', 'import-products-to-vk' ),
				__LINE__
			) );
			return;
		}

		usleep( 300000 ); // притормозим на 0,3 секунды старт импорта при сохранении поста
		$api = new IP2VK_Api();
		$answer_arr = $api->product_sync( $post_id );
		if ( true == $answer_arr['status'] ) {
			new IP2VK_Error_Log( sprintf( 'Товара с post_id = %1$s %2$s; Файл: %3$s; %4$s: %5$s',
				$post_id,
				'успешно импортирован',
				'class-ip2vk-interface-hoocked.php',
				__( 'line', 'import-products-to-vk' ),
				__LINE__
			) );
		} else {
			new IP2VK_Error_Log( sprintf( '%1$s post_id = %2$s; Файл: %3$s; %4$s: %5$s',
				'Ошибка добавления товара с',
				$post_id,
				'class-ip2vk-interface-hoocked.php',
				__( 'line', 'import-products-to-vk' ),
				__LINE__
			) );
			new IP2VK_Error_Log( $answer_arr );
		}
	}

	/**
	 * Удаляем метаполе о синхронизации с вк, если мы в админке дублируем товар.
	 * Function for `woocommerce_product_duplicate` action-hook.
	 * 
	 * @param WC_Product $duplicate
	 * @param WC_Product $product
	 *
	 * @return void
	 */
	public function product_duplicate( $duplicate, $product ) {

		// для простых товаров
		if ( get_post_meta( $duplicate->get_id(), '_ip2vk_prod_id_on_vk', true ) !== '' ) {
			delete_post_meta( $duplicate->get_id(), '_ip2vk_prod_id_on_vk' );
		}

		// для вариативных
		if ( $duplicate->is_type( 'variable' ) ) {
			$variation_ids = $duplicate->get_children();
			for ( $i = 0; $i < count( $variation_ids ); $i++ ) {
				$offer_id = $variation_ids[ $i ];
				delete_post_meta( $offer_id, '_ip2vk_prod_id_on_vk' );
			}
		}

	}

	/**
	 * Save post_meta
	 * 
	 * @param array $post_meta_arr
	 * @param int $post_id
	 * 
	 * @return void
	 */
	private function save_post_meta( $post_meta_arr, $post_id ) {
		for ( $i = 0; $i < count( $post_meta_arr ); $i++ ) {
			$meta_name = $post_meta_arr[ $i ];
			if ( isset( $_POST[ $meta_name ] ) ) {
				if ( empty( $_POST[ $meta_name ] ) ) {
					delete_post_meta( $post_id, $meta_name );
				} else {
					update_post_meta( $post_id, $meta_name, sanitize_text_field( $_POST[ $meta_name ] ) );
				}
			}
		}
	}

	/**
	 * Флаг для того, чтобы работало сохранение настроек если мультиселект пуст
	 * 
	 * @param string $save_if_empty
	 * @param array $args_arr
	 * 
	 * @return string
	 */
	public function flag_save_if_empty( $save_if_empty, $args_arr ) {
		// if ( ! empty( $_GET ) && isset( $_GET['tab'] ) && $_GET['tab'] === 'main_tab' ) {
		if ( $args_arr['opt_name'] === 'params_arr' ) {
			$save_if_empty = 'empty_arr';
		}
		// }
		return $save_if_empty;
	}

	/**
	 * Function for `woocommerce_product_options_general_product_data` action-hook.
	 * 
	 * @return void
	 */
	public function add_to_product_sync_info() {
		global $product, $post;

		if ( get_post_meta( $post->ID, '_ip2vk_prod_id_on_vk', true ) == '' ) {
			$product_prod_id_on_vk = '';
		} else {
			$product_prod_id_on_vk = get_post_meta( $post->ID, '_ip2vk_prod_id_on_vk', true );
		}

		/**
		 * Выводит в админке ссылку на импортированный и опубликованный в OZON товар.
		 */
		if ( ! empty( $product_prod_id_on_vk ) ) {
			printf( '</p><p class="form-row form-row-full">%1$s. %2$s: "%3$s"<br/>
				<strong>%4$s</strong>: <a href="%5$s-%6$s?w=product-%6$s_%7$s" target="_blank">%5$s-%6$s?w=product-%6$s_%7$s</a>', // ! не закрывать <p>
				esc_html__( 'The product was imported to the VK', 'import-products-to-vk' ),
				esc_html__( 'His ID on VK', 'import-products-to-vk' ),
				esc_html__( $product_prod_id_on_vk ),
				esc_html__( 'Edit product on VK', 'import-products-to-vk' ),
				'https://vk.com/market',
				esc_attr( common_option_get( 'group_id', false, '1', 'ip2vk' ) ),
				esc_attr( get_post_meta( $post->ID, '_ip2vk_prod_id_on_vk', true ) )
			);
		}
	}

	/**
	 * Function for `woocommerce_variation_options` action-hook.
	 * 
	 * @param int $i Position in the loop
	 * @param array $variation_data Variation data
	 * @param WP_Post $variation Post data
	 *
	 * @return void
	 */
	function add_to_product_variation_sync_info( $i, $variation_data, $variation ) {
		if ( get_post_meta( $variation->ID, '_ip2vk_prod_id_on_vk', true ) == '' ) {
			$product_prod_id_on_vk = '';
		} else {
			$product_prod_id_on_vk = get_post_meta( $variation->ID, '_ip2vk_prod_id_on_vk', true );
		}

		/**
		 * Выводит в админке ссылку на импортированный и опубликованный в OZON товар.
		 */
		if ( ! empty( $product_prod_id_on_vk ) ) {
			printf( '</p><p class="form-row form-row-full">%1$s. %2$s: "%3$s"<br/>
				<strong>%4$s</strong>: <a href="%5$s-%6$s?w=product-%6$s_%7$s" target="_blank">%5$s-%6$s?w=product-%6$s_%7$s</a>', // ! не закрывать <p>
				esc_html__( 'The variation of product was imported to the VK', 'import-products-to-vk' ),
				esc_html__( 'His ID on VK', 'import-products-to-vk' ),
				esc_html__( $product_prod_id_on_vk ),
				esc_html__( 'Edit variation on VK', 'import-products-to-vk' ),
				'https://vk.com/market',
				esc_attr( common_option_get( 'group_id', false, '1', 'ip2vk' ) ),
				esc_attr( get_post_meta( $variation->ID, '_ip2vk_prod_id_on_vk', true ) )
			);
		}
	}

} // end class IP2VK_Interface_Hoocked