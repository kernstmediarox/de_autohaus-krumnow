<?php
/**
 * Related Products
 *
 * Display a list of related products and/or up-sells
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'woocommerce', false ) )
{
	add_shortcode( 'av_product_upsells', 'avia_please_install_woo' );
	return;
}

if( ! class_exists( 'avia_sc_product_upsells', false ) )
{
	class avia_sc_product_upsells extends aviaShortcodeTemplate
	{
		/**
		 * Create the config array for the shortcode button
		 */
		protected function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['self_closing']	= 'yes';

			$this->config['name']			= __( 'Related Products', 'avia_framework' );
			$this->config['tab']			= __( 'Plugin Additions', 'avia_framework' );
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-tabs.png';
			$this->config['order']			= 15;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode']		= 'av_product_upsells';
			$this->config['tooltip']		= __( 'Display a list of related products and/or up-sells', 'avia_framework' );
			$this->config['drag-level']		= 3;
			$this->config['tinyMCE']		= array( 'disable' => 'true' );
			$this->config['posttype']		= array( 'product', __( 'This element can only be used on single product pages', 'avia_framework' ) );
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';
			$this->config['alb_desc_id']	= 'alb_description';
		}

		/**
		 * Popup Elements
		 *
		 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
		 * opens a modal window that allows to edit the element properties
		 *
		 * @return void
		 */
		protected function popup_elements()
		{

			$this->elements = array(

				array(
						'type' 	=> 'tab_container',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),

					array(
							'type'			=> 'template',
							'template_id'	=> $this->popup_key( 'content_upsells' )
						),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)

			);
		}

		/**
		 * Create and register templates for easier maintainance
		 *
		 * @since 4.6.4
		 */
		protected function register_dynamic_templates()
		{

			/**
			 * Content Tab
			 * ===========
			 */

			$c = array(
						array(
							'name'		=> __( 'Display options', 'avia_framework' ),
							'desc'		=> __( 'Choose which products you want to display', 'avia_framework' ),
							'id'		=> 'display',
							'type'		=> 'select',
							'std'		=> 'upsells related',
							'subtype'	=> array(
												__( 'Display up-sells and related products', 'avia_framework' )	=> 'upsells related',
												__( 'Display up-sells only', 'avia_framework' )					=> 'upsells',
												__( 'Display related products only', 'avia_framework' )			=> 'related'
											)
						),

						array(
							'name'		=> __( 'Number of items', 'avia_framework' ),
							'desc'		=> __( 'Choose the maximum number of products to display', 'avia_framework' ),
							'id'		=> 'count',
							'type'		=> 'select',
							'std'		=> '4',
							'subtype'	=> array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' )
						),

						array(
							'name'		=> __( 'WooCommerce Product visibility?', 'avia_framework' ),
							'desc'		=> __( 'Select the visibility of WooCommerce products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Inventory -&gt Out of stock visibility. Currently it is only possible to hide products out of stock.', 'avia_framework' ),
							'id'		=> 'wc_prod_visible',
							'type'		=> 'select',
							'std'		=> '',
							'subtype'	=> array(
												__( 'Use default WooCommerce Setting (Settings -&gt; Products -&gt; Out of stock visibility)', 'avia_framework' ) => '',
												__( 'Hide products out of stock', 'avia_framework' )	=> 'hide',
//												__( 'Show products out of stock', 'avia_framework' )	=> 'show'
								)
						),

				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_upsells' ), $c );

		}

		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 * @param array $params			holds the default values for $content and $args.
		 * @return array				usually holds an innerHtml key that holds item specific markup.
		 */
		public function editor_element( $params )
		{
			$params = parent::editor_element( $params );
			return $params;
		}

		/**
		 * Create custom stylings
		 *
		 * @since 4.8.9
		 * @param array $args
		 * @return array
		 */
		protected function get_element_styles( array $args )
		{
			$result = parent::get_element_styles( $args );

			extract( $result );

			$default = array(
							'display'			=> 'upsells related',
							'count'				=> 4,
							'wc_prod_visible'	=> ''
						);

			$default = $this->sync_sc_defaults_array( $default, 'no_modal_item', 'no_content' );

//			$locked = array();
//			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked, $content );
//			Avia_Element_Templates()->add_template_class( $meta, $atts, $default );

			$atts = shortcode_atts( $default, $atts, $this->config['shortcode'] );


			$classes = array(
						'av-woo-product-related-upsells',
						$element_id
					);

			$element_styling->add_classes( 'container', $classes );
			$element_styling->add_classes_from_array( 'container', $meta, 'el_class' );



			$selectors = array(
						'container'		=> ".av-woo-product-related-upsells.{$element_id}"
					);

			$element_styling->add_selectors( $selectors );


			$result['default'] = $default;
			$result['atts'] = $atts;
			$result['content'] = $content;
			$result['meta'] = $meta;

			return $result;
		}

		/**
		 * Frontend Shortcode Handler
		 *
		 * @param array $atts array of attributes
		 * @param string $content text within enclosing form of shortcode element
		 * @param string $shortcodename the shortcode found, when == callback name
		 * @return string $output returns the modified html string
		 */
		public function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
			global $avia_config, $product;

			//	fix for seo plugins which execute the do_shortcode() function before everything is loaded
			if( ! function_exists( 'WC' ) || ! WC() instanceof WooCommerce || ! is_object( WC()->query ) || ! $product instanceof WC_Product )
			{
				return '';
			}

			$result = $this->get_element_styles( compact( array( 'atts', 'content', 'shortcodename', 'meta' ) ) );

			extract( $result );
			extract( $atts );


			//	force to ignore WC default setting - see hooked function avia_wc_product_is_visible
			switch( $wc_prod_visible )
			{
				case 'show':
					$avia_config['woocommerce']['catalog_product_visibility'] = 'show_all';
					break;
				case 'hide':
					$avia_config['woocommerce']['catalog_product_visibility'] = 'hide_out_of_stock';
					break;
				default:
					$avia_config['woocommerce']['catalog_product_visibility'] = 'use_default';
					break;
			}

			$style_tag = $element_styling->get_style_tag( $element_id );
			$container_class = $element_styling->get_class_string( 'container' );

			$output  = '';
			$output .= $style_tag;
			$output .= "<div {$meta['custom_el_id']} class='{$container_class}'>";

			if( strpos( $display, 'upsells' ) !== false )
			{
				$output .= avia_woocommerce_output_upsells( $count, $count );
			}

			if( strpos( $display, 'related' ) !== false )
			{
				$output .= avia_woocommerce_output_related_products( $count, $count );
			}

			$output .= '</div>';

				//	reset
			$avia_config['woocommerce']['catalog_product_visibility'] = 'use_default';

			return $output;
		}
	}
}
