// product.js
document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-container');
    if (!productContainer) return;

    // Get product ID from URL query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const productId = parseInt(urlParams.get('id'));

    if (!productId || isNaN(productId)) {
        productContainer.innerHTML = '<div class="error-msg">Producto no encontrado. <a href="index.html">Volver al inicio</a></div>';
        return;
    }

    // Find the product in the data
    const product = products.find(p => p.id === productId);

    if (!product) {
        productContainer.innerHTML = '<div class="error-msg">Producto no encontrado. <a href="index.html">Volver al inicio</a></div>';
        return;
    }

    // Render product details
    renderProductDetails(product, productContainer);
});

function renderProductDetails(product, container) {
    let sizesHTML = '';
    if (product.sizes && product.sizes.length > 0) {
        sizesHTML = `
            <div class="product-sizes">
                <h4>Talla</h4>
                <div class="size-options">
                    ${product.sizes.map(size => `<button class="size-btn">${size}</button>`).join('')}
                </div>
            </div>
        `;
    }

    let colorsHTML = '';
    if (product.colors && product.colors.length > 0) {
        let colorThumbs = product.colors.map((color, index) => {
            const activeClass = index === 0 ? 'active' : '';
            return `<img src="${color}" class="color-thumb ${activeClass}" alt="Color variant" onclick="selectProductPageColor(event, this, '${color}', ${index})" style="filter: hue-rotate(${index * 45}deg); width: 45px; height: 60px;">`;
        }).join('');
        
        colorsHTML = `
            <div class="product-sizes" style="margin-bottom: 20px;">
                <h4>Color</h4>
                <div class="product-colors-container" style="gap: 12px; margin-bottom: 0;">
                    ${colorThumbs}
                </div>
            </div>
        `;
    }

    // Similar Products HTML
    const similarProducts = products.filter(p => p.category === product.category && p.id !== product.id).slice(0, 4);
    let similarProductsHTML = '';
    if (similarProducts.length > 0) {
        similarProductsHTML = `
            <div class="similar-products-section" style="margin-top: 80px;">
                <h3 style="margin-bottom: 30px; font-size: 1.5rem; text-align: center;">Productos Similares</h3>
                <div class="products-grid">
                    ${similarProducts.map(p => `
                        <div class="product-card">
                            <div class="product-image">
                                <a href="product.html?id=${p.id}">
                                    <img src="${p.image}" alt="${p.title}" class="main-product-img">
                                </a>
                                <div class="add-to-cart-overlay">
                                    <button class="btn btn-primary btn-block" onclick="addToCart(${p.id})">Añadir al Carrito</button>
                                </div>
                            </div>
                            <div class="product-info-card">
                                <h3 class="product-title-card">${p.title}</h3>
                                <div class="product-price-card">${formatCurrency(p.price)} CLP</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    // Reviews HTML
    const reviewsHTML = `
        <div class="reviews-section" style="margin-top: 60px; border-top: 1px solid #e5e7eb; padding-top: 40px;">
            <h3 style="margin-bottom: 20px; font-size: 1.5rem;">Calificaciones y Comentarios</h3>
            <div class="reviews-summary" style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <div class="stars" style="color: #fbbf24; font-size: 1.25rem;">
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-half-filled"></i>
                </div>
                <span style="font-weight: 600; font-size: 1.2rem;">4.8 / 5.0</span>
                <span style="color: #6b7280;">(24 reseñas)</span>
            </div>
            
            <div class="review-item" style="border-bottom: 1px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong style="display: flex; align-items: center; gap: 8px;">María G. <i class="ti ti-rosette-discount-check-filled" style="color: #3b82f6;"></i></strong>
                    <span style="color: #9ca3af; font-size: 0.85rem;">Hace 2 semanas</span>
                </div>
                <div class="stars" style="color: #fbbf24; font-size: 0.9rem; margin-bottom: 10px;">
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                </div>
                <p style="color: #4b5563; font-size: 0.95rem;">Excelente calidad. El calce es perfecto y el material se nota muy duradero. Totalmente recomendado.</p>
            </div>
            
            <div class="review-item" style="border-bottom: 1px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong style="display: flex; align-items: center; gap: 8px;">Javiera S. <i class="ti ti-rosette-discount-check-filled" style="color: #3b82f6;"></i></strong>
                    <span style="color: #9ca3af; font-size: 0.85rem;">Hace 1 mes</span>
                </div>
                <div class="stars" style="color: #fbbf24; font-size: 0.9rem; margin-bottom: 10px;">
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star-filled"></i>
                    <i class="ti ti-star"></i>
                </div>
                <p style="color: #4b5563; font-size: 0.95rem;">Muy bonito, exactamente como en la foto. El envío fue rápido.</p>
            </div>
            
            <button class="btn btn-outline" id="load-more-reviews-btn" style="margin-top: 10px;">Ver más reseñas</button>
        </div>
    `;

    container.innerHTML = `
        <div class="product-page-layout">
            <div class="product-page-image">
                <img id="product-page-main-img" src="${product.image}" alt="${product.title}">
            </div>
            <div class="product-page-info">
                <div class="breadcrumbs">
                    <a href="index.html">Inicio</a> / <a href="index.html#productos">${product.category}</a> / <span>${product.title}</span>
                </div>
                <div class="brand">${product.brand}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-bottom: 5px; text-transform: uppercase;">SKU: CV-${1000 + product.id}</div>
                <h1 class="title">${product.title}</h1>
                <div class="price">${formatCurrency(product.price)}</div>
                
                <p class="description">${product.description || 'Sin descripción disponible.'}</p>
                
                ${colorsHTML}
                ${sizesHTML}
                
                <div class="product-actions" style="display: flex; gap: 15px; align-items: center;">
                    <div class="qty-control" style="height: 50px; border-color: #d1d5db; min-width: 120px;">
                        <button class="qty-btn" id="prod-page-qty-dec" style="height: 100%; width: 40px; font-size: 1.2rem;">-</button>
                        <input type="number" id="prod-page-qty" class="qty-input" value="1" readonly style="height: 100%; width: 40px; font-size: 1.1rem;">
                        <button class="qty-btn" id="prod-page-qty-inc" style="height: 100%; width: 40px; font-size: 1.2rem;">+</button>
                    </div>
                    <button class="btn btn-primary btn-large" style="flex: 1;" onclick="addToCartFromPage(${product.id})">Añadir al Carrito</button>
                </div>

                <div class="product-features">
                    <div class="feature"><i class="ti ti-truck"></i> Envío gratis a todo el país</div>
                    <div class="feature"><i class="ti ti-shield-check"></i> Garantía de autenticidad</div>
                    <div class="feature"><i class="ti ti-arrow-back-up"></i> Devoluciones en 30 días</div>
                </div>
                
                <div class="tech-specs-accordion" style="margin-top: 30px; border-top: 1px solid #e5e7eb;">
                    <button class="accordion-btn" style="width: 100%; padding: 15px 0; display: flex; justify-content: space-between; align-items: center; background: none; border: none; font-size: 1rem; font-weight: 600; color: #1f2937; cursor: pointer; text-align: left;">
                        Ficha Técnica del Producto
                        <i class="ti ti-chevron-down" style="transition: transform 0.3s;"></i>
                    </button>
                    <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; font-size: 0.9rem; color: #4b5563;">
                        <ul style="padding-bottom: 20px; list-style-type: disc; padding-left: 20px; line-height: 1.8; margin-top: 10px;">
                            <li><strong>Material Principal:</strong> 100% Calidad Premium</li>
                            <li><strong>Instrucciones de Cuidado:</strong> Lavado en seco o a máquina con agua fría (máx 30°C). No usar blanqueador.</li>
                            <li><strong>Origen:</strong> Diseñado en Italia</li>
                            <li><strong>Garantía:</strong> 6 meses por defectos de fábrica</li>
                            <li><strong>Fit:</strong> Fiel a la talla. Sugerimos elegir tu talla habitual.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        ${similarProductsHTML}
        ${reviewsHTML}
    `;

    // Size selection logic
    const sizeBtns = container.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            sizeBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
        });
    });

    // Qty logic
    const qtyDec = container.querySelector('#prod-page-qty-dec');
    const qtyInc = container.querySelector('#prod-page-qty-inc');
    const qtyInput = container.querySelector('#prod-page-qty');
    
    if (qtyDec && qtyInc && qtyInput) {
        qtyDec.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) qtyInput.value = val - 1;
        });
        qtyInc.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val < 10) qtyInput.value = val + 1;
        });
    }

    // Accordion logic
    const accordionBtn = container.querySelector('.accordion-btn');
    if (accordionBtn) {
        accordionBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            const content = this.nextElementSibling;
            
            if (this.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = "0px";
                icon.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Reviews button logic
    const loadMoreBtn = container.querySelector('#load-more-reviews-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            alert('¡Llegaste al final! En esta demo no hay más reseñas para cargar por el momento.');
        });
    }
}

window.addToCartFromPage = (productId) => {
    const qtyInput = document.getElementById('prod-page-qty');
    const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
    if (typeof addToCart === 'function') {
        addToCart(productId, qty);
    }
};

window.selectProductPageColor = (event, element, newSrc, index) => {
    event.preventDefault();
    event.stopPropagation();
    
    // Update active class on thumbnails
    const container = element.closest('.product-colors-container');
    if (container) {
        container.querySelectorAll('.color-thumb').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }
    
    // Update main image
    const mainImg = document.getElementById('product-page-main-img');
    if (mainImg) {
        mainImg.src = newSrc;
        mainImg.style.filter = `hue-rotate(${index * 45}deg)`;
    }
};
