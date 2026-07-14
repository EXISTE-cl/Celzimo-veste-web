<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Celzimo_Veste
 */
?>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <div class="footer-col">
                <div class="brand-logo footer-logo">
                    <div class="logo-cv">
                        <span class="c">C</span><span class="v">V</span>
                    </div>
                    <div class="logo-text">
                        <h2>CELZIMO</h2>
                        <span>VESTE</span>
                    </div>
                </div>
                <p>Redefiniendo el lujo moderno para el individuo exigente.</p>
            </div>
            
            <div class="footer-col">
                <h4><?php esc_html_e( 'Servicio al Cliente', 'celzimo-theme' ); ?></h4>
                <ul>
                    <li><a href="mailto:contacto@celzimoveste.cl"><?php esc_html_e( 'Contacto', 'celzimo-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/politica-envios/' ) ); ?>"><?php esc_html_e( 'Política de Envíos', 'celzimo-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/devoluciones-y-garantias/' ) ); ?>"><?php esc_html_e( 'Devoluciones y Garantías', 'celzimo-theme' ); ?></a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4><?php esc_html_e( 'Legal', 'celzimo-theme' ); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/terminos-y-condiciones/' ) ); ?>"><?php esc_html_e( 'Términos y Condiciones', 'celzimo-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/politica-privacidad/' ) ); ?>"><?php esc_html_e( 'Política de Privacidad', 'celzimo-theme' ); ?></a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4><?php esc_html_e( 'Síguenos', 'celzimo-theme' ); ?></h4>
                <div class="social-links">
                    <a href="https://www.instagram.com/celzimo_veste/" target="_blank" rel="noopener"><i class="ti ti-brand-instagram"></i></a>
                    <a href="#" target="_blank" rel="noopener"><i class="ti ti-brand-facebook"></i></a>
                    <a href="#" target="_blank" rel="noopener"><i class="ti ti-brand-twitter"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date( 'Y' ); ?> CELZIMO VESTE. <?php esc_html_e( 'Todos los derechos reservados.', 'celzimo-theme' ); ?></p>
        </div>
    </footer>

    <!-- Login Modal (Solo activo si WooCommerce no está manejando las sesiones activamente por PHP) -->
    <?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
    <div class="login-overlay" id="login-overlay"></div>
    <div class="login-modal" id="login-modal">
        <div class="login-header">
            <h3><?php esc_html_e( 'Iniciar Sesión', 'celzimo-theme' ); ?></h3>
            <button class="close-btn" id="close-login"><i class="ti ti-x"></i></button>
        </div>
        <div class="login-body">
            <form id="login-form">
                <div class="form-group">
                    <label for="login-email"><?php esc_html_e( 'Correo Electrónico', 'celzimo-theme' ); ?></label>
                    <input type="email" id="login-email" class="form-control" placeholder="tu@email.com" required>
                </div>
                <div class="form-group">
                    <label for="login-password"><?php esc_html_e( 'Contraseña', 'celzimo-theme' ); ?></label>
                    <input type="password" id="login-password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-actions">
                    <a href="#" class="forgot-password"><?php esc_html_e( '¿Olvidaste tu contraseña?', 'celzimo-theme' ); ?></a>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Ingresar', 'celzimo-theme' ); ?></button>
            </form>
            <div class="login-footer">
                <p><?php esc_html_e( '¿No tienes una cuenta?', 'celzimo-theme' ); ?> <a href="<?php echo esc_url( home_url( '/registro/' ) ); ?>" class="register-link"><?php esc_html_e( 'Regístrate', 'celzimo-theme' ); ?></a></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
