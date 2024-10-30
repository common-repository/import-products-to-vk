<?php
/**
 * The Instruction tab
 * 
 * @version 0.7.4 (12-08-2024)
 * @see     
 * @package 
 * 
 * @param $view_arr['feed_id']
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="postbox">
	<h2 class="hndle">
		<?php esc_html_e( 'API Settings', 'import-products-to-vk' ); ?>
	</h2>
	<div class="inside">
		<?php
		$token = common_option_get( 'access_token', false, $view_arr['feed_id'], 'ip2vk' );
		if ( empty( $token ) ) {
			printf( '<p><span style="color: red;">%1$s</span></p>',
				esc_html__( 'You need to get a token', 'import-products-to-vk' )
			);
		}
		$params = [ 
			'client_id' => common_option_get( 'application_id', false, $view_arr['feed_id'], 'ip2vk' ),
			'display' => 'page',
			'scope' => 'offline,wall,photos,market,groups',
			'response_type' => 'token', // or 'code' see https://vk.com/dev/implicit_flow_user
			'redirect_uri' => get_site_url( null, '/ip2vk/' )
		];

		$url = 'https://oauth.vk.com/authorize?' . urldecode( http_build_query( $params ) );

		// const uuid = 'offlinewallph@sgfwrotosmarketZgroups'; 
		// Сгенерируйте случайную строку — рекомендуем использовать представление не менее 36 символов. 
		// Далее эта строка используется для проверки, что запрос идет именно со стороны вашего приложения.
		// const appId = 0; // Идентификатор вашего приложения.
		// const redirectUri = ''; // Адрес для перехода после авторизации, который совпадает с доверенным редиректом из настроек приложения.			
		// const query = `uuid=${uuid}&app_id=${appId}&response_type=silent_token&redirect_uri=${redirectUri}`;
		$params_new = [ 
			'uuid' => md5( time() ),
			'app_id' => common_option_get( 'application_id', false, $view_arr['feed_id'], 'ip2vk' ),
			'response_type' => 'silent_token', // or 'code' see https://vk.com/dev/implicit_flow_user
			'redirect_uri' => get_site_url( null, '/ip2vk/' ),
			'display' => 'page',
			'scope' => 'offline,wall,photos,market,groups'
		];
		$url_new = 'https://id.vk.com/auth?' . urldecode( http_build_query( $params_new ) );
		printf( '<p>%1$s "%2$s". <a target="_blank" href="//id.vk.com/about/business/go/">%3$s</a>. %4$s: <a href="%5$s">%6$s</a>. %7$s (<a href="%8$s" target="_blank">%9$s</a>). %10$s</p>',
			esc_html__( 'Fill in the', 'import-products-to-vk' ),
			esc_html__( 'Group ID', 'import-products-to-vk' ),
			esc_html__(
				'Create an application',
				'import-products-to-vk'
			),
			esc_html__(
				'Thereafter fill in the "Application ID", "Client secret", "Private key", save them, and then follow this link',
				'import-products-to-vk'
			),
			esc_attr( $url_new ),
			esc_html__( 'Authorization via VK ID', 'import-products-to-vk' ),

			esc_html__( 'Be sure to click "allow". You will then be redirected back', 'import-products-to-vk' ),
			'https://id.vk.com/about/business/go/',
			esc_html__( 'You can delete a previously issued token here', 'import-products-to-vk' ),
			esc_html__(
				'After configuring the API, edit your categories on the site by selecting a similar category for each of them in VK.com',
				'import-products-to-vk'
			)
		);
		?>
		<table class="form-table" role="presentation">
			<tbody>
				<?php IP2VK_Settings_Page::print_view_html_fields( $view_arr['tab_name'], $view_arr['feed_id'] ); ?>
				<tr class="ip2vk_tr">
					<th scope="row"><label for="redirect_uri">Redirect URI</label></th>
					<td class="overalldesc">
						<input type="text" name="redirect_uri" id="redirect_uri"
							value="<?php echo get_site_url( null, '/ip2vk/' ); ?>" disabled><br />
						<span class="description">
							<small><strong>redirect_uri</strong> -
								<?php
								esc_html_e( 'specify it in the application settings', 'import-products-to-vk' );
								?>
							</small></span>
					</td>
				</tr>
				<?php if ( ! empty( $token ) ) : ?>
					<tr class="ip2vk_tr">
						<th scope="row">
							<label for="redirect_uri">
								<?php esc_html_e( 'Check API', 'import-products-to-vk' ); ?>
							</label>
						</th>
						<td class="overalldesc">
							<input id="button-check-api" class="button" value="<?php
							esc_html_e( 'Check API', 'import-products-to-vk' );
							?>" type="submit" name="ip2vk_check_action" /><br />
							<span class="description">
								<small>
									<?php
									printf( '%s. %s',
										esc_html__( 'The VK API is configured', 'import-products-to-vk' ),
										esc_html__(
											'Now you can check its operation by clicking on this button',
											'import-products-to-vk'
										)
									);
									?></span>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>