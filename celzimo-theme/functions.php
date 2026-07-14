<?php
/**
 * Celzimo Veste Theme — functions.php (VERSIÓN ESTABLE BASE)
 *
 * @package Celzimo_Veste
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ==========================================================================
   PRODUCT SPECS FROM DESCRIPTION (PDP — all catalog)
   ========================================================================== */
$celzimo_product_specs = get_template_directory() . '/inc/product-specs.php';
if ( file_exists( $celzimo_product_specs ) ) {
    require_once $celzimo_product_specs;
}

/* ==========================================================================
   THEME SETUP
   ========================================================================== */

function celzimo_theme_setup() {
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus([
        'menu-primary'         => 'Primary Menu',
        'menu-footer-customer' => 'Footer Customer Service',
        'menu-footer-legal'    => 'Footer Legal Menu',
    ]);
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption','style','script'] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'celzimo_theme_setup' );

/* ==========================================================================
   ENQUEUE SCRIPTS & STYLES
   ========================================================================== */

function celzimo_theme_scripts() {
    wp_enqueue_style( 'tabler-icons',        'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css', [], '1.0.0' );
    wp_enqueue_style( 'celzimo-google-fonts','https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap', [], null );
    wp_enqueue_style( 'celzimo-theme-style', get_stylesheet_uri(), [], '2.0.0' );
    wp_enqueue_style( 'celzimo-custom-style', get_template_directory_uri() . '/css/style.css', ['celzimo-theme-style'], '2.0.1' );
    if ( ! is_admin() ) {
        wp_enqueue_script( 'celzimo-theme-navigation', get_template_directory_uri() . '/js/navigation.js', [], '1.0.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'celzimo_theme_scripts' );

/* ==========================================================================
   CART STYLES — Inline (garantiza aplicación sin importar caché)
   Aplica a: WooCommerce mini-cart widget + Side Cart WooCommerce (xoo-wsc)
   ========================================================================== */
add_action( 'wp_head', 'celzimo_cart_inline_styles', 99 );
function celzimo_cart_inline_styles() {
    ?>
    <style id="celzimo-cart-css">
    /* =====================================================================
       WOOCOMMERCE MINI-CART (widget dropdown nativo del header)
       ===================================================================== */
    .widget_shopping_cart,
    .woocommerce .widget_shopping_cart {
        font-family: 'Inter', sans-serif;
    }
    /* Panel / dropdown container */
    .widget_shopping_cart_content {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        padding: 0;
        overflow: hidden;
        min-width: 340px;
        max-width: 380px;
    }
    /* Título del carrito */
      .widget_shopping_cart .widgettitle {
          font-family: 'Inter', sans-serif !important;
          font-size: 16px !important;
          font-weight: 700 !important;
          color: #1a1a2e !important;
          margin: 0 0 15px 0 !important;
          padding: 0 20px !important;
          text-transform: uppercase !important;
          letter-spacing: -0.02em !important;
      }
      
      /* Contenedor de controles de cantidad */;
    /* Nombre producto */
    .woocommerce-mini-cart-item a:not(.remove) {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        text-decoration: none !important;
        display: block !important;
        line-height: 1.35 !important;
        margin-bottom: 4px !important;
    }
    /* Cantidad × Precio */
    .woocommerce-mini-cart-item .quantity {
        font-size: 12px !important;
        color: #888 !important;
        display: block !important;
        margin-top: 4px !important;
    }
    .woocommerce-mini-cart-item .quantity .woocommerce-Price-amount {
        font-weight: 700 !important;
        color: #1a1a2e !important;
        font-size: 13px !important;
    }
    /* Botón eliminar × */
    .woocommerce-mini-cart-item .remove {
        position: absolute !important;
        top: 10px !important;
        right: 14px !important;
        font-size: 16px !important;
        color: #ccc !important;
        text-decoration: none !important;
        font-weight: 400 !important;
        line-height: 1 !important;
        width: 20px !important;
        height: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: none !important;
        border: none !important;
        cursor: pointer !important;
    }
    .woocommerce-mini-cart-item .remove:hover { color: #e53935 !important; }
    /* Total */
    .woocommerce-mini-cart__total,
    .total {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 14px 20px !important;
        border-top: 2px solid #f0f0f0 !important;
        margin: 0 !important;
    }
    .woocommerce-mini-cart__total strong {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #666 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }
    .woocommerce-mini-cart__total .woocommerce-Price-amount {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #1a1a2e !important;
    }
    /* Botones */
    .woocommerce-mini-cart__buttons,
    .widget_shopping_cart_content .buttons {
        padding: 14px 20px 18px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 8px !important;
    }
    /* Botón Finalizar compra */
    .woocommerce-mini-cart__buttons .checkout,
    .widget_shopping_cart_content .checkout,
    a.checkout.wc-forward {
        display: block !important;
        width: 100% !important;
        background: #1a1a2e !important;
        color: #fff !important;
        text-align: center !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        padding: 14px 20px !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        border: none !important;
        cursor: pointer !important;
        transition: background 0.2s !important;
        box-sizing: border-box !important;
        line-height: 1 !important;
    }
    a.checkout.wc-forward:hover { background: #2d2d50 !important; color: #fff !important; }
    /* Botón Ver carrito */
    .woocommerce-mini-cart__buttons .button.wc-forward:not(.checkout),
    .widget_shopping_cart_content .cart.wc-forward,
    a.cart.wc-forward {
        display: block !important;
        width: 100% !important;
        text-align: center !important;
        font-size: 12px !important;
        color: #888 !important;
        text-decoration: none !important;
        padding: 6px 0 !important;
        background: none !important;
        border: none !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        transition: color 0.15s !important;
        box-sizing: border-box !important;
    }
    a.cart.wc-forward:hover { color: #1a1a2e !important; text-decoration: underline !important; }

    /* =====================================================================
       SIDE CART WOOCOMMERCE (xoo-wsc) — Panel deslizante
       ===================================================================== */
    .xoo-wsc-modal {
        width: 380px !important;
        max-width: 100vw !important;
        font-family: 'Inter', sans-serif !important;
        background: #fff !important;
        box-shadow: -4px 0 40px rgba(0,0,0,0.2) !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .xoo-wsc-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 22px 24px 18px !important;
        border-bottom: 1px solid #f0f0f0 !important;
    }
    .xoo-wsc-header h2,
    .xoo-wsc-hdr-title {
        font-size: 22px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        margin: 0 !important;
        letter-spacing: -0.02em !important;
        text-transform: none !important;
    }
    .xoo-wsc-close, .xoo-wsc-hdr-close {
        background: #1a1a2e !important;
        border: none !important;
        color: #fff !important;
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        padding: 0 !important;
        transition: background 0.2s, transform 0.15s !important;
    }
    .xoo-wsc-close:hover { background: #2d2d50 !important; transform: rotate(90deg) !important; }
    /* Productos */
    .xoo-wsc-bskt, .xoo-wsc-items, .xoo-wsc-cart-content { padding: 0 !important; }
    .xoo-wsc-bskt-product, .xoo-wsc-item, .xoo-wsc-product {
        display: grid !important;
        grid-template-columns: 72px 1fr !important;
        gap: 12px !important;
        padding: 16px 20px !important;
        border-bottom: 1px solid #f5f5f5 !important;
        position: relative !important;
        align-items: start !important;
    }
    .xoo-wsc-bskt-img, .xoo-wsc-img-col { width: 72px !important; height: 72px !important; }
    .xoo-wsc-bskt-img img, .xoo-wsc-img-col img, .xoo-wsc-item img {
        width: 72px !important;
        height: 72px !important;
        object-fit: cover !important;
        border-radius: 8px !important;
        border: 1px solid #eee !important;
        display: block !important;
    }
    .xoo-wsc-bskt-pname, .xoo-wsc-pname {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        line-height: 1.35 !important;
        margin: 0 0 4px !important;
    }
    .xoo-wsc-smeta, .xoo-wsc-bskt-smeta { font-size: 11px !important; color: #888 !important; }
    .xoo-wsc-bskt-price .woocommerce-Price-amount,
    .xoo-wsc-price .woocommerce-Price-amount { font-size: 14px !important; font-weight: 700 !important; color: #1a1a2e !important; }
    /* Cantidad — botones cuadrados oscuros */
    .xoo-wsc-bskt-qty, .xoo-wsc-qty {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        margin-top: 8px !important;
    }
    .xoo-wsc-qt-btn, .xoo-wsc-qty button, .xoo-wsc-bskt-qty button {
        width: 32px !important;
        height: 32px !important;
        background: #1a1a2e !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
        font-size: 18px !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        line-height: 1 !important;
        transition: background 0.15s !important;
    }
    .xoo-wsc-qt-btn:hover { background: #2d2d50 !important; }
    .xoo-wsc-qty input, .xoo-wsc-bskt-qty input {
        width: 36px !important;
        height: 32px !important;
        text-align: center !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        padding: 0 !important;
    }
    /* Eliminar */
    .xoo-wsc-smclose, .xoo-wsc-bskt-smclose {
        width: 32px !important;
        height: 32px !important;
        background: #f5f5f5 !important;
        border: none !important;
        border-radius: 4px !important;
        color: #888 !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        position: static !important;
        margin-left: 4px !important;
        font-size: 14px !important;
        transition: background 0.15s, color 0.15s !important;
    }
    .xoo-wsc-smclose:hover { background: #ffe5e5 !important; color: #c0392b !important; }
    /* Footer */
    .xoo-wsc-ft, .xoo-wsc-footer {
        padding: 16px 20px 20px !important;
        border-top: 2px solid #f0f0f0 !important;
    }
    .xoo-wsc-subtotal, .xoo-wsc-ft-subtotal {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding-bottom: 14px !important;
        margin-bottom: 14px !important;
        border-bottom: 1px solid #f5f5f5 !important;
    }
    .xoo-wsc-ft-subtotal span:first-child, .xoo-wsc-subtotal .xoo-wsc-st-label {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #666 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }
    .xoo-wsc-ft-subtotal .amount, .xoo-wsc-subtotal .xoo-wsc-st-price,
    .xoo-wsc-subtotal .woocommerce-Price-amount {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #1a1a2e !important;
    }
    /* Botón finalizar compra */
    .xoo-wsc-ft-btn-checkout, a.xoo-wsc-ft-btn-checkout {
        display: block !important;
        width: 100% !important;
        background: #1a1a2e !important;
        color: #fff !important;
        text-align: center !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        padding: 15px !important;
        border-radius: 6px !important;
        border: none !important;
        text-decoration: none !important;
        margin-bottom: 8px !important;
        transition: background 0.2s !important;
        box-sizing: border-box !important;
    }
    .xoo-wsc-ft-btn-checkout:hover { background: #2d2d50 !important; color: #fff !important; }
    .xoo-wsc-ft-btn-cart, a.xoo-wsc-ft-btn-cart {
        display: block !important;
        text-align: center !important;
        font-size: 12px !important;
        color: #999 !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 6px 0 !important;
        background: none !important;
        border: none !important;
        cursor: pointer !important;
    }
    .xoo-wsc-ft-btn-cart:hover { color: #1a1a2e !important; text-decoration: underline !important; }
    /* Overlay */
    .xoo-wsc-overlay { background: rgba(10,10,20,0.5) !important; backdrop-filter: blur(4px) !important; }
    /* Botón flotante */
    .xoo-wsc-open-btn { background: #1a1a2e !important; border-radius: 50% !important; }
    .xoo-wsc-basket-count { background: #c5a880 !important; border: 2px solid #fff !important; font-weight: 800 !important; }
    @media (max-width: 480px) {
        .xoo-wsc-modal { width: 100vw !important; }
        .widget_shopping_cart_content { min-width: 100vw; }
    }
    </style>
    <?php
}

/* ==========================================================================
   AJAX — Actualizar cantidad de item en mini-cart
   ========================================================================== */

add_action( 'wp_ajax_celzimo_update_cart_qty',        'celzimo_ajax_update_cart_qty' );
add_action( 'wp_ajax_nopriv_celzimo_update_cart_qty', 'celzimo_ajax_update_cart_qty' );

function celzimo_ajax_update_cart_qty() {
    check_ajax_referer( 'celzimo_cart_nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
    $qty           = max( 0, intval( $_POST['qty'] ?? 0 ) );

    if ( ! $cart_item_key ) wp_send_json_error( 'Invalid key' );

    WC()->cart->set_quantity( $cart_item_key, $qty, true );
    WC()->cart->calculate_totals();

    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', [
        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
    ] );

    wp_send_json_success( [
        'fragments'  => $fragments,
        'cart_hash'  => WC()->cart->get_cart_hash(),
        'cart_count' => WC()->cart->get_cart_contents_count(),
    ] );
}

add_action( 'wp_ajax_celzimo_update_cart_variation',        'celzimo_ajax_update_cart_variation' );
add_action( 'wp_ajax_nopriv_celzimo_update_cart_variation', 'celzimo_ajax_update_cart_variation' );

function celzimo_ajax_update_cart_variation() {
    check_ajax_referer( 'celzimo_cart_nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
    $product_id    = intval( $_POST['product_id'] ?? 0 );
    
    // attributes might be passed as an object/array via POST
    $attributes    = isset( $_POST['attributes'] ) ? wc_clean( $_POST['attributes'] ) : [];

    if ( ! $cart_item_key || ! $product_id || empty( $attributes ) ) {
        wp_send_json_error( 'Datos inválidos' );
    }

    $cart = WC()->cart;
    $cart_item = $cart->get_cart_item( $cart_item_key );

    if ( ! $cart_item ) {
        wp_send_json_error( 'El ítem ya no está en el carrito.' );
    }

    $quantity = $cart_item['quantity'];

    // Ensure incoming attributes have the 'attribute_' prefix and merge with existing ones
    $existing_attributes = isset( $cart_item['variation'] ) ? $cart_item['variation'] : [];
    $new_attributes = [];
    foreach ( $attributes as $key => $val ) {
        $key_raw = (string) $key;
        if ( 0 === strpos( $key_raw, 'attribute_' ) ) {
            $prefix_key = 'attribute_' . sanitize_title( substr( $key_raw, 10 ) );
        } else {
            $prefix_key = 'attribute_' . sanitize_title( $key_raw );
        }
        $new_attributes[ $prefix_key ] = $val;
    }
    $merged_attributes = array_merge( $existing_attributes, $new_attributes );
    // Normalizar claves existentes (attribute_Talla → attribute_talla).
    $normalized = [];
    foreach ( $merged_attributes as $mk => $mv ) {
        $mk_s = (string) $mk;
        if ( 0 === strpos( $mk_s, 'attribute_' ) ) {
            $normalized[ 'attribute_' . sanitize_title( substr( $mk_s, 10 ) ) ] = $mv;
        } else {
            $normalized[ 'attribute_' . sanitize_title( $mk_s ) ] = $mv;
        }
    }
    $merged_attributes = $normalized;

    $data_store   = WC_Data_Store::load( 'product' );
    $variation_id = $data_store->find_matching_product_variation(
        wc_get_product( $product_id ),
        $merged_attributes
    );

    if ( ! $variation_id ) {
        wp_send_json_error( 'Esta combinación no está disponible. Attr: ' . wp_json_encode($attributes) . ' | Prod: ' . $product_id );
    }

    $variation = wc_get_product( $variation_id );
    if ( ! $variation || ! $variation->is_purchasable() || ! $variation->is_in_stock() ) {
        wp_send_json_error( 'Sin stock' );
    }

    // Remove old variation and add new
    $cart->remove_cart_item( $cart_item_key );
    $cart->add_to_cart( $product_id, $quantity, $variation_id, $merged_attributes );
    $cart->calculate_totals();

    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', [
        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
    ] );

    wp_send_json_success( [
        'fragments'  => $fragments,
        'cart_hash'  => WC()->cart->get_cart_hash(),
    ] );
}

/* ==========================================================================
   JS — Inyectar botones −/+/🗑 en cada item del mini-cart
   ========================================================================== */

add_action( 'wp_footer', 'celzimo_minicart_qty_buttons', 99 );
function celzimo_minicart_qty_buttons() {
    $nonce = wp_create_nonce( 'celzimo_cart_nonce' );
    ?>
    <style id="celzimo-qty-btns-css">
    /* Contenedor de controles de cantidad */
    .celzimo-qty-controls {
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin-top: 8px !important;
    }
    /* Botón − (más claro) */
    .celzimo-qty-btn.celzimo-qty-minus {
        width: 30px !important;
        height: 30px !important;
        background: rgba(26,26,46,0.15) !important;
        color: #1a1a2e !important;
        border: none !important;
        border-radius: 4px !important;
        font-size: 20px !important;
        font-weight: 300 !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        line-height: 1 !important;
        transition: background 0.15s !important;
    }
    .celzimo-qty-btn.celzimo-qty-minus:hover {
        background: rgba(26,26,46,0.25) !important;
    }
    /* Número de cantidad */
    .celzimo-qty-num {
        width: 30px !important;
        height: 30px !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 4px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        background: #fff !important;
        min-width: 30px !important;
        text-align: center !important;
    }
    /* Botón + (oscuro — navy) */
    .celzimo-qty-btn.celzimo-qty-plus {
        width: 30px !important;
        height: 30px !important;
        background: #1a1a2e !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
        font-size: 20px !important;
        font-weight: 300 !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        line-height: 1 !important;
        transition: background 0.15s !important;
    }
    .celzimo-qty-btn.celzimo-qty-plus:hover { background: #2d2d50 !important; }
    /* Botón basura (navy) */
    .celzimo-qty-trash {
        width: 30px !important;
        height: 30px !important;
        background: #1a1a2e !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin-left: 2px !important;
        transition: background 0.15s !important;
    }
    .celzimo-qty-trash:hover { background: #a02020 !important; }
    .celzimo-qty-trash svg { width: 13px; height: 13px; display: block; }
    /* Ocultar el span de cantidad original */
    li.woocommerce-mini-cart-item .quantity.celzimo-qty-hidden { display: none !important; }
    /* Spinner durante update */
    .celzimo-qty-controls.loading { opacity: 0.5; pointer-events: none; }
    </style>

    <script>
    (function($){
        var AJAX_URL = '<?php echo esc_js( admin_url("admin-ajax.php") ); ?>';
        var NONCE    = '<?php echo esc_js( $nonce ); ?>';
        var trashSVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>';

        function updateCartQty(cartKey, qty, $controls) {
            // Preferir WC Cart Sync (WC_AJAX + debounce). Fallback al AJAX legacy del tema.
            if (window.WCCartSync && typeof window.WCCartSync.updateQty === 'function') {
                window.WCCartSync.updateQty(cartKey, qty, $controls);
                return;
            }
            $controls.addClass('loading');
            $.post(AJAX_URL, {
                action: 'celzimo_update_cart_qty',
                cart_item_key: cartKey,
                qty: qty,
                nonce: NONCE
            }, function(response) {
                $controls.removeClass('loading');
                if (response.success) {
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(selector, html) {
                            $(selector).replaceWith(html);
                        });
                    }
                    $(document.body).trigger('wc_fragment_refresh');
                    try {
                        if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch) {
                            wp.data.dispatch('wc/store/cart').invalidateResolution('getCartData');
                        }
                    } catch(e) {}
                    setTimeout(initQtyButtons, 50);
                }
            }).fail(function() {
                $controls.removeClass('loading');
            });
        }

        function initQtyButtons() {
            $('.woocommerce-mini-cart-item').each(function() {
                var $item = $(this);
                if ($item.find('.celzimo-qty-controls').length) return;

                var $removeBtn  = $item.find('.remove_from_cart_button');
                var cartKey     = $removeBtn.data('cart_item_key') || $removeBtn.attr('data-cart_item_key');
                var $qtySpan    = $item.find('.quantity');
                var qtyText     = $qtySpan.text().trim();
                var qtyMatch    = qtyText.match(/^(\d+)/);
                var currentQty  = qtyMatch ? parseInt(qtyMatch[1]) : 1;
                var $productLink = $item.find('a:not(.remove)').first();

                if (!cartKey) return;

                // --- LAYOUT RESTRUCTURE ---
                // Mover la imagen para separarla del texto del link
                var $img = $productLink.find('img');
                if ($img.length && !$item.hasClass('celzimo-restructured')) {
                    $item.addClass('celzimo-restructured');
                    
                    var linkHref = $productLink.attr('href');
                    var $imgContainer = $('<a href="' + linkHref + '" class="celzimo-mc-img"></a>');
                    $imgContainer.append($img);
                    
                    var $infoContainer = $('<div class="celzimo-mc-info"></div>');
                    $productLink.addClass('celzimo-mc-title');
                    
                    $infoContainer.append($productLink, $qtySpan);
                    $item.empty().append($removeBtn, $imgContainer, $infoContainer);
                }

                $qtySpan.addClass('celzimo-qty-hidden');

                var trashSVG = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                
                var $controls = $('<div class="celzimo-qty-controls"></div>');
                var $minus    = $('<button class="celzimo-qty-btn celzimo-qty-minus" type="button" aria-label="Disminuir cantidad">−</button>');
                var $num      = $('<span class="celzimo-qty-num">' + currentQty + '</span>');
                var $plus     = $('<button class="celzimo-qty-btn celzimo-qty-plus" type="button" aria-label="Aumentar cantidad">+</button>');
                var $trash    = $('<button class="celzimo-qty-trash" type="button" aria-label="Eliminar producto">' + trashSVG + '</button>');

                $controls.append($minus, $num, $plus, $trash);
                
                var $targetContainer = $item.find('.celzimo-mc-info');
                if ($targetContainer.length) {
                    $targetContainer.append($controls);
                } else {
                    $qtySpan.after($controls);
                }
                
                // Hide the original quantity span
                $qtySpan.addClass('celzimo-qty-hidden');

                $minus.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var q = parseInt($num.text(), 10) - 1;
                    if (q < 0) q = 0;
                    // No pintar qty optimista: el servidor (y stock) mandan.
                    updateCartQty(cartKey, q, $controls);
                });

                $plus.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var q = parseInt($num.text(), 10) + 1;
                    updateCartQty(cartKey, q, $controls);
                });

                $trash.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    updateCartQty(cartKey, 0, $controls);
                });
            });
        }

        function initSwatches() {
            $('.celzimo-mc-swatch').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = $(this);
                if ($btn.hasClass('selected') || $btn.closest('.celzimo-mc-swatches-container').hasClass('loading')) {
                    return;
                }

                var $container = $btn.closest('.celzimo-mc-swatches-container');
                var cartKey = $container.data('cart_item_key');
                var productId = $container.data('product_id');

                // Preview visual; si falla el servidor se revierte con fragments.
                $btn.siblings().removeClass('selected');
                $btn.addClass('selected');

                var attributes = {};
                var allSelected = true;
                $container.find('.celzimo-mc-attr-row').each(function() {
                    var $selectedSwatch = $(this).find('.celzimo-mc-swatch.selected');
                    if ($selectedSwatch.length) {
                        var attrKey = $selectedSwatch.attr('data-attribute') || $selectedSwatch.data('attribute');
                        var attrVal = $selectedSwatch.attr('data-value') || $selectedSwatch.data('value');
                        attributes[attrKey] = attrVal;
                    } else {
                        allSelected = false;
                    }
                });

                if (!allSelected) return;

                if (window.WCCartSync && typeof window.WCCartSync.updateVariation === 'function') {
                    window.WCCartSync.updateVariation(cartKey, productId, attributes, $container);
                    return;
                }

                $container.addClass('loading');
                $.post(AJAX_URL, {
                    action: 'celzimo_update_cart_variation',
                    cart_item_key: cartKey,
                    product_id: productId,
                    attributes: attributes,
                    nonce: NONCE
                }, function(response) {
                    $container.removeClass('loading');
                    if (response.success) {
                        if (response.data && response.data.fragments) {
                            $.each(response.data.fragments, function(selector, html) {
                                $(selector).replaceWith(html);
                            });
                        }
                        $(document.body).trigger('wc_fragment_refresh');
                        try {
                            if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch) {
                                wp.data.dispatch('wc/store/cart').invalidateResolutionForStore();
                            }
                        } catch(e) {}
                        if (document.body.classList.contains('woocommerce-cart')) {
                            window.location.reload();
                        }
                    } else {
                        var msg = response.data || 'Error al actualizar.';
                        if (msg === 'Sin stock') msg = 'La variación seleccionada se encuentra sin stock.';
                        alert(msg);
                        $(document.body).trigger('wc_fragment_refresh');
                    }
                }).fail(function() {
                    $container.removeClass('loading');
                    alert('Error de conexión');
                    $(document.body).trigger('wc_fragment_refresh');
                });
            });
        }

        // Re-inicializar después de que el mini-cart se refresque via AJAX
        $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
            initQtyButtons();
            initSwatches();
        });

        // Re-inicializar si se abre un widget dropdown
        $(document).on('click', '.cart-contents, .wc-block-mini-cart__button, [data-block-name="woocommerce/mini-cart"]', function() {
            setTimeout(function() {
                initQtyButtons();
                initSwatches();
            }, 300);
        });

        // Función SEGURA para ocultar el footer redundante sin congelar el navegador
        function hideRedundantFooterSafe() {
            // Eliminar clases nativas del block (solo dentro del mini-cart)
            $('.wc-block-mini-cart__footer, .wp-block-woocommerce-mini-cart-contents .wc-block-components-totals-wrapper').hide();
            
            // Buscar por texto y ocultar con precisión solo dentro de elementos del mini-cart
            $('.wp-block-woocommerce-mini-cart-contents, .widget_shopping_cart, .cart-drawer').find('a, button').filter(function() {
                var t = $(this).text().trim().toUpperCase();
                return t === 'PROCEDER AL PAGO' || t === 'PROCEED TO CHECKOUT';
            }).each(function() {
                $(this).hide();
                var $actions = $(this).closest('.wc-block-mini-cart__footer-actions');
                if ($actions.length) $actions.hide();
            });
            
            $('.wp-block-woocommerce-mini-cart-contents, .widget_shopping_cart, .cart-drawer').find('span, div, p').filter(function() {
                return $(this).text().trim() === 'Impuestos calculados en el checkout.';
            }).hide();
        }

        $(document).ready(function() {
            initQtyButtons();
            initSwatches();
            hideRedundantFooterSafe();
            // Sin setInterval: solo al refrescar fragments / abrir mini-cart.
            $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', hideRedundantFooterSafe);
        });

    })(jQuery);
    </script>
    <?php
}

add_action('wp_head', 'celzimo_minicart_swatch_css');
function celzimo_minicart_swatch_css() {
    ?>
    <style>
    /* Bypass Cache - Mini Cart Restructured Layout */
    li.woocommerce-mini-cart-item.celzimo-restructured {
        position: relative !important;
        display: flex !important;
        align-items: flex-start !important;
        padding: 15px 0 !important;
        border-bottom: 1px solid #f1f1f1 !important;
        overflow: visible !important;
        min-height: 120px !important;
    }
    .celzimo-mc-img {
        width: 60px !important;
        height: auto !important;
        margin-right: 15px !important;
        flex-shrink: 0 !important;
    }
    .celzimo-mc-info {
        flex: 1 !important;
        padding-right: 60px !important;
        overflow: visible !important;
    }
    .celzimo-mc-price-right {
        position: absolute !important;
        right: 14px !important;
        bottom: 20px !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        color: #111 !important;
        display: block !important;
        z-index: 10 !important;
    }
    .celzimo-mc-swatches-container {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .celzimo-mc-attr-row {
        margin-bottom: 5px;
        display: flex !important;
        align-items: center !important;
        gap: 8px;
        flex-wrap: wrap;
    }
    .celzimo-mc-attr-label {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 500;
    }
    .celzimo-mc-attr-options {
        display: flex !important;
        gap: 4px;
        flex-wrap: wrap;
    }
    button.celzimo-mc-swatch {
        background-color: #fff !important;
        background-size: cover !important;
        background-position: center !important;
        border: 1px solid #e5e7eb !important;
        padding: 4px 8px !important;
        font-size: 0.7rem !important;
        cursor: pointer !important;
        border-radius: 2px !important;
        color: #111 !important;
        transition: all 0.2s !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        display: inline-block !important;
        margin-right: 4px !important;
        min-width: 28px !important;
        text-align: center !important;
        line-height: 1 !important;
    }
    button.celzimo-mc-swatch:hover {
        border-color: #000 !important;
    }
    button.celzimo-mc-swatch.selected {
        border-color: #000 !important;
        background: #000 !important;
        color: #fff !important;
    }
    .celzimo-mc-swatches-container.loading {
        opacity: 0.5;
        pointer-events: none;
    }
    </style>
    <?php
}

/* ==========================================================================
   WOOCOMMERCE WRAPPER
   ========================================================================== */

if ( class_exists( 'WooCommerce' ) ) {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

    function celzimo_theme_wrapper_start() {
        echo '<div class="container main-content-wrapper" style="padding-top:40px;padding-bottom:80px;">';
    }
    function celzimo_theme_wrapper_end() {
        echo '</div>';
    }
    add_action( 'woocommerce_before_main_content', 'celzimo_theme_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content',  'celzimo_theme_wrapper_end',   10 );
}

/* ==========================================================================
   HELPER — CATEGORY LINK
   ========================================================================== */

function celzimo_get_category_link( $slug ) {
    if ( ! class_exists( 'WooCommerce' ) ) return home_url( '/tienda/' );
    $term = get_term_by( 'slug', $slug, 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) return get_term_link( $term );
    return home_url( '/categoria-producto/' . $slug . '/' );
}

/* ==========================================================================
   UNDER CONSTRUCTION — ACTIVO
   ========================================================================== */

function celzimo_under_construction() {
    // Evitar bloquear wp-admin, wp-login, cron, ajax y API REST
    if ( is_admin() || 
         in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ||
         ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
         ( defined( 'DOING_CRON' ) && DOING_CRON ) ||
         ( defined( 'REST_REQUEST' ) && REST_REQUEST )
    ) {
        return;
    }

    // Permitir a usuarios administradores ver el sitio de manera normal
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }

    // Indicar a los buscadores que el sitio está temporalmente en mantenimiento (SEO friendly)
    header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
    header( 'Status: 503 Service Temporarily Unavailable' );
    header( 'Retry-After: 86400' ); // 24 horas

    $image_url = get_template_directory_uri() . '/assets/coming-soon.jpg';
    $instagram_url = 'https://www.instagram.com/celzimo_veste/';
    $email = 'contacto@celzimoveste.cl';
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Celzimo Veste - Próximamente</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
        <style>
            :root {
                --color-bg-dark: #0d0c0b;
                --color-bg-light: #161413;
                --color-gold: #c5a880;
                --color-text-main: #f5f4f0;
                --color-text-muted: #a69f96;
                --font-primary: 'Inter', sans-serif;
                --font-secondary: 'Playfair Display', serif;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                background: radial-gradient(circle at center, var(--color-bg-light) 0%, var(--color-bg-dark) 100%);
                color: var(--color-text-main);
                font-family: var(--font-primary);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem 1rem;
                overflow-x: hidden;
            }

            .container {
                max-width: 600px;
                width: 100%;
                text-align: center;
                animation: fadeIn 1s ease-out;
            }

            .badge {
                display: inline-block;
                border: 1px solid var(--color-gold);
                color: var(--color-gold);
                padding: 0.35rem 1rem;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.2em;
                border-radius: 50px;
                margin-bottom: 2rem;
                font-weight: 500;
                background-color: rgba(197, 168, 128, 0.05);
            }

            .image-card {
                position: relative;
                width: 100%;
                max-width: 480px;
                margin: 0 auto 2.5rem;
                border-radius: 16px;
                padding: 12px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(10px);
                transition: transform 0.4s ease, box-shadow 0.4s ease;
            }

            .image-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 50px rgba(197, 168, 128, 0.15);
            }

            .image-card img {
                width: 100%;
                height: auto;
                border-radius: 10px;
                display: block;
                border: 1px solid rgba(197, 168, 128, 0.15);
            }

            .title {
                font-family: var(--font-secondary);
                font-size: 2.2rem;
                font-weight: 400;
                line-height: 1.3;
                margin-bottom: 1rem;
                letter-spacing: 0.02em;
            }

            .title em {
                font-family: var(--font-secondary);
                font-style: italic;
                color: var(--color-gold);
            }

            .description {
                font-size: 0.95rem;
                color: var(--color-text-muted);
                line-height: 1.6;
                max-width: 460px;
                margin: 0 auto 2.5rem;
                font-weight: 300;
            }

            .divider {
                width: 40px;
                height: 1px;
                background-color: var(--color-gold);
                margin: 0 auto 2rem;
            }

            .social-links {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                margin-bottom: 3rem;
            }

            .social-link {
                color: var(--color-text-main);
                text-decoration: none;
                font-size: 1.5rem;
                transition: color 0.3s ease, transform 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }

            .social-link:hover {
                color: var(--color-gold);
                transform: scale(1.1);
                background-color: rgba(197, 168, 128, 0.1);
                border-color: var(--color-gold);
            }

            .footer-text {
                font-size: 0.75rem;
                color: var(--color-text-muted);
                letter-spacing: 0.05em;
                opacity: 0.6;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 480px) {
                .title {
                    font-size: 1.8rem;
                }
                .description {
                    font-size: 0.9rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <span class="badge">Próximamente</span>
            
            <div class="image-card">
                <img src="<?php echo esc_url($image_url); ?>" alt="Celzimo Veste">
            </div>

            <h1 class="title">Diseñando la elegancia <em>a tu medida</em></h1>
            <p class="description">Estamos preparando una experiencia exclusiva de alta costura y sastrería de lujo. Muy pronto revelaremos nuestra nueva colección.</p>
            
            <div class="divider"></div>

            <div class="social-links">
                <a href="<?php echo esc_url($instagram_url); ?>" class="social-link" title="Síguenos en Instagram" target="_blank" rel="noopener">
                    <i class="ti ti-brand-instagram"></i>
                </a>
                <a href="mailto:<?php echo esc_attr($email); ?>" class="social-link" title="Escríbenos por correo">
                    <i class="ti ti-mail"></i>
                </a>
            </div>

            <p class="footer-text">&copy; <?php echo date('Y'); ?> CELZIMO VESTE. TODOS LOS DERECHOS RESERVADOS.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}
// add_action( 'template_redirect', 'celzimo_under_construction', 1 );

/* ==========================================================================
   TRADUCCIONES — "Proceder al pago" → "FINALIZAR COMPRA"
   ========================================================================== */

add_filter( 'gettext', 'celzimo_translate_checkout_btn', 20, 3 );
function celzimo_translate_checkout_btn( $translated, $text, $domain ) {
    $replacements = [
        'Proceed to checkout'          => 'FINALIZAR COMPRA',
        'Proceed to Checkout'          => 'FINALIZAR COMPRA',
        'Proceder al pago'             => 'FINALIZAR COMPRA',
        'Proceed to payment'           => 'FINALIZAR COMPRA',
        'Place order'                  => 'FINALIZAR COMPRA',
    ];
    return $replacements[ $text ] ?? $translated;
}

/* ==========================================================================
   MINI-CART LAYOUT FIX — Imagen al lado del nombre (no debajo)
   + Corrección botones de la página carrito
   ========================================================================== */

add_action( 'wp_head', 'celzimo_minicart_layout_fix', 100 );
function celzimo_minicart_layout_fix() {
    ?>
    <style id="celzimo-minicart-layout">

    /* ---- ITEM: Contenedor flex ---- */
    li.woocommerce-mini-cart-item.mini_cart_item,
    li.woocommerce-mini-cart-item {
        display: flex !important;
        align-items: flex-start !important;
        gap: 16px !important;
        padding: 16px 36px 16px 20px !important; /* right padding para el botón × */
        border-bottom: 1px solid #f0f0f0 !important;
        position: relative !important;
        list-style: none !important;
    }

    /* Botón eliminar × — posición absoluta arriba derecha */
    li.woocommerce-mini-cart-item .remove,
    li.woocommerce-mini-cart-item .remove_from_cart_button {
        position: absolute !important;
        top: 12px !important;
        right: 14px !important;
        font-size: 18px !important;
        color: #ccc !important;
        text-decoration: none !important;
        line-height: 1 !important;
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        font-weight: 400 !important;
    }
    li.woocommerce-mini-cart-item .remove:hover { color: #e53935 !important; }

    /* Imagen — separada gracias a JS */
    .celzimo-mc-img {
        display: block !important;
        width: 72px !important;
        height: 72px !important;
        flex-shrink: 0 !important;
    }
    .celzimo-mc-img img {
        width: 72px !important;
        height: 72px !important;
        object-fit: cover !important;
        border-radius: 8px !important;
        border: 1px solid #eee !important;
        display: block !important;
    }

    /* Contenedor de info (Título, Precio, Controles) */
    .celzimo-mc-info {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }

    /* Enlace con nombre */
    .celzimo-mc-title {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #1a1a2e !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        text-decoration: none !important;
        line-height: 1.35 !important;
        display: block !important;
        margin-bottom: 4px !important;
    }
    .celzimo-mc-title:hover { color: #c5a880 !important; }

    /* Cantidad original (si aplica) y precio */
    .woocommerce-mini-cart-item .quantity {
        font-size: 12px !important;
        color: #888 !important;
        display: block !important;
    }
    .celzimo-qty-hidden {
        display: none !important;
    }
    .woocommerce-mini-cart-item .quantity .woocommerce-Price-amount {
        font-weight: 700 !important;
        color: #1a1a2e !important;
        font-size: 13px !important;
    }

    /* ---- PÁGINA CARRITO — botón "PROCEDER AL PAGO" / "FINALIZAR COMPRA" ---- */
    .wc-proceed-to-checkout .checkout-button,
    .wc-proceed-to-checkout a.checkout-button,
    a.checkout-button.button.alt.wc-forward {
        display: block !important;
        width: 100% !important;
        background: #1a1a2e !important;
        color: #fff !important;
        text-align: center !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        padding: 16px 24px !important;
        border-radius: 6px !important;
        border: none !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: background 0.2s, transform 0.1s !important;
        box-sizing: border-box !important;
        margin-top: 10px !important;
    }
    .wc-proceed-to-checkout .checkout-button:hover,
    a.checkout-button.button.alt.wc-forward:hover {
        background: #2d2d50 !important;
        color: #fff !important;
        transform: translateY(-1px) !important;
        text-decoration: none !important;
    }

    /* =====================================================================
       ELIMINAR FOOTER DUPLICADO (Bloque vs Clásico)
       ===================================================================== */
    /* 1. Ocultar el footer nativo del bloque de WooCommerce (el bloque azul inferior) */
    .wc-block-mini-cart__footer,
    .wp-block-woocommerce-mini-cart-contents .wc-block-components-totals-wrapper {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* 2. Asegurar que nuestro footer clásico se comporte como el footer principal del drawer */
    .woocommerce-mini-cart__total {
        padding: 24px 20px 16px !important;
        margin: 0 !important;
        background: #fff !important;
        position: relative !important;
        z-index: 10 !important;
    }
    .woocommerce-mini-cart__buttons {
        padding: 0 20px 24px !important;
        background: #fff !important;
        position: relative !important;
        z-index: 10 !important;
    }

    </style>
    <?php
}
add_action( 'wp_ajax_celzimo_debug_variation', 'celzimo_debug_variation' );
add_action( 'wp_ajax_nopriv_celzimo_debug_variation', 'celzimo_debug_variation' );
function celzimo_debug_variation() {
    error_log('AJAX Attributes: ' . print_r(['attributes'], true));
    wp_send_json_success();
}
add_action( 'wp_ajax_celzimo_debug_variation2', 'celzimo_debug_variation2' );
add_action( 'wp_ajax_nopriv_celzimo_debug_variation2', 'celzimo_debug_variation2' );
function celzimo_debug_variation2() {
    error_log('DEBUG POST: ' . print_r($_POST, true));
    wp_send_json_success();
}

/* ==========================================================================
   DOCUMENT TYPE SELECTION (BOLETA / FACTURA)
   ========================================================================== */

add_filter( 'woocommerce_checkout_fields', 'celzimo_add_billing_document_fields', 9999 );
function celzimo_add_billing_document_fields( $fields ) {
    // Checkbox ¿Desea comprar con factura?
    $fields['billing']['billing_solicita_factura'] = array(
        'type'        => 'checkbox',
        'label'       => '¿Desea comprar con factura?',
        'required'    => false,
        'class'       => array( 'form-row-wide', 'celzimo-solicita-factura-container', 'fc-skip-hide-optional-field' ),
        'priority'    => 2,
    );

    // Título de Facturación
    $fields['billing']['billing_factura_heading'] = array(
        'type'        => 'heading',
        'label'       => 'Datos de Facturación',
        'required'    => false,
        'class'       => array( 'celzimo-factura-field', 'celzimo-factura-header', 'form-row-wide', 'fc-skip-hide-optional-field' ),
        'priority'    => 22,
    );

    // RUT de la Empresa
    $fields['billing']['billing_rut'] = array(
        'type'        => 'text',
        'label'       => 'RUT de la Empresa',
        'placeholder' => '12.345.678-9',
        'required'    => false,
        'class'       => array( 'form-row-first', 'celzimo-factura-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 24,
    );

    // Razón Social
    $fields['billing']['billing_razon_social'] = array(
        'type'        => 'text',
        'label'       => 'Razón Social',
        'placeholder' => 'Celzimo Chile SpA',
        'required'    => false,
        'class'       => array( 'form-row-last', 'celzimo-factura-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 26,
    );

    // Giro Comercial
    $fields['billing']['billing_giro'] = array(
        'type'        => 'text',
        'label'       => 'Giro Comercial',
        'placeholder' => 'Venta de prendas de vestir',
        'required'    => false,
        'class'       => array( 'form-row-wide', 'celzimo-factura-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 28,
    );

    // ¿La dirección de envío es distinta? (Checkbox)
    $fields['billing']['billing_factura_diff_address'] = array(
        'type'        => 'checkbox',
        'label'       => '¿La dirección de envío es distinta?',
        'required'    => false,
        'class'       => array( 'form-row-wide', 'celzimo-factura-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 29,
        'default'     => 0, // Unchecked by default
    );

    // --- Campos propios de despacho (independientes de Fluid Checkout) ---
    $fields['billing']['celzimo_ship_address'] = array(
        'type'        => 'text',
        'label'       => 'Dirección de Envío',
        'placeholder' => 'Calle y número',
        'required'    => false,
        'class'       => array( 'form-row-wide', 'celzimo-factura-field', 'celzimo-ship-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 30,
    );

    $fields['billing']['celzimo_ship_region'] = array(
        'type'        => 'state',
        'label'       => 'Región de Envío',
        'required'    => false,
        'class'       => array( 'form-row-first', 'celzimo-factura-field', 'celzimo-ship-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 31,
        'country_field' => 'billing_country',
        'country'     => 'CL',
    );

    $fields['billing']['celzimo_ship_comuna'] = array(
        'type'        => 'text',
        'label'       => 'Comuna de Envío',
        'placeholder' => 'Ej: Providencia',
        'required'    => false,
        'class'       => array( 'form-row-last', 'celzimo-factura-field', 'celzimo-ship-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 32,
    );

    // Eliminar el campo Dirección 2 (Departamento/Habitación) por completo
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['shipping']['shipping_address_2'] );

    return $fields;
}

// 2. Render Custom Heading Field
add_filter( 'woocommerce_form_field_heading', 'celzimo_render_heading_field', 10, 4 );
function celzimo_render_heading_field( $field, $key, $args, $value ) {
    return '<h4 class="' . esc_attr( implode( ' ', $args['class'] ) ) . '" id="' . esc_attr( $key ) . '_field" style="margin-top:25px;margin-bottom:15px;grid-column:1/-1;">' . esc_html( $args['label'] ) . '</h4>';
}

// 3. Inject CSS Styles for Checkout Toggle Buttons & Progress Bar
add_action( 'wp_head', 'celzimo_checkout_document_styles', 101 );
function celzimo_checkout_document_styles() {
    ?>
    <style id="celzimo-checkout-document-css">
    .celzimo-solicita-factura-container {
        margin-top: 15px !important;
        margin-bottom: 15px !important;
        float: none !important;
        clear: both !important;
        width: 100% !important;
    }
    .celzimo-solicita-factura-container label.checkbox {
        display: flex !important;
        align-items: center !important;
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        padding: 14px 18px !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        color: #1a1a2e !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-sizing: border-box !important;
    }
    .celzimo-solicita-factura-container label.checkbox:hover {
        background: rgba(26, 26, 46, 0.03) !important;
        border-color: #1a1a2e !important;
    }
    .celzimo-solicita-factura-container label.checkbox input[type="checkbox"] {
        margin-right: 12px !important;
        width: 18px !important;
        height: 18px !important;
        cursor: pointer !important;
        accent-color: #1a1a2e !important;
    }
    #billing_factura_diff_address_field {
        margin-top: 10px !important;
        margin-bottom: 10px !important;
        float: none !important;
        clear: both !important;
        width: 100% !important;
    }
    #billing_factura_diff_address_field label.checkbox {
        display: flex !important;
        align-items: center !important;
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        padding: 12px 16px !important;
        font-family: 'Inter', sans-serif !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        color: #495057 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-sizing: border-box !important;
    }
    #billing_factura_diff_address_field label.checkbox:hover {
        background: rgba(26, 26, 46, 0.03) !important;
        border-color: #1a1a2e !important;
    }
    #billing_factura_diff_address_field label.checkbox input[type="checkbox"] {
        margin-right: 10px !important;
        width: 16px !important;
        height: 16px !important;
        cursor: pointer !important;
        accent-color: #1a1a2e !important;
    }
    /* Campos propios de envío */
    #celzimo_ship_address_field,
    #celzimo_ship_region_field,
    #celzimo_ship_comuna_field {
        display: none;
    }
    #celzimo_ship_address_field {
        clear: both !important;
        margin-top: 8px !important;
    }
    /* Factura Heading style */
    .celzimo-factura-header {
        font-family: 'Playfair Display', serif !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #1a1a2e !important;
        border-top: 1px solid #e9ecef !important;
        padding-top: 20px !important;
        margin-top: 25px !important;
        width: 100% !important;
        clear: both !important;
    }
    
    /* Barra de progreso de envío gratis */
    .cz-free-shipping-progress {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 16px 20px !important;
        margin-bottom: 25px !important;
        font-family: 'Inter', sans-serif !important;
        width: 100% !important;
        clear: both !important;
        box-sizing: border-box !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
    }
    .cz-progress-text {
        font-size: 14px !important;
        color: #1f2937 !important;
        margin-bottom: 10px !important;
        text-align: left !important;
        line-height: 1.5 !important;
    }
    .cz-progress-text strong {
        color: #10b981 !important; /* Verde esmeralda para el monto faltante */
        font-weight: 700 !important;
    }
    .cz-progress-text.cz-success {
        color: #047857 !important;
        font-weight: 700 !important;
    }
    .cz-progress-bar-container {
        background: #f3f4f6 !important;
        border-radius: 9999px !important;
        height: 8px !important;
        width: 100% !important;
        overflow: hidden !important;
        margin-bottom: 8px !important;
        position: relative !important;
    }
    .cz-progress-bar {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%) !important;
        height: 100% !important;
        border-radius: 9999px !important;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .cz-progress-limits {
        display: flex !important;
        justify-content: space-between !important;
        font-size: 11px !important;
        color: #6b7280 !important;
        font-weight: 500 !important;
    }
    .cz-limit-current {
        color: #111827 !important;
        font-weight: 600 !important;
    }
    
    /* Ajustes específicos para el mini-carrito (side cart) */
    .widget_shopping_cart_content .cz-free-shipping-progress {
        padding: 12px 16px !important;
        margin-bottom: 12px !important;
        border-radius: 0px !important;
        box-shadow: none !important;
        border-left: 0 !important;
        border-right: 0 !important;
        background: #f9fafb !important;
        border-top: 1px solid #e5e7eb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    </style>
    <?php
}

// 4. Inject jQuery Toggle Logic
add_action( 'wp_footer', 'celzimo_checkout_document_js', 101 );
function celzimo_checkout_document_js() {
    if ( ! is_checkout() ) return;
    ?>
    <script id="celzimo-checkout-document-js">
    (function($) {
        function relocatePersonalRutField() {
            var $rutField = $('#billing_rut_personal_field');
            var $phoneField = $('#billing_phone_field');
            if ($rutField.length && $phoneField.length) {
                if ($rutField.next()[0] !== $phoneField[0]) {
                    $rutField.insertBefore($phoneField);
                }
            }
        }

        function handleShippingToggle() {
            var solicitaFactura = jQuery('#billing_solicita_factura').is(':checked');
            var diffAddress = jQuery('#billing_factura_diff_address').is(':checked');
            
            if (solicitaFactura && diffAddress) {
                // Mostrar campos propios de despacho
                jQuery('#celzimo_ship_address_field, #celzimo_ship_region_field, #celzimo_ship_comuna_field').show();
                
                // Pre-rellenar si están vacíos con la dirección de facturación
                if (jQuery('#celzimo_ship_address').val() === '') {
                    jQuery('#celzimo_ship_address').val(jQuery('#billing_address_1').val());
                }
                if (jQuery('#celzimo_ship_region').val() === '' || jQuery('#celzimo_ship_region').val() === null) {
                    jQuery('#celzimo_ship_region').val(jQuery('#billing_state').val()).trigger('change');
                }
                setTimeout(function() {
                    if (jQuery('#celzimo_ship_comuna').val() === '' || jQuery('#celzimo_ship_comuna').val() === null) {
                        jQuery('#celzimo_ship_comuna').val(jQuery('#billing_city').val()).trigger('change');
                    }
                }, 150);
            } else {
                // Ocultar campos propios de despacho
                jQuery('#celzimo_ship_address_field, #celzimo_ship_region_field, #celzimo_ship_comuna_field').hide();
            }
        }

        function toggleFacturaFields() {
            relocatePersonalRutField();
            var solicitaFactura = $('#billing_solicita_factura').is(':checked');

            if (solicitaFactura) {
                $('.celzimo-factura-field').show();
                $('.celzimo-boleta-field').hide();
                
                // Hacer teléfono ancho completo en Factura
                $('#billing_phone_field').removeClass('form-row-last').addClass('form-row-wide');
                
                // Marcar factura como obligatorios
                $('#billing_rut_field, #billing_razon_social_field, #billing_giro_field')
                    .addClass('validate-required')
                    .find('.optional').remove();
                
                if ($('#billing_rut_field label abbr.required').length === 0) {
                    $('#billing_rut_field label').append(' <abbr class="required" title="obligatorio">*</abbr>');
                }
                if ($('#billing_razon_social_field label abbr.required').length === 0) {
                    $('#billing_razon_social_field label').append(' <abbr class="required" title="obligatorio">*</abbr>');
                }
                if ($('#billing_giro_field label abbr.required').length === 0) {
                    $('#billing_giro_field label').append(' <abbr class="required" title="obligatorio">*</abbr>');
                }

                // Desmarcar boleta como obligatorio
                $('#billing_rut_personal_field')
                    .removeClass('validate-required')
                    .find('abbr.required').remove();
                if ($('#billing_rut_personal_field label .optional').length === 0) {
                    $('#billing_rut_personal_field label').append(' <span class="optional">(opcional)</span>');
                }
            } else {
                $('.celzimo-factura-field').hide();
                $('.celzimo-boleta-field').show();
                
                // Hacer teléfono media columna en Boleta (para que quepa al lado de RUT del Cliente)
                $('#billing_phone_field').removeClass('form-row-wide').addClass('form-row-last');
                
                // Marcar boleta como obligatorio
                $('#billing_rut_personal_field')
                    .addClass('validate-required')
                    .find('.optional').remove();
                if ($('#billing_rut_personal_field label abbr.required').length === 0) {
                    $('#billing_rut_personal_field label').append(' <abbr class="required" title="obligatorio">*</abbr>');
                }

                // Desmarcar factura como obligatorios
                $('#billing_rut_field, #billing_razon_social_field, #billing_giro_field')
                    .removeClass('validate-required')
                    .find('abbr.required').remove();
                
                if ($('#billing_rut_field label .optional').length === 0) {
                    $('#billing_rut_field label').append(' <span class="optional">(opcional)</span>');
                }
                if ($('#billing_razon_social_field label .optional').length === 0) {
                    $('#billing_razon_social_field label').append(' <span class="optional">(opcional)</span>');
                }
                if ($('#billing_giro_field label .optional').length === 0) {
                    $('#billing_giro_field label').append(' <span class="optional">(opcional)</span>');
                }
            }
            
            // Sincronizar el despacho
            handleShippingToggle();
        }

        jQuery(document).on('change', 'input[name="billing_solicita_factura"]', function() {
            toggleFacturaFields();
        });

        jQuery(document).on('change', 'input[name="billing_factura_diff_address"]', function() {
            handleShippingToggle();
        });

        $(document).on('input change', '#billing_address_1, #billing_state, #billing_city', function() {
            var solicitaFactura = $('#billing_solicita_factura').is(':checked');
            var diffAddress = $('#billing_factura_diff_address').is(':checked');
            if (solicitaFactura && !diffAddress) {
                handleShippingToggle();
            }
        });

        // Toggle on page load and after checkout fragment refreshes
        jQuery(document).ready(function() {
            // Forzar checkboxes desmarcados al cargar la página
            jQuery('input[name="billing_solicita_factura"]').prop('checked', false);
            jQuery('input[name="billing_factura_diff_address"]').prop('checked', false);
            relocatePersonalRutField();
            toggleFacturaFields();
        });
        jQuery(document.body).on('updated_checkout init_checkout', function() {
            relocatePersonalRutField();
            toggleFacturaFields();
        });
    })(jQuery);
    </script>
    <?php
}

// 5. Checkout Validation (PHP validation of RUT, Razón Social and Giro)
add_action( 'woocommerce_after_checkout_validation', 'celzimo_validate_billing_document_fields', 10, 2 );
function celzimo_validate_billing_document_fields( $data, $errors ) {
    $solicita_factura = isset( $_POST['billing_solicita_factura'] ) && (int) $_POST['billing_solicita_factura'] === 1;

    if ( $solicita_factura ) {
        if ( empty( $_POST['billing_rut'] ) ) {
            $errors->add( 'billing_rut_required', 'El <strong>RUT de la Empresa</strong> es obligatorio si solicita Factura.' );
        } else {
            $rut = sanitize_text_field( $_POST['billing_rut'] );
            if ( ! celzimo_valida_rut( $rut ) ) {
                $errors->add( 'billing_rut_invalid', 'El <strong>RUT de la Empresa</strong> ingresado no es válido.' );
            }
        }
        if ( empty( $_POST['billing_razon_social'] ) ) {
            $errors->add( 'billing_razon_social_required', 'La <strong>Razón Social</strong> es obligatoria si solicita Factura.' );
        }
        if ( empty( $_POST['billing_giro'] ) ) {
            $errors->add( 'billing_giro_required', 'El <strong>Giro Comercial</strong> es obligatorio si solicita Factura.' );
        }
        // Si la dirección de envío es distinta, validar los campos propios de despacho
        $diff_address = isset( $_POST['billing_factura_diff_address'] ) && (int) $_POST['billing_factura_diff_address'] === 1;
        if ( $diff_address ) {
            if ( empty( $_POST['celzimo_ship_address'] ) ) {
                $errors->add( 'celzimo_ship_address_required', 'La <strong>Dirección de Envío</strong> es obligatoria cuando la dirección es distinta.' );
            }
            if ( empty( $_POST['celzimo_ship_region'] ) ) {
                $errors->add( 'celzimo_ship_region_required', 'La <strong>Región de Envío</strong> es obligatoria cuando la dirección es distinta.' );
            }
            if ( empty( $_POST['celzimo_ship_comuna'] ) ) {
                $errors->add( 'celzimo_ship_comuna_required', 'La <strong>Comuna de Envío</strong> es obligatoria cuando la dirección es distinta.' );
            }
        }
    } else {
        // Validación obligatoria de RUT del Cliente para Boleta
        if ( empty( $_POST['billing_rut_personal'] ) ) {
            $errors->add( 'billing_rut_personal_required', 'El <strong>RUT del Cliente</strong> es obligatorio.' );
        } else {
            $rut = sanitize_text_field( $_POST['billing_rut_personal'] );
            if ( ! celzimo_valida_rut( $rut ) ) {
                $errors->add( 'billing_rut_personal_invalid', 'El <strong>RUT del Cliente</strong> ingresado no es válido.' );
            }
        }
    }
}

// Simple Chilean RUT validation helper
function celzimo_valida_rut( $rut ) {
    $rut = preg_replace( '/[^k0-9]/i', '', $rut );
    if ( strlen( $rut ) < 2 ) return false;
    $dv  = substr( $rut, -1 );
    $numero = substr( $rut, 0, -1 );
    $i = 2;
    $suma = 0;
    foreach ( array_reverse( str_split( $numero ) ) as $v ) {
        if ( $i == 8 ) $i = 2;
        $suma += $v * $i;
        $i++;
    }
    $dvr = 11 - ( $suma % 11 );
    if ( $dvr == 11 ) $dvr = 0;
    if ( $dvr == 10 ) $dvr = 'K';
    if ( strtolower( $dvr ) == strtolower( $dv ) ) return true;
    return false;
}

// 6. Save Fields to Order Metadata
add_action( 'woocommerce_checkout_create_order', 'celzimo_save_billing_document_fields', 10, 2 );
function celzimo_save_billing_document_fields( $order, $data ) {
    $solicita_factura = isset( $_POST['billing_solicita_factura'] ) && (int) $_POST['billing_solicita_factura'] === 1;

    if ( $solicita_factura ) {
        $order->update_meta_data( '_billing_document_type', 'factura' );
        $order->update_meta_data( '_billing_solicita_factura', '1' );
        if ( isset( $_POST['billing_rut'] ) ) {
            $order->update_meta_data( '_billing_rut', sanitize_text_field( $_POST['billing_rut'] ) );
        }
        if ( isset( $_POST['billing_razon_social'] ) ) {
            $order->update_meta_data( '_billing_razon_social', sanitize_text_field( $_POST['billing_razon_social'] ) );
        }
        if ( isset( $_POST['billing_giro'] ) ) {
            $order->update_meta_data( '_billing_giro', sanitize_text_field( $_POST['billing_giro'] ) );
        }
        $diff_address = isset( $_POST['billing_factura_diff_address'] ) && (int) $_POST['billing_factura_diff_address'] === 1 ? '1' : '0';
        $order->update_meta_data( '_billing_factura_diff_address', $diff_address );

        // Si la dirección de envío es distinta, copiar los campos propios al despacho de WooCommerce
        if ( $diff_address === '1' ) {
            if ( ! empty( $_POST['celzimo_ship_address'] ) ) {
                $order->set_shipping_address_1( sanitize_text_field( $_POST['celzimo_ship_address'] ) );
                $order->update_meta_data( '_celzimo_ship_address', sanitize_text_field( $_POST['celzimo_ship_address'] ) );
            }
            if ( ! empty( $_POST['celzimo_ship_region'] ) ) {
                $order->set_shipping_state( sanitize_text_field( $_POST['celzimo_ship_region'] ) );
                $order->update_meta_data( '_celzimo_ship_region', sanitize_text_field( $_POST['celzimo_ship_region'] ) );
            }
            if ( ! empty( $_POST['celzimo_ship_comuna'] ) ) {
                $order->set_shipping_city( sanitize_text_field( $_POST['celzimo_ship_comuna'] ) );
                $order->update_meta_data( '_celzimo_ship_comuna', sanitize_text_field( $_POST['celzimo_ship_comuna'] ) );
            }
        } else {
            // Misma dirección: copiar billing al shipping
            $order->set_shipping_address_1( $order->get_billing_address_1() );
            $order->set_shipping_state( $order->get_billing_state() );
            $order->set_shipping_city( $order->get_billing_city() );
        }
    } else {
        $order->update_meta_data( '_billing_document_type', 'boleta' );
        $order->update_meta_data( '_billing_solicita_factura', '0' );
        if ( isset( $_POST['billing_rut_personal'] ) ) {
            $order->update_meta_data( '_billing_rut_personal', sanitize_text_field( $_POST['billing_rut_personal'] ) );
        }
    }
}

// 7. Display fields in WooCommerce Admin Order page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'celzimo_display_billing_document_fields_admin', 10, 1 );
function celzimo_display_billing_document_fields_admin( $order ) {
    $doc_type = $order->get_meta( '_billing_document_type' );
    if ( ! $doc_type ) $doc_type = 'boleta';

    echo '<div class="address" style="margin-top:20px; border-top:1px solid #eee; padding-top:10px; clear:both;">';
    echo '<h3>Documento de Venta</h3>';
    echo '<p><strong>Tipo:</strong> ' . ucfirst( $doc_type ) . '</p>';
    if ( $doc_type === 'factura' ) {
        echo '<p><strong>RUT:</strong> ' . esc_html( $order->get_meta( '_billing_rut' ) ) . '</p>';
        echo '<p><strong>Razón Social:</strong> ' . esc_html( $order->get_meta( '_billing_razon_social' ) ) . '</p>';
        echo '<p><strong>Giro Comercial:</strong> ' . esc_html( $order->get_meta( '_billing_giro' ) ) . '</p>';
        $diff_addr = $order->get_meta( '_billing_factura_diff_address' );
        echo '<p><strong>¿Dirección de envío es distinta?:</strong> ' . ($diff_addr === '1' ? 'Sí' : 'No') . '</p>';
    } else {
        echo '<p><strong>RUT del Cliente:</strong> ' . esc_html( $order->get_meta( '_billing_rut_personal' ) ) . '</p>';
    }
    echo '</div>';
}

// 8. Add custom fields to order email confirmation
add_action( 'woocommerce_email_after_order_table', 'celzimo_display_billing_document_fields_email', 10, 4 );
function celzimo_display_billing_document_fields_email( $order, $sent_to_admin, $plain_text, $email ) {
    $doc_type = $order->get_meta( '_billing_document_type' );
    if ( ! $doc_type ) return;

    if ( $plain_text ) {
        echo "\nDOCUMENTO DE VENTA\n";
        echo "Tipo: " . ucfirst( $doc_type ) . "\n";
        if ( $doc_type === 'factura' ) {
            echo "RUT: " . $order->get_meta( '_billing_rut' ) . "\n";
            echo "Razón Social: " . $order->get_meta( '_billing_razon_social' ) . "\n";
            echo "Giro: " . $order->get_meta( '_billing_giro' ) . "\n";
            $diff_addr = $order->get_meta( '_billing_factura_diff_address' );
            echo "¿Dirección de envío es distinta?: " . ($diff_addr === '1' ? 'Sí' : 'No') . "\n";
        } else {
            echo "RUT del Cliente: " . $order->get_meta( '_billing_rut_personal' ) . "\n";
        }
    } else {
        echo '<div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">';
        echo '<h3>Documento de Venta</h3>';
        echo '<p><strong>Tipo:</strong> ' . ucfirst( $doc_type ) . '<br>';
        if ( $doc_type === 'factura' ) {
            echo '<strong>RUT:</strong> ' . esc_html( $order->get_meta( '_billing_rut' ) ) . '<br>';
            echo '<strong>Razón Social:</strong> ' . esc_html( $order->get_meta( '_billing_razon_social' ) ) . '<br>';
            echo '<strong>Giro Comercial:</strong> ' . esc_html( $order->get_meta( '_billing_giro' ) ) . '<br>';
            $diff_addr = $order->get_meta( '_billing_factura_diff_address' );
            echo '<strong>¿Dirección de envío es distinta?:</strong> ' . ($diff_addr === '1' ? 'Sí' : 'No') . '</p>';
        } else {
            echo '<strong>RUT del Cliente:</strong> ' . esc_html( $order->get_meta( '_billing_rut_personal' ) ) . '</p>';
        }
        echo '</div>';
    }
}

/* ==========================================================================
   CHILEAN REGIONS AND COMMUNES HIERARCHICAL SELECTORS
   ========================================================================== */

// 1. Programmatically deactivate the Comunas de Chile plugin to prevent conflict
add_action( 'admin_init', 'celzimo_deactivate_comunas_chile_plugin' );
function celzimo_deactivate_comunas_chile_plugin() {
    if ( is_plugin_active( 'comunas-de-chile-para-woocommerce/woocommerce-comunas.php' ) ) {
        deactivate_plugins( 'comunas-de-chile-para-woocommerce/woocommerce-comunas.php' );
    }
}

// 2. Define the 16 regions of Chile as States in WooCommerce
add_filter( 'woocommerce_states', 'celzimo_chile_states', 9999 );
function celzimo_chile_states( $states ) {
    $states['CL'] = array(
        'AP' => 'Arica y Parinacota',
        'TA' => 'Tarapacá',
        'AN' => 'Antofagasta',
        'AT' => 'Atacama',
        'CO' => 'Coquimbo',
        'VS' => 'Valparaíso',
        'RM' => 'Región Metropolitana de Santiago',
        'LI' => 'Región del Libertador Gral. Bernardo O’Higgins',
        'ML' => 'Región del Maule',
        'NB' => 'Región de Ñuble',
        'BI' => 'Región del Biobío',
        'AR' => 'Región de la Araucanía',
        'LR' => 'Región de Los Ríos',
        'LL' => 'Región de Los Lagos',
        'AI' => 'Región Aisén del Gral. Carlos Ibáñez del Campo',
        'MA' => 'Región de Magallanes y de la Antártica Chilena',
    );
    return $states;
}

// Helper function to insert a key-value pair after a specific key in an associative array
function celzimo_array_insert_after( array $array, $key, $new_key, $new_value ) {
    if ( ! array_key_exists( $key, $array ) ) {
        $array[$new_key] = $new_value;
        return $array;
    }
    $new_array = array();
    foreach ( $array as $k => $value ) {
        $new_array[$k] = $value;
        if ( $k === $key ) {
            $new_array[$new_key] = $new_value;
        }
    }
    return $new_array;
}

// 3. Modify billing and shipping city fields to select dropdowns
add_filter( 'woocommerce_checkout_fields', 'celzimo_modify_checkout_city_to_select', 99999 );
function celzimo_modify_checkout_city_to_select( $fields ) {
    // Billing City -> Comuna dropdown
    $fields['billing']['billing_city'] = array(
        'label'        => 'Comuna',
        'required'     => true,
        'type'         => 'select',
        'class'        => array( 'form-row-wide', 'fc-skip-hide-optional-field' ), // No colapsar en Fluid Checkout
        'options'      => array( '' => 'Seleccione una comuna' ),
        'priority'     => 85,
        'placeholder'  => 'Seleccione una comuna',
    );

    // Shipping City -> Comuna dropdown
    $fields['shipping']['shipping_city'] = array(
        'label'        => 'Comuna',
        'required'     => true,
        'type'         => 'select',
        'class'        => array( 'form-row-wide', 'fc-skip-hide-optional-field' ), // No colapsar en Fluid Checkout
        'options'      => array( '' => 'Seleccione una comuna' ),
        'priority'     => 85,
        'placeholder'  => 'Seleccione una comuna',
    );

    // Región billing layout configuration
    if ( isset( $fields['billing']['billing_state'] ) ) {
        $fields['billing']['billing_state']['label'] = 'Región';
        $fields['billing']['billing_state']['priority'] = 80;
        $fields['billing']['billing_state']['required'] = true;
        // Evitar colapsado en Fluid Checkout
        if ( ! is_array( $fields['billing']['billing_state']['class'] ) ) {
            $fields['billing']['billing_state']['class'] = array( $fields['billing']['billing_state']['class'] );
        }
        $fields['billing']['billing_state']['class'][] = 'fc-skip-hide-optional-field';
    }

    // Región shipping layout configuration
    if ( isset( $fields['shipping']['shipping_state'] ) ) {
        $fields['shipping']['shipping_state']['label'] = 'Región';
        $fields['shipping']['shipping_state']['priority'] = 80;
        $fields['shipping']['shipping_state']['required'] = true;
        // Evitar colapsado en Fluid Checkout
        if ( ! is_array( $fields['shipping']['shipping_state']['class'] ) ) {
            $fields['shipping']['shipping_state']['class'] = array( $fields['shipping']['shipping_state']['class'] );
        }
        $fields['shipping']['shipping_state']['class'][] = 'fc-skip-hide-optional-field';
    }

    // Billing Phone -> Required and Fixed, positioned on the right (form-row-last)
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['required'] = true;
        $fields['billing']['billing_phone']['priority'] = 20;
        if ( ! is_array( $fields['billing']['billing_phone']['class'] ) ) {
            $fields['billing']['billing_phone']['class'] = array( $fields['billing']['billing_phone']['class'] );
        }
        $fields['billing']['billing_phone']['class'] = array_diff( $fields['billing']['billing_phone']['class'], array( 'form-row-wide' ) );
        $fields['billing']['billing_phone']['class'][] = 'form-row-last';
        $fields['billing']['billing_phone']['class'][] = 'fc-skip-hide-optional-field';
        $fields['billing']['billing_phone']['class'] = array_unique( $fields['billing']['billing_phone']['class'] );
    }

    // RUT del Cliente (para Boleta) - posicionado a la izquierda del teléfono (debajo de Nombre)
    $rut_personal_field = array(
        'type'        => 'text',
        'label'       => 'RUT del Cliente',
        'placeholder' => '12.345.678-9',
        'required'    => false, // Toggle requerido dinámicamente en JS y validación PHP
        'class'       => array( 'form-row-first', 'celzimo-boleta-field', 'fc-skip-hide-optional-field' ),
        'priority'    => 19,
    );
    // Insertamos ordenadamente justo después de billing_last_name (Apellidos) para que se renderice debajo de Nombre
    if ( isset( $fields['billing'] ) ) {
        $fields['billing'] = celzimo_array_insert_after( $fields['billing'], 'billing_last_name', 'billing_rut_personal', $rut_personal_field );
    }

    // Shipping Phone -> Required and Fixed
    if ( isset( $fields['shipping']['shipping_phone'] ) ) {
        $fields['shipping']['shipping_phone']['required'] = true;
        if ( ! is_array( $fields['shipping']['shipping_phone']['class'] ) ) {
            $fields['shipping']['shipping_phone']['class'] = array( $fields['shipping']['shipping_phone']['class'] );
        }
        $fields['shipping']['shipping_phone']['class'][] = 'fc-skip-hide-optional-field';
    }

    return $fields;
}

// 4. Inject JavaScript list and dynamic populate logic in checkout footer
add_action( 'wp_footer', 'celzimo_chile_regions_comunas_js', 102 );
function celzimo_chile_regions_comunas_js() {
    if ( ! is_checkout() ) return;
    ?>
    <script id="celzimo-chile-regions-comunas-js">
    (function($) {
        var comunasPorRegion = {
            'AP': ["Arica", "Camarones", "Putre", "General Lagos"],
            'TA': ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"],
            'AN': ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"],
            'AT': ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"],
            'CO': ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paiguano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"],
            'VS': ["Valparaíso", "Casablanca", "Concón", "Juan Fernández", "Puchuncaví", "Quintero", "Viña del Mar", "Isla de Pascua", "Los Andes", "Calle Larga", "Rinconada", "San Esteban", "La Ligua", "Cabildo", "Papudo", "Petorca", "Zapallar", "Quillota", "Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Algarrobo", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Catemu", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Quilpué", "Limache", "Olmué", "Villa Alemana"],
            'LI': ["Rancagua", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "Las Cabras", "Machalí", "Malloa", "Mostazal", "Olivar", "Peumo", "Pichidegua", "Quinta de Tilcoco", "Rengo", "Requínoa", "San Vicente", "Pichilemu", "La Estrella", "Litueche", "Marchihue", "Navidad", "Paredones", "San Fernando", "Chépica", "Chimbarongo", "Lolol", "Nancagua", "Palmilla", "Peralillo", "Placilla", "Pumanque", "Santa Cruz"],
            'ML': ["Talca", "Constitución", "Curepto", "Empedrado", "Maule", "Pelarco", "Pencahue", "Río Claro", "San Clemente", "San Rafael", "Cauquenes", "Chanco", "Pelluhue", "Curicó", "Hualañé", "Licantén", "Molina", "Rauco", "Romeral", "Sagrada Familia", "Teno", "Vichuquén", "Linares", "Colbún", "Longaví", "Parral", "Retiro", "San Javier", "Villa Alegre", "Yerbas Buenas"],
            'NB': ["Cobquecura", "Coelemu", "Ninhue", "Portezuelo", "Quirihue", "Ránquil", "Treguaco", "Bulnes", "Chillán Viejo", "Chillán", "El Carmen", "Pemuco", "Pinto", "Quillón", "San Ignacio", "Yungay", "Coihueco", "Ñiquén", "San Carlos", "San Fabián", "San Nicolás"],
            'BI': ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío"],
            'AR': ["Temuco", "Carahue", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Cholchol", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria"],
            'LR': ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"],
            'LL': ["Puerto Montt", "Calbuco", "Cochamó", "Fresia", "Frutillar", "Los Muermos", "Llanquihue", "Maullín", "Puerto Varas", "Castro", "Ancud", "Chonchi", "Curaco de Vélez", "Dalcahue", "Puqueldón", "Queilén", "Quellón", "Quemchi", "Quinchao", "Osorno", "Puerto Octay", "Purranque", "Puyehue", "Río Negro", "San Juan de la Costa", "San Pablo", "Chaitén", "Futaleufú", "Hualaihué", "Palena"],
            'AI': ["Coihaique", "Lago Verde", "Aisén", "Cisnes", "Guaitecas", "Cochrane", "O’Higgins", "Tortel", "Chile Chico", "Río Ibáñez"],
            'MA': ["Punta Arenas", "Laguna Blanca", "Río Verde", "San Gregorio", "Cabo de Hornos (Ex Navarino)", "Antártica", "Porvenir", "Primavera", "Timaukel", "Natales", "Torres del Paine"],
            'RM': ["Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "Santiago", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "Colina", "Lampa", "Tiltil", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor"]
        };

        function updateComunasDropdown(type) {
            var $countrySelect = $('#' + type + '_country');
            var $stateSelect = $('#' + type + '_state');
            var $citySelect = $('#' + type + '_city');
            
            if (!$stateSelect.length || !$citySelect.length) return;

            var country = $countrySelect.val();
            if (country !== 'CL') {
                $citySelect.prop('disabled', false);
                return;
            }

            var selectedRegion = $stateSelect.val();
            var currentComuna = $citySelect.data('pending-value') || $citySelect.val();

            // Convertir a select si es input de texto
            if (!$citySelect.is('select')) {
                var nameAttr = $citySelect.attr('name');
                var idAttr = $citySelect.attr('id');
                var classAttr = $citySelect.attr('class');
                var placeholderAttr = $citySelect.attr('placeholder') || 'Seleccione una comuna';
                
                var $newSelect = $('<select></select>')
                    .attr('name', nameAttr)
                    .attr('id', idAttr)
                    .attr('class', classAttr)
                    .attr('data-placeholder', placeholderAttr);

                $citySelect.replaceWith($newSelect);
                $citySelect = $('#' + type + '_city');
            }

            // Limpiar y repoblar comunas
            $citySelect.empty();
            $citySelect.append('<option value="">Selecciona tu comuna</option>');

            if (selectedRegion && comunasPorRegion[selectedRegion]) {
                var comunas = comunasPorRegion[selectedRegion];
                $.each(comunas, function(index, value){
                    var selected = (value === currentComuna) ? ' selected="selected"' : '';
                    $citySelect.append('<option value="' + value + '"' + selected + '>' + value + '</option>');
                });
                $citySelect.prop('disabled', false);
            } else {
                $citySelect.prop('disabled', true);
            }

            $citySelect.trigger('change.select2');
            $citySelect.trigger('change');
        }

        // Eventos
        $(document).on('change', '#billing_state', function(){
            updateComunasDropdown('billing');
        });

        $(document).on('change', '#shipping_state', function(){
            updateComunasDropdown('shipping');
        });

        // Escuchar refrescos AJAX de WooCommerce
        $(document).on('updated_checkout country_to_state_changing', function(){
            var bVal = $('#billing_city').val();
            if (bVal) $('#billing_city').data('pending-value', bVal);
            
            var sVal = $('#shipping_city').val();
            if (sVal) $('#shipping_city').data('pending-value', sVal);

            updateComunasDropdown('billing');
            updateComunasDropdown('shipping');
        });

        // Carga inicial
        $(document).ready(function(){
            setTimeout(function(){
                updateComunasDropdown('billing');
                updateComunasDropdown('shipping');
            }, 600);
        });

    })(jQuery);
    </script>
    <?php
}

/* ==========================================================================
   TRANSBANK WEBPAY PLUS — Fluid Checkout Fix (DEFINITIVO)
   =========================================================================
   Diagnóstico senior (2026-07-13):
   - El handle exacto del script de bloques de Transbank es 'wc_transbank_webpay_payment'
     (ver WCGatewayTransbankBlocks::get_payment_method_script_handles())
   - Este script requiere React + wc-blocks-registry e intenta registrarse
     con wcBlocksRegistry.registerPaymentMethod() en contexto de checkout clásico.
   - El checkout AJAX (update_order_review) funciona correctamente para navegadores
     reales (HTTP 200 con headers correctos). No es problema de ModSecurity.
   - El spinner queda colgado por error JS silencioso al inicializar el bloque.
   Solución:
   1. Desencolar 'wc_transbank_webpay_payment' (handle EXACTO) antes de renderizar.
   2. Safety-net JS con timeout para liberar el spinner si queda colgado.
   3. Interceptor AJAX que garantiza el redirect a WebPay tras el submit.
   ========================================================================== */

// Fix 1: Desencolar el script de bloques de Transbank (handle EXACTO verificado)
add_action( 'wp_print_scripts', 'celzimo_dequeue_transbank_blocks_scripts', 999 );
function celzimo_dequeue_transbank_blocks_scripts() {
    if ( ! is_checkout() ) return;

    // Handles EXACTOS obtenidos de WCGatewayTransbankBlocks::get_payment_method_script_handles()
    // Formato: 'wc_transbank_' . $productName . '_payment'
    $handles = [
        'wc_transbank_webpay_payment',    // WebPay Plus
        'wc_transbank_oneclick_payment',  // OneClick (deshabilitado pero por seguridad)
    ];
    foreach ( $handles as $h ) {
        wp_dequeue_script( $h );
        wp_deregister_script( $h );
    }
}

// Fix 2: Prevenir el registro de Transbank como bloque de pago en el checkout clásico.
// Esto se hace deshabilitando el hook de registro ANTES de que WooCommerce Blocks corra.
add_action( 'woocommerce_blocks_loaded', 'celzimo_prevent_transbank_blocks_registration', 1 );
function celzimo_prevent_transbank_blocks_registration() {
    // Eliminar el hook de registro de métodos de pago para Transbank en checkout clásico.
    // Como los hooks son anónimos, usamos una alternativa: sobrescribir la variable global
    // de contexto y controlar qué scripts se sirven al frontend.
    // (El dequeue del Fix 1 es la solución real; esto es defensa en profundidad.)
    if ( is_admin() ) return;
    // Agregar filtro para que el registro de bloques no tenga efecto en frontend
    add_filter( 'woocommerce_blocks_is_checkout', '__return_false', 999 );
}

// Fix 4: Solucionar el bloqueo de checkout (Hacer comuna no requerida para evitar bloqueos)
add_filter('woocommerce_billing_fields', 'celzimo_fix_comuna_requirement', 9999);
add_filter('woocommerce_shipping_fields', 'celzimo_fix_comuna_requirement', 9999);
function celzimo_fix_comuna_requirement($fields) {
    if (isset($fields['billing_city'])) {
        $fields['billing_city']['required'] = false;
    }
    if (isset($fields['shipping_city'])) {
        $fields['shipping_city']['required'] = false;
    }
    return $fields;
}

add_filter('woocommerce_default_address_fields', 'celzimo_fix_default_comuna_req', 9999);
function celzimo_fix_default_comuna_req($fields) {
    if (isset($fields['city'])) {
        $fields['city']['required'] = false;
    }
    return $fields;
}


// Fix 5: Sincronizador de Comuna a billing_city/shipping_city (JS Bypass Seguro)
add_action( 'wp_footer', 'celzimo_checkout_comuna_synchronizer', 200 );
function celzimo_checkout_comuna_synchronizer() {
    if ( ! is_checkout() ) return;
    ?>
    <script id="celzimo-comuna-sync">
    (function($) {
        'use strict';
        if (typeof $ === 'undefined') return;

        function syncComuna() {
            var $billingDropdown = $('#billing_city_field select, select[name="billing_city"], #billing_address_3_field select');
            var val = $billingDropdown.val();
            
            if (val) {
                var $billingInput = $('input[name="billing_city"], select[name="billing_city"]');
                if ($billingInput.val() !== val || $billingInput.prop('disabled')) {
                    $billingInput.prop('disabled', false).val(val);
                }
            }

            var $shippingDropdown = $('#shipping_city_field select, select[name="shipping_city"], #shipping_address_3_field select');
            var sVal = $shippingDropdown.val() || val;
            if (sVal) {
                var $shippingInput = $('input[name="shipping_city"], select[name="shipping_city"]');
                if ($shippingInput.val() !== sVal || $shippingInput.prop('disabled')) {
                    $shippingInput.prop('disabled', false).val(sVal);
                }
            }
        }

        // Ejecutar ÚNICAMENTE al intentar enviar el formulario final
        $(document).on('checkout_place_order', function() {
            syncComuna();
        });

        // Sincronizar silenciosamente al cambiar el dropdown sin disparar eventos recursivos
        $(document).on('change', '#billing_city_field select, #billing_address_3_field select', function() {
            syncComuna();
        });

    })(jQuery);
    </script>
    <?php
}

// Fix 4: Interceptor AJAX — garantizar redirect de WebPay tras submit del checkout
// Fluid Checkout envía el form via AJAX (?wc-ajax=checkout). Cuando Transbank
// devuelve {'result':'success','redirect':url}, el JS debe seguir el redirect.
// Si Fluid Checkout no lo hace automáticamente, este interceptor lo fuerza.
add_action( 'wp_footer', 'celzimo_transbank_redirect_interceptor', 201 );
function celzimo_transbank_redirect_interceptor() {
    if ( ! is_checkout() ) return;
    ?>
    <script id="celzimo-tbk-redirect-fix">
    (function($) {
        'use strict';
        if (typeof $ === 'undefined') return;

        var TBK_ID = 'transbank_webpay_plus_rest';

        // Interceptar la respuesta AJAX del checkout
        $(document).ajaxSuccess(function(event, xhr, settings) {
            // Solo para el endpoint de checkout
            if (!settings.url || settings.url.indexOf('wc-ajax=checkout') === -1) return;

            try {
                var resp = typeof xhr.responseJSON !== 'undefined'
                    ? xhr.responseJSON
                    : JSON.parse(xhr.responseText);

                // Si el resultado es exitoso y hay redirect → seguirlo
                if (resp && resp.result === 'success' && resp.redirect) {
                    // Pequeño delay para que Fluid Checkout limpie su estado
                    setTimeout(function() {
                        window.location.href = resp.redirect;
                    }, 200);
                }
            } catch(e) {}
        });

        // También escuchar el evento de WooCommerce para el redirect
        $(document.body).on('checkout_place_order_success', function(e, result) {
            if (result && result.result === 'success' && result.redirect) {
                window.location.href = result.redirect;
            }
        });

     })(jQuery);
     </script>
     <?php
 }


/* ==========================================================================
   SISTEMA SEGURO DE REGISTRO DE USUARIOS Y GESTIÓN DE DATOS (CZ INTEGRACIÓN)
   ========================================================================== */

// 1. Mostrar campos personalizados en el formulario de registro de WooCommerce
add_action( 'woocommerce_register_form', 'cz_add_registration_fields' );
function cz_add_registration_fields() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_billing_tax_id"><?php _e( 'RUT / Identificación Fiscal (Opcional)', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_tax_id" id="reg_billing_tax_id" value="<?php if ( ! empty( $_POST['billing_tax_id'] ) ) echo esc_attr( $_POST['billing_tax_id'] ); ?>" />
    </p>
    <p class="form-row form-row-wide cz-marketing-consent-wrapper">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="marketing_consent" id="reg_marketing_consent" value="1" <?php checked( isset($_POST['marketing_consent']), true ); ?> />
            <span><?php _e( 'Acepto recibir ofertas y campañas personalizadas.', 'woocommerce' ); ?></span>
        </label>
    </p>
    <?php
}

// 2. Guardar los campos del registro y encriptar PII sensible
add_action( 'woocommerce_created_customer', 'cz_save_registration_fields' );
function cz_save_registration_fields( $customer_id ) {
    if ( isset( $_POST['billing_tax_id'] ) && ! empty( $_POST['billing_tax_id'] ) ) {
        $encrypted_tax_id = cz_encrypt_pii_data( sanitize_text_field( $_POST['billing_tax_id'] ) );
        update_user_meta( $customer_id, 'billing_tax_id', $encrypted_tax_id );
    }
    $consent = isset( $_POST['marketing_consent'] ) ? 'yes' : 'no';
    update_user_meta( $customer_id, 'marketing_consent', $consent );
}

// 3. Métodos Criptográficos para PII usando AES-256-GCM y AUTH_KEY
function cz_encrypt_pii_data( $data ) {
    if ( empty( $data ) ) return '';
    $key = defined('AUTH_KEY') ? AUTH_KEY : wp_salt('auth');
    $cipher = "aes-256-gcm";
    $ivlen = openssl_cipher_iv_length( $cipher );
    $iv = openssl_random_pseudo_bytes( $ivlen );
    $ciphertext = openssl_encrypt( $data, $cipher, $key, 0, $iv, $tag );
    return base64_encode( $iv . $tag . $ciphertext );
}

function cz_decrypt_pii_data( $encrypted_data ) {
    if ( empty( $encrypted_data ) ) return '';
    $key = defined('AUTH_KEY') ? AUTH_KEY : wp_salt('auth');
    $cipher = "aes-256-gcm";
    $c = base64_decode( $encrypted_data );
    $ivlen = openssl_cipher_iv_length( $cipher );
    $iv = substr( $c, 0, $ivlen );
    $tag = substr( $c, $ivlen, 16 );
    $ciphertext = substr( $c, $ivlen + 16 );
    return openssl_decrypt( $ciphertext, $cipher, $key, 0, $iv, $tag );
}

// 4. Copiar metadatos del usuario al pedido durante la creación (Consistencia histórica y seguridad)
add_action( 'woocommerce_checkout_create_order', 'cz_copy_user_meta_to_order', 10, 2 );
function cz_copy_user_meta_to_order( $order, $data ) {
    $user_id = $order->get_customer_id();
    if ( $user_id ) {
        $tax_id = get_user_meta( $user_id, 'billing_tax_id', true );
        if ( $tax_id ) {
            $order->update_meta_data( '_billing_tax_id', sanitize_text_field( $tax_id ) );
        }
    }
}

// 5. Configurar endpoints seguros en "Mi Cuenta" para visualización de Facturas y Autogestión
add_action( 'init', 'cz_register_my_account_endpoints' );
function cz_register_my_account_endpoints() {
    add_rewrite_endpoint( 'historial-facturas', EP_PAGES );
}

add_filter( 'woocommerce_account_menu_items', 'cz_my_account_menu_items' );
function cz_my_account_menu_items( $items ) {
    $items['historial-facturas'] = __( 'Mis Facturas Digitales', 'woocommerce' );
    return $items;
}

add_action( 'woocommerce_account_historial-facturas_endpoint', 'cz_render_invoice_history_content' );
function cz_render_invoice_history_content() {
    $current_user_id = get_current_user_id();
    
    // Obtener los pedidos del usuario
    $customer_orders = wc_get_orders( array(
        'customer' => $current_user_id,
        'limit'    => -1,
    ) );

    if ( $customer_orders ) {
        echo '<h3>Historial de Facturación y Documentos</h3>';
        echo '<table class="woocommerce-orders-table shop_table shop_table_responsive my_account_orders">';
        echo '<thead><tr><th>Pedido</th><th>Fecha</th><th>Total</th><th>Acción</th></tr></thead>';
        echo '<tbody>';
        foreach ( $customer_orders as $order ) {
            $pdf_url = wp_nonce_url( admin_url('admin-ajax.php?action=cz_download_invoice&order_id=' . $order->get_id()), 'cz_download_invoice_' . $order->get_id() );
            echo '<tr>';
            echo '<td>#' . $order->get_order_number() . '</td>';
            echo '<td>' . wc_format_datetime( $order->get_date_created() ) . '</td>';
            echo '<td>' . $order->get_formatted_order_total() . '</td>';
            echo '<td><a href="' . esc_url($pdf_url) . '" class="button view">Descargar PDF</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="woocommerce-message woocommerce-message--info">Aún no tienes facturas disponibles.</div>';
    }
}

// 6. Barra de Progreso de Envío Gratis para el Checkout (En el lateral derecho) y Side Cart
function celzimo_render_free_shipping_progress_bar_html() {
    if ( ! WC()->cart ) {
        return;
    }

    $threshold = 150000; // $150.000 CLP de acuerdo a políticas de la tienda
    
    // Buscar si hay configurado un monto dinámico para el envío gratis activo
    $shipping_zones = WC_Shipping_Zones::get_zones();
    foreach ($shipping_zones as $zone) {
        foreach ($zone['shipping_methods'] as $method) {
            if ($method->id === 'free_shipping' && $method->enabled === 'yes') {
                $min = $method->get_option('min_amount');
                if (is_numeric($min) && $min > 0) {
                    $threshold = floatval($min);
                    break 2;
                }
            }
        }
    }

    $cart_subtotal = WC()->cart->get_subtotal();
    $remaining = $threshold - $cart_subtotal;
    $percentage = min( 100, max( 0, ( $cart_subtotal / $threshold ) * 100 ) );

    $formatted_threshold = wc_price( $threshold );
    $formatted_remaining = wc_price( $remaining );
    $formatted_subtotal = wc_price( $cart_subtotal );
    ?>
    <div class="cz-free-shipping-progress">
        <?php if ( $remaining > 0 ) : ?>
            <div class="cz-progress-text">
                ¡Estás a solo <strong><?php echo $formatted_remaining; ?></strong> de obtener <strong>envío gratis</strong>!
            </div>
        <?php else : ?>
            <div class="cz-progress-text cz-success">
                🎉 ¡Felicidades! Has calificado para <strong>Envío Gratis</strong>.
            </div>
        <?php endif; ?>
        
        <div class="cz-progress-bar-container">
            <div class="cz-progress-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
        </div>
        
        <div class="cz-progress-limits">
            <span class="cz-limit-start"><?php echo wc_price( 0 ); ?></span>
            <span class="cz-limit-current">Llevas: <?php echo $formatted_subtotal; ?></span>
            <span class="cz-limit-end">Meta: <?php echo $formatted_threshold; ?></span>
        </div>
    </div>
    <?php
}

// Inyectar en el lateral derecho del checkout, justo arriba del resumen del pedido
add_action( 'woocommerce_checkout_before_order_review', 'celzimo_print_free_shipping_progress_bar', 5 );
// Inyectar al principio del mini-carrito (side cart)
add_action( 'woocommerce_before_mini_cart', 'celzimo_print_free_shipping_progress_bar', 5 );

function celzimo_print_free_shipping_progress_bar() {
    echo '<div id="celzimo-free-shipping-progress-wrapper">';
    celzimo_render_free_shipping_progress_bar_html();
    echo '</div>';
}

// Actualizar la barra de progreso dinámicamente vía AJAX al modificar el carrito en checkout
add_filter( 'woocommerce_update_order_review_fragments', 'celzimo_checkout_add_progress_bar_fragment' );
function celzimo_checkout_add_progress_bar_fragment( $fragments ) {
    ob_start();
    celzimo_render_free_shipping_progress_bar_html();
    $fragments['#celzimo-free-shipping-progress-wrapper'] = ob_get_clean();
    return $fragments;
}


