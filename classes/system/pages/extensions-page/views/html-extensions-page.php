<?php
/**
 * Print extensions page
 * 
 * @version 0.6.8 (13-06-2024)
 * @see     
 * @package 
 */
defined( 'ABSPATH' ) || exit;
?>
<style>
	.button-primary {
		padding: 0.375rem 0.75rem !important;
		font-size: 1rem !important;
		border-radius: 0.25rem !important;
		border: #181a1c 1px solid !important;
		background-color: #181a1c !important;
		text-align: center;
		margin: 0 auto !important;
	}

	.button-primary:hover {
		background-color: #3d4247 !important;
	}

	.ip2vk_banner {
		max-width: 100%
	}
</style>
<div id="ip2vk_extensions" class="wrap">
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-1">
				<div class="meta-box-sortables">
					<div class="postbox">
						<a href="https://icopydoc.ru/product/import-products-to-vk-pro/?utm_source=import-products-to-vk&utm_medium=organic&utm_campaign=in-plugin-import-products-to-vk&utm_content=extensions&utm_term=banner-pro"
							target="_blank"><img class="ip2vk_banner"
								src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>/assets/img/import-products-to-vk-pro-banner.jpg"
								alt="Upgrade to Import Products to VK PRO" /></a>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<td class="overalldesc" style="font-size: 20px;">
											<h3 style="font-size: 24px; text-align: center; color: #5b2942;">Import
												Products to VK PRO</h3>
											<ul style="text-align: center;">
												<li>&#10004;
													<?php esc_html_e( 'The ability to exclude products from certain categories', 'import-products-to-vk' ); ?>;
												</li>
												<li>&#10004;
													<?php esc_html_e( 'Ability to exclude products by certain tags', 'import-products-to-vk' ); ?>;
												</li>
												<li>&#10004;
													<?php esc_html_e( 'The ability to exclude products at a price', 'import-products-to-vk' ); ?>;
												</li>
												<li>&#10004;
													<?php esc_html_e( 'The ability to сhange the product price by a certain percentage', 'import-products-to-vk' ); ?>;
												</li>
												<li>&#10004;
													<?php esc_html_e( 'The ability to import multiple images instead of one', 'import-products-to-vk' ); ?>;
												</li>
												<li>&#10004;
													<?php esc_html_e( 'Even more stable work', 'import-products-to-vk' ); ?>!
												</li>
											</ul>
											<p style="text-align: center;"><a class="button-primary"
													href="https://icopydoc.ru/product/import-products-to-vk-pro/?utm_source=import-products-to-vk&utm_medium=organic&utm_campaign=in-plugin-import-products-to-vk&utm_content=extensions&utm_term=poluchit-pro"
													target="_blank">
													<?php
													printf( '%s %s %s',
														esc_html__( 'Get', 'import-products-to-vk' ),
														'Import Products to VK PRO',
														esc_html__( 'Now', 'import-products-to-vk' )
													);
													?>
												</a><br /></p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>