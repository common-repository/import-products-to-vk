<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Traits for skip products
 *
 * @package			Import Products to VK
 * @subpackage		
 * @since			0.1.0
 * 
 * @version			0.3.0 (04-05-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 *
 * @return	array	!! проверить, насколько в принципе нужен возврат пустого массива
 *
 * @depends			class:		
 *					methods: 	get_product
 *								get_feed_id
 *								get_feed_category_id
 *					variable:	
 *					methods:	
 *					functions:	
 *					constants:	
 */

trait IP2VK_T_Common_Skips {
	public function get_skips() {
		$product = $this->get_product();
		$skip_flag = false;

		if ( null == $product ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'There is no product with this ID', 'import-products-to-vk' ),
				'post_id' => $product->get_id(),
				'file' => 'trait-ip2vk-t-common-skips.php',
				'line' => __LINE__
			] );
			return [];
		}

		if ( $product->is_type( 'grouped' ) ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'Product is grouped', 'import-products-to-vk' ),
				'post_id' => $product->get_id(),
				'file' => 'trait-ip2vk-t-common-skips.php',
				'line' => __LINE__
			] );
			return [];
		}

		if ( $product->get_status() !== 'publish' ) {
			$this->add_skip_reason( [ 
				'reason' => sprintf( '%s "%s"',
					__( 'The product status/visibility is', 'import-products-to-vk' ),
					$product->get_status()
				),
				'post_id' => $product->get_id(),
				'file' => 'trait-ip2vk-t-common-skips.php',
				'line' => __LINE__
			] );
			return [];
		}

		// пропуск товаров по флагу
		$skip_flag = apply_filters( 'ip2vk_f_skip_flag',
			$skip_flag,
			[ 
				'product' => $product,
				'catid' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		if ( false !== $skip_flag ) {
			$this->add_skip_reason( [ 
				'reason' => $skip_flag,
				'post_id' => $product->get_id(),
				'file' => 'trait-ip2vk-t-common-skips.php',
				'line' => __LINE__
			] );
			return [];
		}

		// пропуск товаров, которых нет в наличии
		$skip_missing_products = common_option_get( 'skip_missing_products', false, $this->get_feed_id(), 'ip2vk' );
		if ( $skip_missing_products == 'enabled' ) {
			if ( false == $product->is_in_stock() ) {
				$this->add_skip_reason( [ 
					'reason' => __( 'Skip missing products', 'import-products-to-vk' ),
					'post_id' => $product->get_id(),
					'file' => 'trait-ip2vk-t-common-skips.php',
					'line' => __LINE__
				] );
				return [];
			}
		}

		// пропускаем товары на предзаказ
		$skip_backorders_products = common_option_get( 'skip_backorders_products', false, $this->get_feed_id(), 'ip2vk' );
		if ( $skip_backorders_products == 'enabled' ) {
			if ( true == $product->get_manage_stock() ) { // включено управление запасом  
				if ( ( $product->get_stock_quantity() < 1 ) && ( $product->get_backorders() !== 'no' ) ) {
					$this->add_skip_reason( [ 
						'reason' => __( 'Skip backorders products', 'import-products-to-vk' ),
						'post_id' => $product->get_id(),
						'file' => 'trait-ip2vk-t-common-skips.php',
						'line' => __LINE__
					] );
					return [];
				}
			} else {
				if ( $product->get_stock_status() !== 'instock' ) {
					$this->add_skip_reason( [ 
						'reason' => __( 'Skip backorders products', 'import-products-to-vk' ),
						'post_id' => $product->get_id(),
						'file' => 'trait-ip2vk-t-common-skips.php',
						'line' => __LINE__
					] );
					return [];
				}
			}
		}

		if ( $product->is_type( 'variable' ) ) {
			$offer = $this->offer;

			// пропуск вариаций, которых нет в наличии
			if ( $skip_missing_products == 'enabled' ) {
				if ( $offer->is_in_stock() == false ) {
					$this->add_skip_reason( [ 
						'offer_id' => $offer->get_id(),
						'reason' => __( 'Skip missing products', 'import-products-to-vk' ),
						'post_id' => $product->get_id(),
						'file' => 'traits-ip2vk-variable.php',
						'line' => __LINE__
					] );
					return [];
				}
			}

			// пропускаем вариации на предзаказ
			if ( $skip_backorders_products == 'enabled' ) {
				if ( true == $offer->get_manage_stock() ) { // включено управление запасом
					if ( ( $offer->get_stock_quantity() < 1 ) && ( $offer->get_backorders() !== 'no' ) ) {
						$this->add_skip_reason( [ 
							'offer_id' => $offer->get_id(),
							'reason' => __( 'Skip backorders products', 'import-products-to-vk' ),
							'post_id' => $product->get_id(),
							'file' => 'traits-ip2vk-variable.php',
							'line' => __LINE__
						] );
						return [];
					}
				}
			}

			// пропуск вариативных товаров по флагу
			$skip_flag = apply_filters( 'ip2vk_f_skip_flag_variable',
				$skip_flag,
				[ 
					'product' => $product,
					'offer' => $offer,
					'catid' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			if ( false !== $skip_flag ) {
				$this->add_skip_reason( [ 
					'offer_id' => $offer->get_id(),
					'reason' => $skip_flag,
					'post_id' => $product->get_id(),
					'file' => 'trait-ip2vk-t-common-skips.php',
					'line' => __LINE__
				] );
				return [];
			}
		}
	}
}