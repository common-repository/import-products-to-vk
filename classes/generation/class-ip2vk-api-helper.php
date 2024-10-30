<?php
/**
 * The class will help you connect your store to VK.com using VK.com API
 *
 * @package                 Import Products to VK
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 0.7.5 (25-10-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    IP2VK_Api_Helper_Simple
 *                                      IP2VK_Api_Helper_Variable
 *                                      IP2VK_Api_Helper_External
 *                                      IP2VK_Error_Log
 *                          trait       
 *                          methods:    
 *                          functions:  
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

final class IP2VK_Api_Helper {
	/**
	 * Feed ID
	 * @var string
	 */
	protected $feed_id;
	/**
	 * @var WC_Product
	 */
	protected $product;
	/**
	 * Product data array
	 * @var array
	 */
	protected $product_data_arr = [];
	/**
	 * Category ID on vk.com
	 * @var string
	 */
	protected $category_id_on_vk = '';
	/**
	 * Product ID on VK.com list array
	 * @var array
	 */
	protected $prod_id_on_vk_list_arr = [];
	/**
	 * Skip reasons array
	 * @var array
	 */
	protected $skip_reasons_arr = [];

	/**
	 * The class will help you connect your store to VK.com using VK.com API
	 */
	public function __construct() {
		$this->feed_id = '1';
	}

	/**
	 * Set product data
	 * 
	 * @param int $product_id
	 * @param string $actions
	 * 
	 * @return void
	 */
	public function set_product_data( $product_id, $actions ) {
		new IP2VK_Error_Log(
			sprintf( 'FEED № %1$s; %2$s %3$s, actions = %4$s; Файл: %4$s; Строка: %5$s',
				$this->get_feed_id(),
				'Устанавливаем данные для товара',
				$product_id,
				$actions,
				'class-ip2vk-api-helper.php',
				__LINE__
			)
		);

		$this->product = wc_get_product( $product_id );
		if ( null == $this->get_product() ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'There is no product with this ID', 'import-products-to-vk' ),
				'post_id' => $product_id,
				'file' => 'class-ip2vk-api-helper.php',
				'line' => __LINE__
			] );
			return;
		}

		$terms_post = get_the_terms( $product_id, 'product_cat' );
		if ( empty( $terms_post ) ) {
			$this->category_id_on_vk = '';
		} else {
			foreach ( $terms_post as $term_cat ) {
				$term_cat_id = $term_cat->term_id;
				$category_id_vk = $this->is_category_exists( $term_cat_id );
				if ( false !== $category_id_vk ) {
					$this->category_id_on_vk = (string) $category_id_vk;
				} else {
					$this->category_id_on_vk = '';
				}
				break;
			}
		}

		if ( $this->get_product()->is_type( 'simple' ) ) {
			$obj = new IP2VK_Api_Helper_Simple( $this->get_product(), $actions, $this->get_feed_id() );
			$this->set_helper_result( $obj, $product_id );
			unset( $obj );
			return;
		} else if ( $this->get_product()->is_type( 'external' ) ) {
			$obj = new IP2VK_Api_Helper_External( $this->get_product(), $actions, $this->get_feed_id() );
			$this->set_helper_result( $obj, $product_id );
			unset( $obj );
			return;
		} else if ( $this->get_product()->is_type( 'variable' ) ) {
			$only_first_variation = common_option_get( 'only_first_variation', false, '1', 'ip2vk' );
			$variations_arr = $this->get_product()->get_available_variations();
			$variation_count = count( $variations_arr );
			$success_flag = false;
			for ( $i = 0; $i < $variation_count; $i++ ) {
				$offer_id = $variations_arr[ $i ]['variation_id'];
				$offer = new WC_Product_Variation( $offer_id ); // получим вариацию
				// если мы УЖЕ импортировали одну вариацию и в настройках включён режим импорта только одной вариации
				if ( true === $success_flag && $only_first_variation === 'enabled'  ) {
					$obj = new IP2VK_Api_Helper_Variable( $this->get_product(), 'skip', $offer, $variation_count, $this->get_feed_id() );
				} else {
					$obj = new IP2VK_Api_Helper_Variable( $this->get_product(), $actions, $offer, $variation_count, $this->get_feed_id() );
				}
				$this->set_helper_result( $obj, $offer_id );
				// если ранее не импортировали ни одной вариации и у нас нет причин пропуска вариации
				if ( false === $success_flag && empty( $obj->get_skip_reasons_arr() ) ) {
					$success_flag = true;
				}
				unset( $obj );
			}
			return;
		} else {
			$this->add_skip_reason( [ 
				'reason' => __( 'The product is not simple, variable or external', 'import-products-to-vk' ),
				'post_id' => $product_id,
				'file' => 'class-ip2vk-api-helper.php',
				'line' => __LINE__
			] );
			return;
		}
	}

	/**
	 * Set helper result
	 * 
	 * @param IP2VK_Api_Helper_Simple | IP2VK_Api_Helper_Variable | IP2VK_Api_Helper_External $obj
	 * @param int $post_id_on_wp
	 * 
	 * @return void
	 */
	public function set_helper_result( $obj, $post_id_on_wp ) {
		if ( ! empty( $obj->get_skip_reasons_arr() ) ) {
			foreach ( $obj->get_skip_reasons_arr() as $value ) {
				array_push( $this->skip_reasons_arr, $value );
			}
		}
		if ( ! empty( $obj->get_result() ) ) {
			array_push( $this->product_data_arr, $obj->get_result() );
			$flag = true;
		} else {
			$flag = false;
		}

		array_push( $this->prod_id_on_vk_list_arr,
			[ 
				'product_id_on_vk' => $obj->get_product_id_on_vk(),
				'post_id_on_wp' => $post_id_on_wp,
				'have_get_result' => $flag
			]
		);
	}

	/**
	 * Get product data array
	 * 
	 * @return array
	 */
	public function get_product_data() {
		return $this->product_data_arr;
	}

	/**
	 * Get category ID on VK.com
	 * 
	 * @return string
	 */
	public function get_category_id_on_vk() {
		return $this->category_id_on_vk;
	}

	/**
	 * Checks whether the photo has been imported to the VK.com
	 * 
	 * @param int $thumb_id - Photo ID on your site
	 * 
	 * @return false|string `false` - import was not; `string` - photo ID on the VK.com
	 */
	public function is_photo_exists( $thumb_id ) {
		if ( get_post_meta( $thumb_id, '_ip2vk_existing_photo_id', true ) == '' ) {
			return false;
		} else {
			return get_post_meta( $thumb_id, '_ip2vk_existing_photo_id', true );
		}
	}

	/**
	 * Checks whether the product has been imported to the VK.com
	 * 
	 * @param int $product_id - Product ID on your site
	 * 
	 * @return false|string `false` - import was not; `string` - product ID on the VK.com
	 */
	public function is_product_exists( $product_id ) {
		if ( get_post_meta( $product_id, '_ip2vk_prod_id_on_vk', true ) == '' ) {
			return false;
		} else {
			return get_post_meta( $product_id, '_ip2vk_prod_id_on_vk', true );
		}
	}

	/**
	 * Checks whether the category has been imported to the VK.com
	 * 
	 * @param int $category_id - Category ID on your site
	 * 
	 * @return false|string `false` - import was not; `string` - category ID on the VK.com
	 */
	public function is_category_exists( $category_id ) {
		$ip2vk_vk_product_category = get_term_meta( $category_id, '_ip2vk_vk_product_category', true );
		if ( $ip2vk_vk_product_category == '' ) {
			return false;
		} else {
			return get_term_meta( $category_id, '_ip2vk_vk_product_category', true );
		}
	}

	/**
	 * Sets information about the synchronization of the photo with the VK.com
	 * 
	 * @param int $thumb_id
	 * @param string $photo_id_on_vk
	 * 
	 * @return void
	 */
	public function set_photo_exists( $thumb_id, $photo_id_on_vk ) {
		update_post_meta( $thumb_id, '_ip2vk_existing_photo_id', $photo_id_on_vk );
		return;
	}

	/**
	 * Sets information about the synchronization of the product with the VK.com
	 * 
	 * @param int $product_id
	 * @param string $product_id_on_vk
	 * 
	 * @return void
	 */
	public function set_product_exists( $product_id, $product_id_on_vk ) {
		update_post_meta( $product_id, '_ip2vk_prod_id_on_vk', $product_id_on_vk );
		return;
	}

	/**
	 * Sets information about the synchronization of the category with the VK.com
	 * 
	 * @param int|string $category_id
	 * @param string $category_id_on_vk
	 * 
	 * @return void
	 */
	public function set_category_exists( $category_id, $category_id_on_vk ) {
		update_term_meta( $category_id, '_ip2vk_vk_product_category', $category_id_on_vk );
		return;
	}

	/**
	 * Sets an array of reasons for skiping an product
	 * 
	 * @param mixed $v
	 * 
	 * @return void
	 */
	public function set_skip_reasons_arr( $v ) {
		$this->skip_reasons_arr[] = $v; // ? может лучше так: array_push( $this->skip_reasons_arr, $v );
	}

	/**
	 * Get skip reasons array
	 * 
	 * @return array
	 */
	public function get_skip_reasons_arr() {
		return $this->skip_reasons_arr;
	}

	/**
	 * Adds the reason for skipping the product (or variation) to the array
	 * 
	 * @param array $reason
	 * 
	 * @return void
	 */
	protected function add_skip_reason( $reason ) {
		if ( isset( $reason['offer_id'] ) ) {
			$reason_string = sprintf(
				'GROUP № %1$s; Вариация (postId = %2$s, offer_id = %3$s) пропущена. Причина: %4$s; Файл: %5$s; %6$s: %7$s',
				$this->feed_id,
				$reason['post_id'],
				$reason['offer_id'],
				$reason['reason'],
				$reason['file'],
				__( 'line', 'import-products-to-vk' ),
				$reason['line']
			);
		} else {
			$reason_string = sprintf(
				'GROUP № %1$s; Товар с postId = %2$s пропущен. Причина: %3$s; Файл: %4$s; %5$s: %6$s',
				$this->feed_id,
				$reason['post_id'],
				$reason['reason'],
				$reason['file'],
				__( 'line', 'import-products-to-vk' ),
				$reason['line']
			);
		}

		$this->set_skip_reasons_arr( $reason_string );
		new IP2VK_Error_Log( $reason_string );
	}

	/* Getters */

	/**
	 * Get product
	 * 
	 * @return WC_Product
	 */
	public function get_product() {
		return $this->product;
	}

	/**
	 * Get feed ID
	 * 
	 * @return int|string
	 */
	public function get_feed_id() {
		return $this->feed_id;
	}

	/**
	 * Get result
	 * 
	 * @return array
	 */
	public function get_result() {
		return $this->product_data_arr;
	}

	/**
	 * Get Product ID on VK.com list array
	 * 
	 * @return array - [0][`product_id_on_vk` => false | string, `post_id_on_wp` => int, `have_get_result` => bool], [1][...]
	 */
	public function get_prod_id_on_vk_list_arr() {
		return $this->prod_id_on_vk_list_arr;
	}
}