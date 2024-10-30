<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * This trait sets the product category
 *
 * @package			Import Products to VK
 * @subpackage		
 * @since			0.1.0
 * 
 * @version			0.3.0 (04-05-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 *
 * @return 			
 * 
 * @depends			class:		IP2VK_Error_Log
 * 								WPSEO_Primary_Term
 *					methods: 	
 *					variable:	
 *					methods:	get_product
 *								get_feed_id
 *					functions:	
 *					constants:	
 *					variable:	feed_category_id (set it)
 */

trait IP2VK_T_Common_Get_CatId {
	protected $feed_category_id = null;

	public function set_category_id( $catid = null ) {
		// Yoast SEO
		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			$obj = new WPSEO_Primary_Term( 'product_cat', $this->get_product()->get_id() );
			$cat_id_yoast_seo = $obj->get_primary_term();
			if ( false === $cat_id_yoast_seo ) {
				$catid = $this->set_catid();
			} else {
				$catid = $cat_id_yoast_seo;
			}

			// Rank Math SEO
		} else if ( class_exists( 'RankMath' ) ) {
			$primary_cat_id = get_post_meta( $this->get_product()->get_id(), 'rank_math_primary_category', true );
			if ( $primary_cat_id ) {
				$product_cat = get_term( $primary_cat_id, 'product_cat' );
				$catid = $product_cat->term_id;
			} else {
				$catid = $this->set_catid();
			}

			// Standard WooCommerce сategory
		} else {
			$catid = $this->set_catid();
		}

		if ( empty( $catid ) ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'The product has no categories', 'import-products-to-vk' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-ip2vk-t-common-get-catid.php',
				'line' => __LINE__
			] );
			return '';
		}

		$this->feed_category_id = $catid;
		return $catid;
	}

	public function get_feed_category_id( $catid = null ) {
		return $this->feed_category_id;
	}

	private function set_catid( $catid = null ) {
		$termini = get_the_terms( $this->get_product()->get_id(), 'product_cat' );
		if ( false !== $termini ) {
			foreach ( $termini as $termin ) {
				$catid = $termin->term_id;
				break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
			}
		} else { // если база битая. фиксим id категорий
			$catid = $this->database_auto_boot();
		}
		return $catid;
	}

	private function database_auto_boot( $catid = null ) {
		new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; %2$s %3$s %4$s; Файл: %5$s; %6$s: %7$s',
			$this->get_feed_id(),
			'WARNING: Для товара $this->get_product()->get_id() =',
			$this->get_product()->get_id(),
			'get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms',
			'trait-ip2vk-t-common-get-catid.php',
			__( 'line', 'import-products-to-vk' ),
			__LINE__
		) );
		$product_cats = wp_get_post_terms( $this->get_product()->get_id(), 'product_cat', [ 'fields' => 'ids' ] );
		// Раскомментировать строку ниже для автопочинки категорий в БД
		// wp_set_object_terms($this->get_product()->get_id(), $product_cats, 'product_cat');
		if ( is_array( $product_cats ) && count( $product_cats ) ) {
			$catid = $product_cats[0];
			new IP2VK_Error_Log( sprintf( 'GROUP № %1$s; %2$s %3$s %4$s %5$s; Файл: %6$s; %7$s: %8$s',
				$this->get_feed_id(),
				'WARNING: Для товара $this->get_product()->get_id() =',
				$this->get_product()->get_id(),
				'база наверняка битая. wp_get_post_terms вернула массив. $catid = ',
				$catid,
				'trait-ip2vk-t-common-get-catid.php',
				__( 'line', 'import-products-to-vk' ),
				__LINE__
			) );
		}
		return $catid;
	}
}