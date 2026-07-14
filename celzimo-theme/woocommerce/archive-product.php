<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @package Celzimo_Veste
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<!-- Products Catalog Section -->
<section id="productos" class="products-section">
    <div class="container catalog-layout">
        
        <!-- Sidebar Filters -->
        <aside class="catalog-sidebar">
            <div class="filter-group">
                <h3>Categoría</h3>
                <ul class="filter-list">
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="text-decoration: none; color: var(--color-text-dark); font-weight: <?php echo !is_product_category() ? '600' : '400'; ?>;">Todos</a></li>
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                    ) );
                    foreach ( $categories as $cat ) {
                        if ($cat->slug === 'uncategorized' || $cat->slug === 'sin-categorizar') continue;
                        $is_active = is_product_category( $cat->slug );
                        echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '" style="text-decoration: none; color: var(--color-text-dark); font-weight: ' . ($is_active ? '600' : '400') . ';">' . esc_html( $cat->name ) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            
            <div class="filter-group">
                <h3>Precio Máximo</h3>
                <div style="font-size:0.85rem; color:var(--color-text-light); margin-top: 10px;">
                    Filtrado dinámico activo en WooCommerce.
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="catalog-main">
            <div class="section-header">
                <h2 id="catalog-title">
                    <?php
                    if ( is_product_category() ) {
                        single_term_title();
                    } else {
                        echo 'Novedades';
                    }
                    ?>
                </h2>
                <p id="catalog-count">
                    <?php
                    global $wp_query;
                    $count = $wp_query->found_posts;
                    echo 'Mostrando ' . $count . ' resultados';
                    ?>
                </p>
            </div>
            
            <div class="products-grid" id="products-container">
                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) :
                        the_post();
                        global $product;
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'main-product-img' ) ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/placeholder.png" alt="<?php the_title(); ?>" class="main-product-img">
                                    <?php endif; ?>
                                </a>
                                
                                <button class="wishlist-btn" aria-label="Añadir a favoritos">
                                    <i class="ti ti-heart"></i>
                                </button>
                                
                                <?php
                                // Promo/Discount Banner
                                $display_price = 0;
                                $display_regular_price = 0;
                                $is_on_sale = $product->is_on_sale();

                                if ( $product->is_type( 'variable' ) ) {
                                    $display_price = $product->get_variation_price( 'min', true );
                                    $display_regular_price = $product->get_variation_regular_price( 'min', true );
                                    if ( $display_regular_price <= $display_price ) {
                                        $is_on_sale = false;
                                    }
                                } else {
                                    $display_price = $product->get_price();
                                    $display_regular_price = $product->get_regular_price();
                                }

                                if ( $is_on_sale && $display_regular_price > 0 ) {
                                    $discount_percent = round( ( ( $display_regular_price - $display_price ) / $display_regular_price ) * 100 );
                                    ?>
                                    <div class="promo-banner">
                                        <div class="promo-left">
                                            <span class="promo-text">OFERTA</span>
                                        </div>
                                        <div class="promo-right">
                                            <span class="promo-discount"><?php echo $discount_percent; ?></span>
                                            <div class="promo-percent">
                                                <span class="percent-sign">%</span>
                                                <span class="off-text">OFF</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="add-to-cart-overlay">
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
                                       class="btn btn-primary btn-block <?php echo $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ajax_add_to_cart' : ''; ?>" 
                                       data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
                                       data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" 
                                       aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>" 
                                       rel="nofollow">
                                       <?php echo esc_html( $product->add_to_cart_text() ); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="product-info-card" style="padding: 15px 0;">
                                <span class="brand" style="font-size: 0.75rem; color: var(--color-text-light); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">
                                    <?php echo esc_html( $product->get_attribute( 'pa_brand' ) ? $product->get_attribute( 'pa_brand' ) : 'Carola Miccono' ); ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" style="text-decoration: none;">
                                    <h3 class="product-title-card" style="font-size: 1rem; color: var(--color-text-dark); margin-bottom: 8px; font-weight: 500;"><?php the_title(); ?></h3>
                                </a>
                                <div class="product-price-card">
                                    <?php
                                    if ( $is_on_sale ) {
                                        ?>
                                        <span class="sale-price" style="font-weight: 600; color: #ef4444;"><?php echo wc_price( $display_price ); ?></span>
                                        <span class="original-price" style="text-decoration: line-through; color: #9ca3af; font-size: 0.85rem; margin-left: 8px;"><?php echo wc_price( $display_regular_price ); ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="current-price" style="font-weight: 600;"><?php echo wc_price( $display_price ); ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                                
                                <?php
                                // Generate color thumbs / gallery images
                                $gallery_images = array();
                                $main_image_id = $product->get_image_id();
                                if ( $main_image_id ) {
                                    $gallery_images[] = array(
                                        'thumb' => wp_get_attachment_image_url( $main_image_id, 'thumbnail' ),
                                        'full'  => wp_get_attachment_image_url( $main_image_id, 'woocommerce_thumbnail' )
                                    );
                                }
                                
                                $attachment_ids = $product->get_gallery_image_ids();
                                if ( !empty( $attachment_ids ) ) {
                                    $limit = min( count( $attachment_ids ), 3 );
                                    for ( $i = 0; $i < $limit; $i++ ) {
                                        $gallery_images[] = array(
                                            'thumb' => wp_get_attachment_image_url( $attachment_ids[$i], 'thumbnail' ),
                                            'full'  => wp_get_attachment_image_url( $attachment_ids[$i], 'woocommerce_thumbnail' )
                                        );
                                    }
                                }
                                
                                if ( count( $gallery_images ) >= 1 ) :
                                    ?>
                                    <div class="product-colors-container" style="display: flex; gap: 5px; margin-top: 8px; margin-bottom: 8px;">
                                        <?php foreach ( $gallery_images as $index => $img ) : ?>
                                            <img src="<?php echo esc_url( $img['thumb'] ); ?>" 
                                                 class="color-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                 alt="Vista <?php echo $index + 1; ?>" 
                                                 onclick="selectColor(event, this, '<?php echo esc_url( $img['full'] ); ?>', <?php echo $index; ?>)" 
                                                 onmouseenter="selectColor(event, this, '<?php echo esc_url( $img['full'] ); ?>', <?php echo $index; ?>)"
                                                 style="width: 30px; height: 40px; object-fit: cover; cursor: pointer; border-radius: 2px; border: 1px solid #e5e7eb; transition: all 0.2s;">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                } else {
                    echo '<p style="text-align:center; width:100%; color:var(--text-muted);">No se encontraron productos.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</section>

<?php
get_footer();
