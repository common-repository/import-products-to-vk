<?php
/**
 * Set and Get the Plugin Data
 *
 * @package                 iCopyDoc Plugins (v1.1, core 22-04-2024)
 * @subpackage              Import Products to VK
 * @since                   0.1.0
 * 
 * @version                 0.7.4 (12-08-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param       array       $data_arr - Optional
 *
 * @depends                 classes:	
 *                          traits:		
 *                          methods:	
 *                          functions:	
 *                          constants:	
 */
defined( 'ABSPATH' ) || exit;

class IP2VK_Data_Arr {
	/**
	 * Plugin options array
	 *
	 * @var array
	 */
	private $data_arr = [];

	/**
	 * Set and Get the Plugin Data
	 * 
	 * @param array $data_arr - Optional
	 */
	public function __construct( $data_arr = [] ) {
		if ( empty( $data_arr ) ) {
			$this->data_arr = [ 
				[ 
					'opt_name' => 'status_sborki',
					'def_val' => '-1',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // дата начала сборки
					'opt_name' => 'date_sborki',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // дата завершения сборки
					'opt_name' => 'date_sborki_end',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // дата сохранения настроек плагина
					'opt_name' => 'date_save_set',
					'def_val' => '0000000001',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // число товаров, попавших в выгрузку
					'opt_name' => 'count_products_in_feed',
					'def_val' => '-1',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 
					'opt_name' => 'status_cron',
					'def_val' => 'off',
					'mark' => 'private',
					'required' => true,
					'type' => 'auto',
					'tab' => 'none'
				],
				[ 
					'opt_name' => 'group_id',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'api_tab',
					'data' => [ 
						'label' => __( 'Group ID', 'import-products-to-vk' ),
						'desc' => sprintf(
							'group_id - %s (%s, %s "club")',
							__( 'from the settings of the group to which we export products', 'import-products-to-vk' ),
							__( 'only numbers', 'import-products-to-vk' ),
							__( 'without', 'import-products-to-vk' )
						),
						'placeholder' => sprintf( '%s: %s',
							__( 'For example', 'import-products-to-vk' ),
							'87654321'
						)
					]
				],
				[ 
					'opt_name' => 'application_id',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'api_tab',
					'data' => [ 
						'label' => __( 'Application ID', 'import-products-to-vk' ),
						'desc' => sprintf(
							'Application ID - %s (%s. %s: 87654321)',
							__( 'from the app settings', 'import-products-to-vk' ),
							__( 'only numbers', 'import-products-to-vk' ),
							__( 'For example', 'import-products-to-vk' )
						),
						'placeholder' => sprintf( '%s: %s',
							__( 'For example', 'import-products-to-vk' ),
							'87654321'
						)
					]
				],
				[ 
					'opt_name' => 'public_key', // Защищённый ключ
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'api_tab',
					'data' => [ 
						'label' => sprintf( '%s<br/><small>(%s)</small>',
							__( 'Secret key', 'import-products-to-vk' ),
							__( 'Client secret', 'import-products-to-vk' )
						),
						'desc' => 'public_key - ' . __( 'from the app settings', 'import-products-to-vk' ),
						'placeholder' => ''
					]
				],
				[ 
					'opt_name' => 'private_key', // Сервисный ключ доступа
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'api_tab',
					'data' => [ 
						'label' => sprintf( '%s<br/><small>(%s)</small>',
							__( 'Service access key', 'import-products-to-vk' ),
							__( 'Private key', 'import-products-to-vk' )
						),
						'desc' => sprintf( 'service_token (private_key) - %s',
							__( 'from the app settings', 'import-products-to-vk' )
						),
						'placeholder' => ''
					]
				],
				[ 
					'opt_name' => 'access_token',
					'def_val' => '',
					'mark' => 'public',
					'required' => true,
					'type' => 'text',
					'tab' => 'api_tab',
					'data' => [ 
						'label' => __( 'Access token', 'import-products-to-vk' ),
						'desc' => sprintf( 'access_token - %s. <a href="https://icopydoc.ru/nastrojka-plagina-import-products-to-vk/?%s">%s</a>',
							__( 'it will be received automatically', 'import-products-to-vk' ),
							'utm_source=import-products-to-vk&utm_medium=organic&utm_campaign=in-plugin-import-products-to-vk&utm_content=api-tab&utm_term=main-instruction',
							__( 'See the instructions', 'import-products-to-vk' )
						),
						'placeholder' => ''
					]
				],
				[ 
					'opt_name' => 'syncing_with_vk',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Syncing with VK.com', 'import-products-to-vk' ),
						'desc' => __(
							'Using this parameter, you can stop the plugin completely',
							'import-products-to-vk'
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
				[ 
					'opt_name' => 'run_cron',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'The frequency of full synchronization of products', 'import-products-to-vk' ),
						'desc' => sprintf( '%s. %s "%s" %s "%s"',
							__(
								'With the specified frequency, the plugin will transmit information about all your products to VK.com',
								'import-products-to-vk'
							),
							__(
								'If selected',
								'import-products-to-vk'
							),
							__( 'Run the full import once', 'import-products-to-vk' ),

							__( 'after the import, the parameter value will change to',
								'import-products-to-vk' ),

							__( 'Disabled', 'import-products-to-vk' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 
								'value' => 'once',
								'text' => sprintf( '%s (%s)',
									__( 'Run the full import once', 'import-products-to-vk' ),
									__( 'launch now', 'import-products-to-vk' )
								)
							],
							[ 'value' => 'hourly', 'text' => __( 'Hourly', 'import-products-to-vk' ) ],
							[ 'value' => 'three_hours', 'text' => __( 'Every three hours', 'import-products-to-vk' ) ],
							[ 'value' => 'six_hours', 'text' => __( 'Every six hours', 'import-products-to-vk' ) ],
							[ 'value' => 'twicedaily', 'text' => __( 'Twice a day', 'import-products-to-vk' ) ],
							[ 'value' => 'daily', 'text' => __( 'Daily', 'import-products-to-vk' ) ],
							[ 'value' => 'week', 'text' => __( 'Once a week', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 'step_export',
					'def_val' => '300',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Step export', 'import-products-to-vk' ),
						'desc' => __(
							'Determines the maximum number of products uploaded to VK.com in one minute',
							'import-products-to-vk'
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => '25', 'text' => '25' ],
							[ 'value' => '30', 'text' => '30' ],
							[ 'value' => '40', 'text' => '40' ],
							[ 'value' => '50', 'text' => '50' ],
							[ 'value' => '100', 'text' => '100' ],
							[ 'value' => '200', 'text' => '200' ],
							[ 'value' => '300', 'text' => '300' ],
							[ 'value' => '400', 'text' => '400' ],
							[ 
								'value' => '500',
								'text' => sprintf( '500 (%s)',
									__( 'The maximum value allowed by VK.com', 'import-products-to-vk' )
								)
							]
						]
					]
				],
				[ 
					'opt_name' => 'image_upload_method',
					'def_val' => 'path',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Image upload method', 'import-products-to-vk' ),
						'desc' => __(
							'If you use a separate server for storing images, then select',
							'import-products-to-vk'
						) . ' URL',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'path', 'text' => 'path' ],
							[ 'value' => 'url', 'text' => 'URL' ]
						]
					]
				],
				[ 
					'opt_name' => 'behavior_cats',
					'def_val' => 'id',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Categories on VK.com', 'import-products-to-vk' ),
						'desc' => sprintf( '%s "%s" %s <a href="https://icopydoc.ru/%s/?utm_source=%s%s">%s</a>',
							__( 'If you set the value', 'import-products-to-vk' ),
							__( 'Always update', 'import-products-to-vk' ),
							__(
								'then with each import, the covers in the product catalog may be lost',
								'import-products-to-vk'
							),
							'sbivayutsya-oblozhki-v-kataloge-tovarov-pri-importe-na-ok-ru-reshenie',
							'import-products-to-vk&utm_medium=organic&utm_campaign=in-plugin-import-products-to-vk&',
							'utm_content=settings&utm_term=sbivayutsya-oblozhki',
							__( 'Read more', 'import-products-to-vk' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'upd_once', 'text' => __( 'Update once', 'import-products-to-vk' ) ],
							[ 'value' => 'upd_on', 'text' => __( 'Always update', 'import-products-to-vk' ) ],
							[ 'value' => 'upd_off', 'text' => __( 'Do not update', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 'description',
					'def_val' => 'fullexcerpt',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Description of the product', 'import-products-to-vk' ),
						'desc' => sprintf( '[description] - %s',
							__( 'The source of the description', 'import-products-to-vk' )
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 
								'value' => 'excerpt',
								'text' => __( 'Only Excerpt description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'full',
								'text' => __( 'Only Full description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'excerptfull',
								'text' => __( 'Excerpt or Full description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'fullexcerpt',
								'text' => __( 'Full or Excerpt description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'excerptplusfull',
								'text' => __( 'Excerpt plus Full description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'fullplusexcerpt',
								'text' => __( 'Full plus Excerpt description', 'import-products-to-vk' )
							]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
				[ 
					'opt_name' => 'var_desc_priority',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __(
							'The varition description takes precedence over others',
							'import-products-to-vk'
						),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 'add_product_text_to_desc',
					'def_val' => 'before',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __(
							'Add text to the product description',
							'import-products-to-vk'
						),
						'desc' => sprintf( '%s! %s',
							__( 'Important', 'import-products-to-vk' ),
							__(
								'You need to fill in the field below',
								'import-products-to-vk'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 
								'value' => 'before',
								'text' => __( 'Add before the main description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'after',
								'text' => __( 'Add after the main description', 'import-products-to-vk' )
							]
						]
					]
				],
				[ 
					'opt_name' => 'text_product_text_to_desc',
					'def_val' => '',
					'mark' => 'public',
					'required' => false,
					'type' => 'textarea',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => '',
						'desc' => __( 'This text will be added to all products', 'import-products-to-vk' ),
						'placeholder' => __(
							'This text will be added to the product description',
							'import-products-to-vk'
						)
					]
				],
				[ 
					'opt_name' => 'add_product_link_to_desc',
					'def_val' => 'end',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __(
							'Add a link to the product in the description',
							'import-products-to-vk'
						),
						'desc' => sprintf( '%s! %s',
							__( 'Important', 'import-products-to-vk' ),
							__(
								'vk.com sometimes automatically removes links to third-party sites from product descriptions',
								'import-products-to-vk'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 
								'value' => 'beginning',
								'text' => __( 'Add to the beginning of the description', 'import-products-to-vk' )
							],
							[ 
								'value' => 'end',
								'text' => __( 'Add to the end of the description', 'import-products-to-vk' )
							]
						]
					]
				],
				[ 
					'opt_name' => 'text_product_link_to_desc',
					'def_val' => '',
					'mark' => 'public',
					'required' => false,
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Text before link', 'import-products-to-vk' ),
						'desc' => __( 'Text before the product link', 'import-products-to-vk' ),
						'placeholder' => ''
					]
				],
				[ 
					'opt_name' => 'product_link_button',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __(
							'Button to go to the online store',
							'import-products-to-vk'
						),
						'desc' => __(
							'Adds a button by clicking on which the user goes to the product card on your site',
							'import-products-to-vk'
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
				[ 
					'opt_name' => 'source_sku',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Product SKU source', 'import-products-to-vk' ),
						'desc' => '',
						'woo_attr' => true,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'import-products-to-vk' ) ],
							[ 'value' => 'germanized', 'text' => 'WooCommerce Germanized' ]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
				[ 
					'opt_name' => 'sync_product_amount',
					'def_val' => 'enabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => sprintf( '%s',
							__( 'Import the amount of the product', 'import-products-to-vk' )
						),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
				[ 
					'opt_name' => 'skip_missing_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => sprintf( '%s (%s)',
							__( 'Skip missing products', 'import-products-to-vk' ),
							__( 'except for products for which a pre-order is permitted', 'import-products-to-vk' )
						),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						],
						'tr_class' => ''
					]
				],
				[ 
					'opt_name' => 'skip_backorders_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Skip backorders products', 'import-products-to-vk' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 'only_first_variation',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => true,
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [ 
						'label' => __( 'Import only the first variation', 'import-products-to-vk' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 'old_price',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => __( 'Old price', 'import-products-to-vk' ),
						'desc' => __(
							'In oldprice indicates the old price of the goods, which must necessarily be higher than the new price (price)',
							'import-products-to-vk'
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						]
					]
				],
				[ 
					'opt_name' => 're_import_img',
					'def_val' => 'disabled',
					'mark' => 'public',
					'required' => false,
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [ 
						'label' => sprintf( '%s',
							__( 'Allow re-import of images', 'import-products-to-vk' )
						),
						'desc' => sprintf( '%s',
							__(
								'This option is useful if your site very often uses the same image several times for different products',
								'import-products-to-vk'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [ 
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'import-products-to-vk' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'import-products-to-vk' ) ]
						],
						'tr_class' => 'ip2vk_tr'
					]
				],
			];
		} else {
			$this->data_arr = $data_arr;
		}

		$this->data_arr = apply_filters( 'ip2vk_set_default_feed_settings_result_arr_filter', $this->get_data_arr() );
	}

	/**
	 * Get the plugin data array
	 * 
	 * @return array
	 */
	public function get_data_arr() {
		return $this->data_arr;
	}

	/**
	 * Get data for tabs
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array([0] => opt_key1, [1] => opt_key2, ...)
	 */
	public function get_data_for_tabs( $whot = '' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			// echo get_array_as_string($this->get_data_arr(), '<br/>');
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "main_tab":
					case "filtration_tab":
						if ( $this->get_data_arr()[ $i ]['tab'] === $whot ) {
							$arr = $this->get_data_arr()[ $i ]['data'];
							$arr['opt_name'] = $this->get_data_arr()[ $i ]['opt_name'];
							$arr['tab'] = $this->get_data_arr()[ $i ]['tab'];
							$arr['type'] = $this->get_data_arr()[ $i ]['type'];
							$res_arr[] = $arr;
						}
						break;
					case "api_tab":
						if ( $this->get_data_arr()[ $i ]['tab'] === 'api_tab' ) {
							$arr = $this->get_data_arr()[ $i ]['data'];
							$arr['opt_name'] = $this->get_data_arr()[ $i ]['opt_name'];
							$arr['tab'] = $this->get_data_arr()[ $i ]['tab'];
							$arr['type'] = $this->get_data_arr()[ $i ]['type'];
							$res_arr[] = $arr;
						}
						break;
					default:
						if ( $this->get_data_arr()[ $i ]['tab'] === $whot ) {
							$arr = $this->get_data_arr()[ $i ]['data'];
							$arr['opt_name'] = $this->get_data_arr()[ $i ]['opt_name'];
							$arr['tab'] = $this->get_data_arr()[ $i ]['tab'];
							$arr['type'] = $this->get_data_arr()[ $i ]['type'];
							$res_arr[] = $arr;
						}
				}
			}
			// echo get_array_as_string($res_arr, '<br/>');
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array([0] => opt_key1, [1] => opt_key2, ...)
	 */
	public function get_opts_name( $whot = '' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					default:
						$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
				}
			}
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name and default date (array)
	 * 
	 * @param string $whot
	 * 
	 * @return array	Example: array(opt_name1 => opt_val1, opt_name2 => opt_val2, ...)
	 */
	public function get_opts_name_and_def_date( $whot = 'all' ) {
		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					default:
						$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
				}
			}
			return $res_arr;
		} else {
			return $res_arr;
		}
	}

	/**
	 * Get plugin options name and default date (stdClass object)
	 * 
	 * @param string $whot
	 * 
	 * @return array<stdClass>
	 */
	public function get_opts_name_and_def_date_obj( $whot = 'all' ) {
		$source_arr = $this->get_opts_name_and_def_date( $whot );

		$res_arr = [];
		foreach ( $source_arr as $key => $value ) {
			$obj = new stdClass();
			$obj->name = $key;
			$obj->opt_def_value = $value;
			$res_arr[] = $obj; // unit obj
			unset( $obj );
		}
		return $res_arr;
	}
}