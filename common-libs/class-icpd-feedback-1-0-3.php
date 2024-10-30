<?php
/**
 * This class is responsible for the feedback form inside the plugin
 *
 * @package                 iCopyDoc Plugins (ICPD)
 * @subpackage              ENG
 * @since                   0.1.0
 * 
 * @version                 1.0.3 (03-06-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param       array       $args
 *
 * @depends                 classes:    WP_Query
 *                          traits:     
 *                          methods:    
 *                          functions:  get_woo_version_number
 *                          constants:  
 *                          actions:    _feedback_block
 *                          filters:    _f_feedback_additional_info
 */
defined( 'ABSPATH' ) || exit;

// 'import-products-to-vk' - slug for translation (be sure to make an autocorrect)
if ( ! class_exists( 'ICPD_Feedback' ) ) {
	final class ICPD_Feedback {
		/**
		 * Plugin name
		 * @var string
		 */
		private $plugin_name = '';
		/**
		 * Plugin version (For example: '1.0.0')
		 * @var string
		 */
		private $plugin_version = '0.1.0';
		/**
		 * Plugin prefix
		 * @var string
		 */
		private $pref = '';
		/**
		 * URL of the log file
		 * @var string
		 */
		private $logs_url = '';
		/**
		 * Additional information that can be passed to the report
		 * @var string
		 */
		private $additional_info = '';

		/**
		 * This class is responsible for the feedback form inside the plugin
		 * 
		 * @param array $args
		 */
		public function __construct( $args = [] ) {
			if ( isset( $args['plugin_name'] ) ) {
				$this->plugin_name = $args['plugin_name'];
			}
			if ( isset( $args['plugin_version'] ) ) {
				$this->plugin_version = $args['plugin_version'];
			}
			if ( isset( $args['pref'] ) ) {
				$this->pref = $args['pref'];
			}
			if ( isset( $args['logs_url'] ) ) {
				$this->logs_url = $args['logs_url'];
			}
			if ( isset( $args['additional_info'] ) ) {
				$this->additional_info = $args['additional_info'];
			}

			$this->init_hooks();
		}

		/**
		 * Initialization hooks
		 * 
		 * @return void
		 */
		public function init_hooks() {
			add_action( 'admin_print_footer_scripts', [ $this, 'print_css_styles' ] );
			$hook_name = $this->get_pref() . '_feedback_block';
			add_action( $hook_name, [ $this, 'print_view_html_feedback_block' ] );

			if ( isset( $_REQUEST[ $this->get_submit_name()] ) ) {
				// ! Очень важно пускать через фильтр в этом месте, а иначе фильтр _f_feedback_additional_info
				// ! внутри фукцнии send_data не будет работать
				add_action( 'admin_init', [ $this, 'send_data' ], 10 );
				add_action( 'admin_notices', function () {
					$class = 'notice notice-success is-dismissible';
					$message = __( 'The data has been sent. Thank you', 'import-products-to-vk' );
					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}, 9999 );
			}
		}

		/**
		 * Print css styles
		 * 
		 * @return void
		 */
		public function print_css_styles() {
			print ( '<style>.clear{clear: both;} .icpd_bold {font-weight: 700;}
		.icpd_ul {list-style-type: square; margin: 5px 0px 3px 30px;}</style>' );
		}

		/**
		 * Print html of feedback block
		 * 
		 * @return void
		 */
		public function print_view_html_feedback_block() { ?>
			<div class="postbox">
				<h2 class="hndle">
					<?php esc_html_e( 'Send data about the work of the plugin', 'import-products-to-vk' ); ?>
				</h2>
				<div class="inside">
					<?php
					printf( '<p>%s! %s:</p>',
						esc_html__( 'Sending statistics you help make the plugin even better', 'import-products-to-vk' ),
						esc_html__( 'The following data will be sent', 'import-products-to-vk' )
					);
					?>
					<ul class="icpd_ul">
						<?php
						printf( '<li>%s</li><li>%s</li><li>%s %s</li></p>',
							esc_html__( 'PHP version information', 'import-products-to-vk' ),
							esc_html__( 'Multisite mode status', 'import-products-to-vk' ),
							esc_html__( 'Technical information and plugin logs', 'import-products-to-vk' ),
							esc_html( $this->get_plugin_name() )
						); ?>
					</ul>
					<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
						<?php
						printf( '<p>%s %s?</p>',
							esc_html__( 'Did my plugin help you upload your products to the', 'import-products-to-vk' ),
							esc_html( $this->get_plugin_name() )
						);
						?>
						<p>
							<?php
							printf( '<input type="radio" value="yes" name="%s">%s<br />',
								esc_attr( $this->get_radio_name() ),
								esc_html__( 'Yes', 'import-products-to-vk' )
							);

							printf( '<input type="radio" value="no" name="%s">%s<br />',
								esc_attr( $this->get_radio_name() ),
								esc_html__( 'No', 'import-products-to-vk' )
							);
							?>
						</p>
						<p>
							<?php
							esc_html_e( "If you don't mind to be contacted in case of problems, please enter your email address",
								"import-products-to-vk"
							); ?>:
						</p>
						<p><input type="email" name="<?php echo $this->get_input_name(); ?>" placeholder="your@email.com"></p>
						<p>
							<?php esc_html_e( 'Your message', 'import-products-to-vk' ); ?>:
						</p>
						<p><textarea rows="6" cols="32" name="<?php echo $this->get_textarea_name(); ?>" placeholder="<?php
						   printf( '%1$s (%2$s). %3$s',
						   	esc_attr( esc_html__( 'Enter your text to send me a message', 'import-products-to-vk' ) ),
						   	esc_attr( esc_html__( 'You can write me in Russian or English', 'import-products-to-vk' ) ),
						   	esc_attr( esc_html__( 'I check my email several times a day', 'import-products-to-vk' ) )
						   ); ?>"></textarea></p>
						<?php wp_nonce_field( $this->get_nonce_action(), $this->get_nonce_field() ); ?>
						<input class="button-primary" type="submit" name="<?php echo $this->get_submit_name(); ?>"
							value="<?php esc_html_e( 'Send data', 'import-products-to-vk' ); ?>" />
					</form>
				</div>
			</div>
			<?php
		}

		/**
		 * Send data
		 * 
		 * @return void
		 */
		public function send_data() {
			if ( ! empty( $_POST )
				&& check_admin_referer( $this->get_nonce_action(), $this->get_nonce_field() ) ) {
				if ( is_multisite() ) {
					$multisite = 'включен';
				} else {
					$multisite = 'отключен';
				}
				$current_time = (string) current_time( 'Y-m-d H:i' );

				$mail_content = sprintf(
					'<h1>Заявка (#%1$s)</h1>
				<p>Сайт: %2$s<br />
				Версия плагина: %3$s<br />
				Версия WP: %4$s<br />
				Режим мультисайта: %5$s<br />
				Версия PHP: %6$s</p>%7$s',
					esc_html( $current_time ),
					home_url(),
					esc_html( $this->get_plugin_version() ),
					get_bloginfo( 'version' ),
					esc_html( $multisite ),
					phpversion(),
					esc_html( $this->get_additional_info() )
				);

				if ( class_exists( 'WooCommerce' ) ) {
					$mail_content .= sprintf( '<p>Версия WC: %1$s<br />',
						esc_html( get_woo_version_number() )
					);

					$argsp = [ 
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => -1
					];
					$products = new \WP_Query( $argsp );
					$vsegotovarov = $products->found_posts;
					unset( $products );
					$mail_content .= sprintf( 'Число товаров: %1$s</p>',
						esc_html( $vsegotovarov )
					);
				}

				if ( is_multisite() ) {
					$keeplogs = get_blog_option( get_current_blog_id(), $this->get_pref() . '_keeplogs' );
				} else {
					$keeplogs = get_option( $this->get_pref() . '_keeplogs' );
				}
				if ( empty( $keeplogs ) ) {
					$mail_content .= "Вести логи: отключено<br />";
				} else {
					$mail_content .= "Вести логи: включено<br />";
					$mail_content .= sprintf(
						'Расположение логов: <a target="_blank" href="%1$s">%1$s</a><br />',
						$this->get_logs_url()
					);
				}

				if ( isset( $_POST[ $this->get_radio_name()] ) ) {
					$mail_content .= sprintf( 'Помог ли плагин: %1$s<br />',
						sanitize_text_field( $_POST[ $this->get_radio_name()] )
					);
				}
				if ( isset( $_POST[ $this->get_input_name()] ) ) {
					$mail_content .= sprintf(
						'Почта: <a href="mailto:%1$s?subject=%2$s %3$s (#%4$s)" target="_blank">%5$s</a><br />',
						sanitize_email( $_POST[ $this->get_input_name()] ),
						'Ответ разработчика',
						esc_html( $this->get_plugin_name() ),
						esc_html( $current_time ),
						sanitize_email( $_POST[ $this->get_input_name()] )
					);
				}
				if ( isset( $_POST[ $this->get_textarea_name()] ) ) {
					$mail_content .= sprintf( 'Сообщение: %1$s<br />',
						sanitize_text_field( $_POST[ $this->get_textarea_name()] )
					);
				}

				$additional_info = '';
				$filters_name = $this->get_pref() . '_f_feedback_additional_info';
				$additional_info = apply_filters( $filters_name, $additional_info );
				if ( is_string( $additional_info ) ) {
					$additional_info = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $additional_info );
					$mail_content .= $additional_info;
				}

				$subject = sprintf( 'Отчёт %1$s',
					esc_html( $this->get_plugin_name() )
				);
				add_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
				wp_mail( 'support@icopydoc.ru', $subject, $mail_content );
				// Сбросим content-type, чтобы избежать возможного конфликта
				remove_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
			}
		}

		/**
		 * Set html content type
		 * 
		 * @return string
		 */
		public static function set_html_content_type() {
			return 'text/html';
		}

		/**
		 * Get prefix
		 * 
		 * @return string
		 */
		private function get_pref() {
			return $this->pref;
		}

		/**
		 * Get plugin name
		 * 
		 * @return string
		 */
		private function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * Get plugin version
		 * 
		 * @return string
		 */
		private function get_plugin_version() {
			return $this->plugin_version;
		}

		/**
		 * Get file logs url
		 * 
		 * @return string
		 */
		private function get_logs_url() {
			return $this->logs_url;
		}

		/**
		 * Get additional info
		 * 
		 * @return string
		 */
		private function get_additional_info() {
			return $this->additional_info;
		}

		/**
		 * Get radio name
		 * 
		 * @return string
		 */
		private function get_radio_name() {
			return $this->get_pref() . '_its_ok';
		}

		/**
		 * Get input name
		 * 
		 * @return string
		 */
		private function get_input_name() {
			return $this->get_pref() . '_email';
		}

		/**
		 * Get textarea name
		 * 
		 * @return string
		 */
		private function get_textarea_name() {
			return $this->get_pref() . '_message';
		}

		/**
		 * Get submit name
		 * 
		 * @return string
		 */
		private function get_submit_name() {
			return $this->get_pref() . '_submit_send_stat';
		}

		/**
		 * Get nonce action
		 * 
		 * @return string
		 */
		private function get_nonce_action() {
			return $this->get_pref() . '_nonce_action_send_stat';
		}

		/**
		 * Get nonce field
		 * 
		 * @return string
		 */
		private function get_nonce_field() {
			return $this->get_pref() . '_nonce_field_send_stat';
		}
	} // end final class ICPD_Feedback
} // end if (!class_exists('ICPD_Feedback'))