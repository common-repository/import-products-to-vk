<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Traits for different classes
 *
 * @package			Import Products to VK
 * @subpackage		
 * @since			0.1.0
 * 
 * @version			0.1.0 (11-02-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 */

trait IP2VK_T_Get_Product {
	protected $product;

	protected function get_product() {
		return $this->product;
	}
}

trait IP2VK_T_Get_Feed_Id {
	protected $feed_id;

	protected function get_feed_id() {
		return $this->feed_id;
	}
}

trait IP2VK_T_Get_Post_Id {
	protected $post_id;

	protected function get_post_id() {
		return $this->post_id;
	}
}

trait IP2VK_T_Get_Skip_Reasons_Arr {
	protected $skip_reasons_arr = array();

	public function set_skip_reasons_arr( $v ) {
		$this->skip_reasons_arr[] = $v;
	}

	public function get_skip_reasons_arr() {
		return $this->skip_reasons_arr;
	}
}