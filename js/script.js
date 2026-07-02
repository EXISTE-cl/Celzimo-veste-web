// Cart State
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let currentCategory = 'all';
let maxPrice = 400000;

// DOM Elements
const productsContainer = document.getElementById('products-container');
const cartBadge = document.getElementById('cart-badge');
const drawerCartCount = document.getElementById('drawer-cart-count');
const cartItemsContainer = document.getElementById('cart-items-container');
const cartTotalAmount = document.getElementById('cart-total-amount');
const emptyCartMsg = document.getElementById('empty-cart-msg');
const checkoutBtn = document.getElementById('checkout-btn');

const cartDrawer = document.getElementById('cart-drawer');
const cartOverlay = document.getElementById('cart-overlay');
const openCartBtn = document.getElementById('open-cart');
const closeCartBtn = document.getElementById('close-cart');
const continueShoppingBtn = document.getElementById('continue-shopping');
const header = document.getElementById('main-header');

// Login Elements
const openLoginBtn = document.getElementById('open-login-btn');
const loginModal = document.getElementById('login-modal');
const loginOverlay = document.getElementById('login-overlay');
const closeLoginBtn = document.getElementById('close-login');
const loginForm = document.getElementById('login-form');

// Filter Elements
const categoryRadios = document.querySelectorAll('input[name="category"]');
const priceFilter = document.getElementById('price-filter');
const priceVal = document.getElementById('price-val');
const resetFiltersBtn = document.getElementById('reset-filters');
const catalogTitle = document.getElementById('catalog-title');
const catalogCount = document.getElementById('catalog-count');
const menuFilters = document.querySelectorAll('.menu-filter');

// Format Currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(amount);
};

// Generic Render Function
const renderProductList = (productsToRender, containerElement) => {
    if (!containerElement) return;
    containerElement.innerHTML = '';
    
    if (productsToRender.length === 0) {
        containerElement.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--color-text-light); padding: 40px 0;">No se encontraron productos.</p>';
        return;
    }
    
    productsToRender.forEach(product => {
        const productEl = document.createElement('div');
        productEl.classList.add('product-card');
        
        let promoHTML = '';
        if (product.discount) {
            promoHTML = `
                <div class="promo-banner">
                    <div class="promo-left">
                        <span class="promo-text">${product.promoText}</span>
                    </div>
                    <div class="promo-right">
                        <span class="promo-discount">${product.discount}</span>
                        <div class="promo-percent">
                            <span class="percent-sign">%</span>
                            <span class="off-text">OFF</span>
                        </div>
                    </div>
                </div>
            `;
        }

        let colorsHTML = '';
        if (product.colors && product.colors.length > 0) {
            const maxColors = 4;
            const visibleColors = product.colors.slice(0, maxColors);
            const extraColors = product.colors.length - maxColors;
            
            let colorThumbs = visibleColors.map((color, index) => {
                const activeClass = index === 0 ? 'active' : '';
                return `<img src="${color}" class="color-thumb ${activeClass}" alt="Color variant" onclick="selectColor(event, this, '${color}', ${index})" style="filter: hue-rotate(${index * 45}deg);">`;
            }).join('');
            let extraText = extraColors > 0 ? `<span class="extra-colors">+${extraColors}</span>` : '';
            
            colorsHTML = `
                <div class="product-colors-container">
                    ${colorThumbs}
                    ${extraText}
                </div>
            `;
        }

        const deliveryDotClass = product.availableDelivery ? 'dot-green' : 'dot-red';
        const storeDotClass = product.availableStore ? 'dot-green' : 'dot-red';

        productEl.innerHTML = `
            <div class="product-image">
                <a href="product.html?id=${product.id}">
                    <img src="${product.image}" alt="${product.title}" class="main-product-img">
                </a>
                <button class="wishlist-btn" aria-label="Añadir a favoritos">
                    <i class="ti ti-heart"></i>
                </button>
                ${promoHTML}
                <div class="add-to-cart-overlay">
                    <button class="btn btn-primary btn-block" onclick="addToCart(${product.id})">Añadir al Carrito</button>
                </div>
            </div>
            <div class="product-info-card">
                <h3 class="product-title-card">${product.title}</h3>
                <div class="product-price-card">${formatCurrency(product.price)} CLP</div>
                
                ${colorsHTML}
                
                <div class="availability-info">
                    <div class="availability-item">
                        <span class="dot ${deliveryDotClass}"></span>
                        <span>Despacho a domicilio</span>
                    </div>
                    <div class="availability-item">
                        <span class="dot ${storeDotClass}"></span>
                        <span>Retiro en tienda</span>
                    </div>
                </div>
            </div>
        `;
        
        containerElement.appendChild(productEl);
    });
};

// Render Products for Catalog
const renderProducts = () => {
    if (!productsContainer) return;
    
    // Filter logic
    const filteredProducts = products.filter(p => {
        const matchCategory = currentCategory === 'all' || p.category === currentCategory;
        const matchPrice = p.price <= maxPrice;
        return matchCategory && matchPrice;
    });
    
    // Update count
    if (catalogCount) {
        catalogCount.innerText = `Mostrando ${filteredProducts.length} producto${filteredProducts.length !== 1 ? 's' : ''}`;
    }
    
    renderProductList(filteredProducts, productsContainer);
};

// Render Home Sections
const renderHomeSections = () => {
    const topVentasContainer = document.getElementById('top-ventas-container');
    const destacadosContainer = document.getElementById('destacados-container');
    
    if (topVentasContainer) {
        const topVentas = products.filter(p => [1, 3, 5, 7].includes(p.id));
        renderProductList(topVentas, topVentasContainer);
    }
    
    if (destacadosContainer) {
        const destacados = products.filter(p => [2, 4, 6].includes(p.id));
        renderProductList(destacados, destacadosContainer);
    }
};

// Handle Filter Changes
if (categoryRadios) {
    categoryRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            currentCategory = e.target.value;
            catalogTitle.innerText = currentCategory === 'all' ? 'Novedades' : currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1);
            renderProducts();
        });
    });
}

if (priceFilter) {
    priceFilter.addEventListener('input', (e) => {
        maxPrice = parseInt(e.target.value);
        priceVal.innerText = formatCurrency(maxPrice);
        renderProducts();
    });
}

if (resetFiltersBtn) {
    resetFiltersBtn.addEventListener('click', () => {
        currentCategory = 'all';
        maxPrice = 400000;
        
        // Reset UI
        priceFilter.value = maxPrice;
        priceVal.innerText = formatCurrency(maxPrice);
        document.querySelector('input[name="category"][value="all"]').checked = true;
        catalogTitle.innerText = 'Novedades';
        
        renderProducts();
    });
}

// Menu Filter Links
if (menuFilters) {
    menuFilters.forEach(link => {
        link.addEventListener('click', (e) => {
            // e.preventDefault();
            const category = e.target.getAttribute('data-category');
            currentCategory = category;
            
            // Update sidebar UI to match
            const radioToSelect = document.querySelector(`input[name="category"][value="${category}"]`);
            if (radioToSelect) {
                radioToSelect.checked = true;
            } else if (document.querySelector('input[name="category"][value="all"]')) {
                document.querySelector('input[name="category"][value="all"]').checked = true;
            }
            
            if (catalogTitle) {
                catalogTitle.innerText = currentCategory === 'all' ? 'Novedades' : currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1);
            }
            renderProducts();
        });
    });
}

// Cart Functions
const updateCartUI = () => {
    // Update Counts
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartBadge) cartBadge.innerText = totalItems;
    if (drawerCartCount) drawerCartCount.innerText = totalItems;
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update Total
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    if (cartTotalAmount) cartTotalAmount.innerText = formatCurrency(totalPrice);
    
    // Handle Empty State
    if (cart.length === 0) {
        if (emptyCartMsg) emptyCartMsg.style.display = 'flex';
        if (checkoutBtn) checkoutBtn.disabled = true;
        // Remove all item elements
        if (cartItemsContainer) {
            const itemElements = cartItemsContainer.querySelectorAll('.cart-item');
            itemElements.forEach(el => el.remove());
        }
    } else {
        if (emptyCartMsg) emptyCartMsg.style.display = 'none';
        if (checkoutBtn) checkoutBtn.disabled = false;
        renderCartItems();
    }
};

const renderCartItems = () => {
    // First, clear existing items (but keep the empty message div)
    const itemElements = cartItemsContainer.querySelectorAll('.cart-item');
    itemElements.forEach(el => el.remove());
    
    cart.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.classList.add('cart-item');
        
        itemEl.innerHTML = `
            <img src="${item.image}" alt="${item.title}" class="cart-item-img">
            <div class="cart-item-details">
                <h4 class="cart-item-title">${item.title}</h4>
                <div class="cart-item-price">${formatCurrency(item.price)}</div>
                
                <div class="cart-item-actions">
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <input type="number" class="qty-input" value="${item.quantity}" readonly>
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                    <button class="remove-btn" onclick="removeFromCart(${item.id})">Eliminar</button>
                </div>
            </div>
        `;
        
        // Append before the empty message
        cartItemsContainer.insertBefore(itemEl, emptyCartMsg);
    });
};

window.addToCart = (productId, qty = 1) => {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += qty;
    } else {
        cart.push({ ...product, quantity: qty });
    }
    
    updateCartUI();
    showToast(`${product.title} añadido al carrito`);
    openCart();
};

window.updateQuantity = (productId, change) => {
    const itemIndex = cart.findIndex(item => item.id === productId);
    if (itemIndex === -1) return;
    
    const newQty = cart[itemIndex].quantity + change;
    
    if (newQty <= 0) {
        removeFromCart(productId);
    } else {
        cart[itemIndex].quantity = newQty;
        updateCartUI();
    }
};

window.removeFromCart = (productId) => {
    cart = cart.filter(item => item.id !== productId);
    updateCartUI();
};

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
            mainImg.style.filter = `hue-rotate(${index * 45}deg)`;
        }
    }
};

// UI Interactions
const openCart = () => {
    cartDrawer.classList.add('active');
    cartOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
};

const closeCart = () => {
    cartDrawer.classList.remove('active');
    cartOverlay.classList.remove('active');
    document.body.style.overflow = '';
};

if (openCartBtn) openCartBtn.addEventListener('click', openCart);
if (closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
if (cartOverlay) cartOverlay.addEventListener('click', closeCart);
if (continueShoppingBtn) continueShoppingBtn.addEventListener('click', closeCart);
if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
        window.location.href = 'basket.html';
    });
}

// Login UI Interactions
const openLogin = () => {
    if(loginModal && loginOverlay) {
        loginModal.classList.add('active');
        loginOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
};

const closeLogin = () => {
    if(loginModal && loginOverlay) {
        loginModal.classList.remove('active');
        loginOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
};

if(openLoginBtn) {
    openLoginBtn.addEventListener('click', () => {
        if (localStorage.getItem('isLoggedIn') === 'true') {
            window.location.href = 'account.html';
        } else {
            openLogin();
        }
    });
}
    
if(closeLoginBtn) closeLoginBtn.addEventListener('click', closeLogin);
    
if(loginOverlay) {
    loginOverlay.addEventListener('click', (e) => {
        if (e.target === loginOverlay) {
            closeLogin();
        }
    });
}

const loginFormSubmit = document.getElementById('login-form-inner') || document.getElementById('login-form');
if (loginFormSubmit) {
    loginFormSubmit.addEventListener('submit', (e) => {
        e.preventDefault();
        // Store login state
        localStorage.setItem('isLoggedIn', 'true');
        showToast('Sesión iniciada correctamente');
        closeLogin();
        // Notice we do NOT redirect to account.html, we stay on the page
    });
}

// Sticky Header
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Toast Notification System
const createToastContainer = () => {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    return container;
};

const showToast = (message) => {
    const container = createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="ti ti-check"></i> ${message}`;
    
    container.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const catParam = urlParams.get('category');
    if (catParam) {
        currentCategory = catParam;
        
        const radioToSelect = document.querySelector(`input[name="category"][value="${catParam}"]`);
        if (radioToSelect) {
            radioToSelect.checked = true;
        }
        
        if (catalogTitle) {
            catalogTitle.innerText = currentCategory === 'all' ? 'Novedades' : currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1);
        }
    }

    renderProducts();
    renderHomeSections();
    updateCartUI();
});

