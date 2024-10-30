<?php 
/**
 * The Another page tab
 * 
 * @version 0.6.0 (24-01-2024)
 * @see     
 * @package 
 * 
 * @param $view_arr['feed_id']
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;

$args_arr = [ 'tab_name' => $view_arr['tab_name'], 'feed_id' => $view_arr['feed_id'] ];
$args_arr = apply_filters( 'ip2vk_f_another_tab_args_arr', $args_arr );
?>
<div class="postbox">
	<?php do_action_ref_array( 'ip2vk_a_before_another_tab', $args_arr ); ?>
	<div class="inside">
		<table class="form-table" role="presentation">
			<tbody>
				<?php do_action_ref_array( 'ip2vk_a_prepend_another_tab', $args_arr ); ?>
				<?php IP2VK_Settings_Page::print_view_html_fields( $view_arr['tab_name'], $view_arr['feed_id'] ); ?>
				<?php do_action_ref_array( 'ip2vk_a_append_another_tab', $args_arr ); ?>
			</tbody>
		</table>
	</div>
	<?php do_action_ref_array( 'ip2vk_a_after_another_tab', $args_arr ); ?>
</div>
<?php
do_action( 'ip2vk_switch_get_tab', [ 'tab_name' => $view_arr['tab_name'], 'feed_id' => $view_arr['feed_id'] ] );