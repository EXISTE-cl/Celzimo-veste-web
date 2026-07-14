<?php
/**
 * The front page template file
 *
 * @package Celzimo_Veste
 */

get_header();
?>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('<?php echo esc_url( get_template_directory_uri() ); ?>/assets/hero.png');">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h2>Elegancia Atemporal</h2>
            <p>Descubre la nueva colección de lujo. Materiales premium y diseño excepcional.</p>
            <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' ) ); ?>" class="btn btn-primary">Comprar Colección</a>
        </div>
    </section>

    <!-- Banners Grid Section -->
    <section class="banners-section container">
        <div class="banners-grid">
            <a href="<?php echo esc_url( celzimo_get_category_link( 'chaquetas' ) ); ?>" class="banner-item tall">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/jacket.png" alt="Chaquetas">
                <div class="banner-content">
                    <span class="banner-subtitle">New Arrivals</span>
                    <h3>Chaquetas</h3>
                </div>
            </a>
            <div class="banners-right">
                <div class="banners-row-top">
                    <a href="<?php echo esc_url( celzimo_get_category_link( 'jeans' ) ); ?>" class="banner-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_2_1782608801421.png" alt="Todo Hombre">
                        <div class="banner-content">
                            <span class="banner-subtitle">New Arrivals</span>
                            <h3>Todo Hombre</h3>
                        </div>
                    </a>
                    <a href="<?php echo esc_url( celzimo_get_category_link( 'jeans' ) ); ?>" class="banner-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_4_1782608818924.png" alt="Todo Mujer">
                        <div class="banner-content">
                            <span class="banner-subtitle">New Arrivals</span>
                            <h3>Todo Mujer</h3>
                        </div>
                    </a>
                </div>
                <a href="<?php echo esc_url( celzimo_get_category_link( 'camisas' ) ); ?>" class="banner-item wide">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_3_1782608811379.png" alt="Polerones y Sweaters">
                    <div class="banner-content">
                        <span class="banner-subtitle">New Arrivals</span>
                        <h3>Polerones y Sweaters</h3>
                    </div>
                </a>
            </div>
        </div>

        <div class="categories-row">
            <a href="<?php echo esc_url( celzimo_get_category_link( 'jeans' ) ); ?>" class="category-square-link">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/jeans.png" alt="Jeans Hombre">
                <h4>JEANS HOMBRE</h4>
                <span>Ver colección</span>
            </a>
            <a href="<?php echo esc_url( celzimo_get_category_link( 'chaquetas' ) ); ?>" class="category-square-link">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/jacket.png" alt="Chaquetas Hombre">
                <h4>CHAQUETAS HOMBRE</h4>
                <span>Ver colección</span>
            </a>
            <a href="<?php echo esc_url( celzimo_get_category_link( 'jeans' ) ); ?>" class="category-square-link">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_4_1782608818924.png" alt="Jeans Mujer">
                <h4>JEANS MUJER</h4>
                <span>Ver colección</span>
            </a>
            <a href="<?php echo esc_url( celzimo_get_category_link( 'camisas' ) ); ?>" class="category-square-link">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_1_1782608791344.png" alt="Poleras Hombre">
                <h4>POLERAS HOMBRE</h4>
                <span>Ver colección</span>
            </a>
            <a href="<?php echo esc_url( celzimo_get_category_link( 'camisas' ) ); ?>" class="category-square-link">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_2_1782608801421.png" alt="Poleras Mujer">
                <h4>POLERAS MUJER</h4>
                <span>Ver colección</span>
            </a>
        </div>
    </section>

    <!-- Top Ventas Section -->
    <section class="top-sales-section container" style="margin-top: 60px;">
        <h2 style="text-align: center; font-family: var(--font-secondary); font-size: 1.8rem; margin-bottom: 30px; letter-spacing: 1px;">TOP VENTAS</h2>
        <div class="products-grid" id="top-ventas-container">
            <?php
            if ( class_exists( 'WooCommerce' ) ) {
                // Query Best Selling Products in WooCommerce
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'meta_key'       => 'total_sales',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                );
                $loop = new WP_Query( $args );
                if ( $loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
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
                    wp_reset_postdata();
                } else {
                    echo '<p style="text-align:center; width:100%; color:var(--text-muted);">Importa productos en WooCommerce para verlos aquí.</p>';
                }
            } else {
                echo '<p style="text-align:center; width:100%; color:var(--text-muted);">Activa WooCommerce para desplegar productos.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Productos Destacados Section -->
    <section class="featured-section container" style="margin-top: 60px; margin-bottom: 60px;">
        <h2 style="text-align: center; font-family: var(--font-secondary); font-size: 1.8rem; margin-bottom: 30px; letter-spacing: 1px;">PRODUCTOS DESTACADOS</h2>
        <div class="products-grid" id="destacados-container">
            <?php
            if ( class_exists( 'WooCommerce' ) ) {
                // Query Featured Products in WooCommerce
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                        ),
                    ),
                );
                $loop = new WP_Query( $args );
                if ( $loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
                        global $product;
                        ?>
                        <div class="product-card">
                            <a href="<?php the_permalink(); ?>">
                                <div class="product-image">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/placeholder.png" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <span class="brand"><?php echo esc_html( $product->get_attribute( 'brand' ) ? $product->get_attribute( 'brand' ) : 'Celzimo Veste' ); ?></span>
                                    <h3><?php the_title(); ?></h3>
                                    <div class="price-container">
                                        <span class="price"><?php echo $product->get_price_html(); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                } else {
                    echo '<p style="text-align:center; width:100%; color:var(--text-muted);">Marca productos como destacados en WooCommerce para verlos aquí.</p>';
                }
            } else {
                echo '<p style="text-align:center; width:100%; color:var(--text-muted);">Activa WooCommerce para desplegar productos destacados.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Instagram Feed Section -->
    <section class="instagram-section">
        <div class="container">
            <div class="section-header instagram-header">
                <h2>Síguenos en Instagram</h2>
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="instagram-handle">@celzimoveste</a>
            </div>
            <div class="instagram-grid">
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="ig-post">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_1_1782608791344.png" alt="Instagram Post 1">
                    <div class="ig-overlay">
                        <span class="ig-icon"><i class="ti ti-heart"></i> 1.2k</span>
                        <span class="ig-icon"><i class="ti ti-message-circle"></i> 45</span>
                    </div>
                </a>
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="ig-post">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_2_178260801421.png" alt="Instagram Post 2" onerror="this.src='<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_2_1782608801421.png'">
                    <div class="ig-overlay">
                        <span class="ig-icon"><i class="ti ti-heart"></i> 850</span>
                        <span class="ig-icon"><i class="ti ti-message-circle"></i> 22</span>
                    </div>
                </a>
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="ig-post">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_3_1782608811379.png" alt="Instagram Post 3">
                    <div class="ig-overlay">
                        <span class="ig-icon"><i class="ti ti-heart"></i> 2.1k</span>
                        <span class="ig-icon"><i class="ti ti-message-circle"></i> 115</span>
                    </div>
                </a>
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="ig-post">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_4_1782608818924.png" alt="Instagram Post 4">
                    <div class="ig-overlay">
                        <span class="ig-icon"><i class="ti ti-heart"></i> 1.5k</span>
                        <span class="ig-icon"><i class="ti ti-message-circle"></i> 67</span>
                    </div>
                </a>
            </div>
            <div class="instagram-action" style="text-align: center; margin-top: 40px;">
                <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener" class="btn btn-outline">Descubrir más</a>
            </div>
        </div>
    </section>

<?php
get_footer();
