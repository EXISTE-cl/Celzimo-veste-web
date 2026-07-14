<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Top Bar -->
    <div class="top-bar">
        Envíos gratis en pedidos superiores a $150.000 | Devoluciones extendidas
    </div>

    <!-- Header & Nav -->
    <header id="main-header">
        <div class="container header-container">
            <!-- Mobile Menu Toggle -->
            <button class="icon-btn mobile-toggle" aria-label="Menu">
                <i class="ti ti-menu-2"></i>
            </button>

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/logo.jpg" alt="<?php bloginfo( 'name' ); ?> Logo" class="main-logo-img">
            </a>

            <!-- Desktop Nav -->
            <nav class="desktop-nav">
                <ul class="nav-list">
                    <li class="nav-item has-dropdown">
                        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' ) ); ?>"><?php esc_html_e( 'TIENDA', 'celzimo-theme' ); ?></a>
                        <div class="mega-menu">
                            <div class="mega-menu-content container image-cards-menu">
                                <a href="<?php echo esc_url( celzimo_get_category_link( 'jeans' ) ); ?>" class="mega-image-card" data-category="jeans">
                                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/jeans.png" alt="Jeans">
                                    <div class="card-overlay">
                                        <h3>Jeans</h3>
                                        <span class="view-collection"><?php esc_html_e( 'Ver colección', 'celzimo-theme' ); ?> &rarr;</span>
                                    </div>
                                </a>
                                <a href="<?php echo esc_url( celzimo_get_category_link( 'chaquetas' ) ); ?>" class="mega-image-card" data-category="chaquetas">
                                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/jacket.png" alt="Chaquetas">
                                    <div class="card-overlay">
                                        <h3>Chaquetas</h3>
                                        <span class="view-collection"><?php esc_html_e( 'Ver colección', 'celzimo-theme' ); ?> &rarr;</span>
                                    </div>
                                </a>
                                <a href="<?php echo esc_url( celzimo_get_category_link( 'camisas' ) ); ?>" class="mega-image-card" data-category="camisas">
                                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/cv_ig_1_1782608791344.png" alt="Camisas">
                                    <div class="card-overlay">
                                        <h3>Camisas</h3>
                                        <span class="view-collection"><?php esc_html_e( 'Ver colección', 'celzimo-theme' ); ?> &rarr;</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'NECESITAS AYUDA', 'celzimo-theme' ); ?></a>
                    </li>
                </ul>
            </nav>

            <!-- Actions -->
            <div class="header-actions">
                <button class="icon-btn" aria-label="Buscar"><i class="ti ti-search"></i></button>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="icon-btn hide-mobile" id="open-login-btn-link" aria-label="Mi Cuenta">
                        <i class="ti ti-user"></i>
                    </a>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-btn cart-btn" id="open-cart" aria-label="Carrito">
                        <i class="ti ti-shopping-cart"></i>
                        <span class="cart-count" id="cart-badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                <?php else : ?>
                    <button class="icon-btn hide-mobile" id="open-login-btn" aria-label="Mi Cuenta"><i class="ti ti-user"></i></button>
                    <button class="icon-btn cart-btn" id="open-cart" aria-label="Carrito">
                        <i class="ti ti-shopping-cart"></i>
                        <span class="cart-count" id="cart-badge">0</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Fallback Cart Drawer for JS state / WooCommerce Side Cart compatibility -->
    <div class="cart-overlay" id="cart-overlay"></div>
    <div class="cart-drawer" id="cart-drawer">
        <div class="cart-header">
            <h3><?php esc_html_e( 'Tu Carrito', 'celzimo-theme' ); ?> (<span id="drawer-cart-count"><?php echo class_exists( 'WooCommerce' ) ? WC()->cart->get_cart_contents_count() : '0'; ?></span>)</h3>
            <button class="close-btn" id="close-cart"><i class="ti ti-x"></i></button>
        </div>
        
        <div class="cart-items" id="cart-items-container">
            <?php if ( class_exists( 'WooCommerce' ) && ! WC()->cart->is_empty() ) : ?>
                <!-- WooCommerce items can be output here or handled via AJAX -->
                <?php woocommerce_mini_cart(); ?>
            <?php else : ?>
                <div class="empty-cart-msg" id="empty-cart-msg">
                    <i class="ti ti-shopping-bag"></i>
                    <p><?php esc_html_e( 'Tu carrito está vacío', 'celzimo-theme' ); ?></p>
                    <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' ) ); ?>" class="btn btn-outline" id="continue-shopping"><?php esc_html_e( 'Continuar Comprando', 'celzimo-theme' ); ?></a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <span><?php esc_html_e( 'Subtotal', 'celzimo-theme' ); ?></span>
                <span class="total-amount" id="cart-total-amount">
                    <?php echo class_exists( 'WooCommerce' ) ? WC()->cart->get_cart_total() : '$0'; ?>
                </span>
            </div>
            <p class="taxes-msg"><?php esc_html_e( 'Impuestos calculados en el checkout.', 'celzimo-theme' ); ?></p>
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary btn-block checkout-btn"><?php esc_html_e( 'Proceder al Pago', 'celzimo-theme' ); ?></a>
            <?php else : ?>
                <button class="btn btn-primary btn-block checkout-btn" id="checkout-btn" disabled><?php esc_html_e( 'Proceder al Pago', 'celzimo-theme' ); ?></button>
            <?php endif; ?>
        </div>
    </div>
