<?php
/**
 * The template for displaying all pages
 *
 * @package Celzimo_Veste
 */

get_header();

// Check if this is a WooCommerce page (Cart, Checkout, Account)
$is_woocommerce_page = false;
if ( class_exists( 'WooCommerce' ) ) {
    if ( is_cart() || is_checkout() || is_account_page() ) {
        $is_woocommerce_page = true;
    }
}

if ( $is_woocommerce_page ) {
    ?>
    <main id="primary" class="site-main container" style="padding-top: 60px; padding-bottom: 80px; margin: 0 auto; line-height: 1.6;">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>
    <?php
} else {
    ?>
    <main class="policy-section">
        <div class="container">
            <article class="policy-card" style="margin-top: 40px; margin-bottom: 40px;">
                <h1 class="policy-title"><?php the_title(); ?></h1>
                <div class="policy-meta" style="text-align: center; color: var(--color-text-light); font-size: 0.9rem; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px;">
                    Última actualización: 28 de junio de 2026
                </div>
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <div class="policy-content">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </article>
        </div>
    </main>
    <?php
}

get_footer();
