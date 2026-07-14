<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				if ( $_product->is_type( 'variation' ) ) {
					$parent_product_id = $_product->get_parent_id();
					$parent_product_obj = wc_get_product( $parent_product_id );
					$base_name = $parent_product_obj ? $parent_product_obj->get_name() : $_product->get_name();
				} else {
					$base_name = $_product->get_name();
				}
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $base_name, $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item celzimo-restructured <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<a role="button" href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">&times;</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) )
						),
						$cart_item_key
					);
					?>
					
					<div class="celzimo-mc-img">
						<?php if ( empty( $product_permalink ) ) : ?>
							<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						<?php endif; ?>
					</div>

					<div class="celzimo-mc-info">
						<?php if ( empty( $product_permalink ) ) : ?>
							<?php echo wp_kses_post( $product_name ); ?>
						<?php else : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo wp_kses_post( $product_name ); ?>
							</a>
						<?php endif; ?>

						<?php
						// Output editable swatches
						if ( $_product->is_type( 'variation' ) ) {
							$parent_product = wc_get_product( $_product->get_parent_id() );
							if ( $parent_product && $parent_product->is_type( 'variable' ) ) {
								$attributes = $parent_product->get_variation_attributes();
								$selected_attributes = $_product->get_variation_attributes();
								
								echo '<div class="celzimo-mc-swatches-container" data-cart_item_key="' . esc_attr($cart_item_key) . '" data-product_id="' . esc_attr($parent_product->get_id()) . '">';
								
								// Pre-fetch variations to find images for colors
								$available_variations = $parent_product->get_available_variations();

								foreach ( $attributes as $attribute_name => $options ) {
									$selected = isset( $selected_attributes['attribute_' . $attribute_name] ) ? $selected_attributes['attribute_' . $attribute_name] : '';
									
									$is_color = ( strpos( strtolower($attribute_name), 'color' ) !== false );

									if ( $is_color ) {
										continue; // Skip rendering color swatch as requested
									}

									echo '<div class="celzimo-mc-attr-row">';
									echo '<span class="celzimo-mc-attr-label">' . wc_attribute_label( $attribute_name ) . ':</span>';
									echo '<div class="celzimo-mc-attr-options">';
									foreach ( $options as $option ) {
										$is_selected = ( sanitize_title( $selected ) === sanitize_title( $option ) ) ? 'selected' : '';
										
										$swatch_content = esc_html($option);
										$swatch_style = '';
										
										if ( $is_color ) {
											// Try to find the image for this color
											$color_image = '';
											foreach ( $available_variations as $var ) {
												if ( isset( $var['attributes']['attribute_' . $attribute_name] ) && sanitize_title( $var['attributes']['attribute_' . $attribute_name] ) === sanitize_title( $option ) ) {
													if ( ! empty( $var['image']['src'] ) ) {
														$color_image = $var['image']['thumb_src'] ? $var['image']['thumb_src'] : $var['image']['src'];
														break;
													}
												}
											}
											if ( $color_image ) {
												$swatch_content = '';
												$swatch_style = 'background-image: url(' . esc_url($color_image) . ') !important; background-size: cover !important; background-position: center !important; width: 28px !important; height: 28px !important; padding: 0 !important; min-width: 28px !important; border: 1px solid #ccc !important; color: transparent !important;';
											}
										}

										echo '<button type="button" class="celzimo-mc-swatch ' . esc_attr($is_selected) . '" data-attribute="' . esc_attr('attribute_' . $attribute_name) . '" data-value="' . esc_attr($option) . '" style="' . esc_attr($swatch_style) . '" title="' . esc_attr($option) . '">' . $swatch_content . '</button>';
									}
									echo '</div></div>';
								}
								echo '</div>';
							} else {
								echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						} else {
							echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>

						<?php 
						// We need this span for the JS to find the quantity, but it's hidden by JS
						echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
					</div>

					<div class="celzimo-mc-price-right">
						<?php echo $product_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<p class="woocommerce-mini-cart__total total">
		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
