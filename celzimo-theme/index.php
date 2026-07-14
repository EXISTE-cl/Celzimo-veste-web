<?php
/**
 * The main template file
 *
 * @package Celzimo_Veste
 */

// Si es la página de inicio (portada), cargar front-page.php
if ( is_front_page() ) {
    get_template_part( 'front-page' );
    return;
}

get_header();
?>

<main id="primary" class="site-main container" style="padding-top: 60px; padding-bottom: 80px; max-width: 800px; margin: 0 auto; line-height: 1.6;">

    <?php
    if ( have_posts() ) :

        if ( is_home() && ! is_front_page() ) :
            ?>
            <header style="margin-bottom: 40px;">
                <h1 class="page-title" style="font-family: var(--font-secondary); font-size: 2.2rem; text-align: center;"><?php single_post_title(); ?></h1>
            </header>
            <?php
        endif;

        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 40px;">
                <?php if ( ! is_singular() ) : ?>
                    <header class="entry-header">
                        <h2 class="entry-title" style="font-family: var(--font-secondary);">
                            <a href="<?php the_permalink(); ?>" style="color: var(--text-dark); text-decoration: none;"><?php the_title(); ?></a>
                        </h2>
                    </header>
                <?php endif; ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        endwhile;

        the_posts_navigation();

    else :
        ?>
        <section class="no-results not-found" style="text-align: center; padding: 40px 0;">
            <h1 style="font-family: var(--font-secondary);"><?php esc_html_e( 'Nada Encontrado', 'celzimo-theme' ); ?></h1>
            <p><?php esc_html_e( 'Parece que no pudimos encontrar lo que estás buscando.', 'celzimo-theme' ); ?></p>
        </section>
        <?php
    endif;
    ?>

</main>

<?php get_footer();
