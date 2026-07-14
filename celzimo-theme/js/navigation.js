/**
 * Theme Navigation and Interactive UI Elements
 * Handles Mobile Menu, Login Modal, Cart Drawer, and Scroll effects.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Header Scroll Effect
    const header = document.getElementById('main-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // Mobile Menu Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const desktopNav = document.querySelector('.desktop-nav');
    if (mobileToggle && desktopNav) {
        mobileToggle.addEventListener('click', () => {
            desktopNav.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (icon) {
                if (desktopNav.classList.contains('active')) {
                    icon.className = 'ti ti-x';
                } else {
                    icon.className = 'ti ti-menu-2';
                }
            }
        });
    }

    // Cart Drawer Toggle (only if WooCommerce side-cart is not overriding this)
    const cartDrawer = document.getElementById('cart-drawer');
    const cartOverlay = document.getElementById('cart-overlay');
    const openCartBtn = document.getElementById('open-cart');
    const closeCartBtn = document.getElementById('close-cart');
    const continueShoppingBtn = document.getElementById('continue-shopping');

    const toggleCart = (show) => {
        if (!cartDrawer || !cartOverlay) return;
        if (show) {
            cartDrawer.classList.add('active');
            cartOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            cartDrawer.classList.remove('active');
            cartOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if (openCartBtn) {
        openCartBtn.addEventListener('click', (e) => {
            // If WooCommerce cart link is an actual page redirect (fallback), do not toggle side cart
            const isLink = openCartBtn.tagName.toLowerCase() === 'a' && !openCartBtn.id;
            if (!isLink) {
                e.preventDefault();
                toggleCart(true);
            }
        });
    }
    if (closeCartBtn) closeCartBtn.addEventListener('click', () => toggleCart(false));
    if (cartOverlay) cartOverlay.addEventListener('click', () => toggleCart(false));
    if (continueShoppingBtn) continueShoppingBtn.addEventListener('click', () => toggleCart(false));


    // Login Modal Toggle
    const loginModal = document.getElementById('login-modal');
    const loginOverlay = document.getElementById('login-overlay');
    const openLoginBtn = document.getElementById('open-login-btn');
    const closeLoginBtn = document.getElementById('close-login');

    const toggleLogin = (show) => {
        if (!loginModal || !loginOverlay) return;
        if (show) {
            loginModal.classList.add('active');
            loginOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            loginModal.classList.remove('active');
            loginOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleLogin(true);
        });
    }
    if (closeLoginBtn) closeLoginBtn.addEventListener('click', () => toggleLogin(false));
    if (loginOverlay) loginOverlay.addEventListener('click', () => toggleLogin(false));

    // Handle ESC key to close modal & drawer
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleCart(false);
            toggleLogin(false);
        }
    });
});

window.selectColor = (event, element, newSrc, index) => {
    event.preventDefault();
    event.stopPropagation();
    
    // Update active class on thumbnails
    const container = element.closest('.product-colors-container');
    if (container) {
        container.querySelectorAll('.color-thumb').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }
    
    // Update main image
    const card = element.closest('.product-card');
    if (card) {
        const mainImg = card.querySelector('.main-product-img');
        if (mainImg) {
            mainImg.src = newSrc;
            mainImg.srcset = ''; // Clear responsive srcset so new image is displayed
            mainImg.style.filter = 'none';
        }
    }
};
