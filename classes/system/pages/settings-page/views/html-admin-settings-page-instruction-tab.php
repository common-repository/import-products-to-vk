<?php
/**
 * The Instruction tab
 * 
 * @version 0.7.4 (12-08-2024)
 * @see     
 * @package 
 * 
 * @param 
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="postbox">
	<h2 class="hndle">
		<?php esc_html_e( 'Instruction', 'import-products-to-vk' ); ?>
	</h2>
	<div class="inside">
		<p><i>(
				<?php esc_html_e( 'The full version of the instruction can be found', 'import-products-to-vk' );
				?> <a href="<?php
				 printf( '%1$s?utm_source=%2$s&utm_medium=organic&utm_campaign=in-plugin-%2$s%3$s',
				 	'https://icopydoc.ru/nastrojka-plagina-import-products-to-vk/',
				 	'import-products-to-vk',
				 	'&utm_content=instruction-tab&utm_term=main-instruction'
				 ); ?>" target="_blank"><?php esc_html_e( 'here', 'import-products-to-vk' ); ?></a>)
			</i></p>
		<ol>
			<li>
				<?php
				printf( '%s "%s" %s',
					esc_html__( 'On the tab', 'import-products-to-vk' ),
					esc_html__( 'API Settings', 'import-products-to-vk' ),
					esc_html__(
						'enter the ID of the group to which you are going to import products',
						'import-products-to-vk'
					)
				);
				?>
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-6.png"
				alt="instruction-6.png" /></p>
		<ol>
			<li value="2">
				<?php
				printf( '%s <a target="_blank" href="//id.vk.com/about/business/go/">%s</a>. %s:<br/>',
					esc_html__( 'To access VK.com API you need', 'import-products-to-vk' ),
					esc_html__( 'Create application', 'import-products-to-vk' ),
					esc_html__( 'For this', 'import-products-to-vk' )
				);
				esc_html_e( 'Follow the link and click', 'import-products-to-vk' ); ?> "<a target="_blank"
					href="//id.vk.com/about/business/go/">
					<?php esc_html_e( 'Add application', 'import-products-to-vk' ); ?></a>"
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-1.png"
				alt="instruction-1.png" /></p>
		<ol>
			<li value="3">
				<?php esc_html_e( 'Application type ', 'import-products-to-vk' ); ?> - "Web"
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-2.png"
				alt="instruction-2.png" /></p>
		<ol>
			<li value="4">
				<?php
				esc_html_e(
					'Fill in the "Site Address" and "Base domain" fields, specifying the',
					'import-products-to-vk'
				); ?> <code><?php $parsed = parse_url( get_site_url( null, '/' ) );
				  echo esc_html( $parsed['host'] ); ?></code>
			</li>
			<li>
				<?php
				esc_html_e( 'Fill in the', 'import-products-to-vk' ); ?> redirect URI
				<?php
				esc_html_e( 'specifying the', 'import-products-to-vk' );
				?> <code><?php echo get_site_url( null, '/ip2vk/' ); ?></code>
			</li>
			<li>
				<?php
				printf( '%s "%s", "%s" (service_token), "%s" (%s) %s (%s "%s")',
					esc_html__( 'Copy the', 'import-products-to-vk' ),
					esc_html__( 'Application ID', 'import-products-to-vk' ),
					esc_html__( 'Secure key', 'import-products-to-vk' ),
					esc_html__( 'Private key', 'import-products-to-vk' ),
					esc_html__( 'Client secret', 'import-products-to-vk' ),
					esc_html__( 'and paste them on the plugin settings page', 'import-products-to-vk' ),
					esc_html__( 'tab', 'import-products-to-vk' ),
					esc_html__( 'API Settings', 'import-products-to-vk' )
				); ?>
			</li>
			<li>
				<?php
				esc_html_e( 'Be sure to turn on the app by selecting', 'import-products-to-vk' ); ?> "
				<?php
				esc_html_e( 'the app is on and visible to everyone', 'import-products-to-vk' ); ?>"
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-3.png"
				alt="instruction-7.png" /></p>
		<ol>
			<li value="8">
				<?php
				esc_html_e(
					'Go to the access section and enable "Wall", "Communities", "Photos", "Products"',
					'import-products-to-vk' );
				?>
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-7.png"
				alt="instruction-3.png" /></p>
		<ol>
			<li value="9">
				<?php
				printf( '%s "%s" %s "%s"',
					esc_html__( 'Go to tab', 'import-products-to-vk' ),
					esc_html__( 'API Settings', 'import-products-to-vk' ),
					esc_html__( 'and click on the link', 'import-products-to-vk' ),
					esc_html__( 'Authorization via VK.com', 'import-products-to-vk' )
				);
				?>
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-4.png"
				alt="instruction-4.png" /></p>
		<ol>
			<li value="10">
				<?php
				printf( '%s "%s". %s. %s "%s. %s"',
					esc_html__(
						'If the authorization was successful, then you will have a button',
						'import-products-to-vk'
					),
					esc_html__( 'Check API', 'import-products-to-vk' ),
					esc_html__( 'Click on it to test the API', 'import-products-to-vk' ),
					esc_html__(
						'If everything is configured correctly, you will see the message',
						'import-products-to-vk'
					),
					esc_html__( 'API connection was successful', 'import-products-to-vk' ),
					esc_html__( 'Now you can go to step 10 of the instructions', 'import-products-to-vk' )
				);
				?>
			</li>
		</ol>
		<p><img style="max-width: 100%;"
				src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>assets/img/instruction-5.png"
				alt="instruction-5.png" /></p>
		<ol>
			<li value="11">
				<?php
				esc_html_e(
					'After configuring the API, edit your categories on the site by selecting a similar category for each of them in VK.com',
					'import-products-to-vk'
				); ?>
			</li>
		</ol>
		<p><img style="max-width: 100%;" src="<?php echo esc_attr( IP2VK_PLUGIN_DIR_URL ); ?>screenshot-2.png"
				alt="screenshot-2.png" /></p>
		<ol>
			<li value="12">
				<?php
				printf( '%s "%s" %s "%s"',
					esc_html__( 'After that, go to the', 'import-products-to-vk' ),
					esc_html__( 'Main settings', 'import-products-to-vk' ),
					esc_html__( 'and activate the item', 'import-products-to-vk' ),
					esc_html__( 'Syncing with VK.com', 'import-products-to-vk' )
				);
				?>
			</li>
		</ol>
	</div>
</div>