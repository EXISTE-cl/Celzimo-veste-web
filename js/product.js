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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <h4 style="margin: 0;">Talla</h4>
                    <button id="size-guide-trigger" style="font-size: 0.85rem; color: var(--color-accent, #c49a45); text-decoration: underline; font-weight: 500; display: flex; align-items: center; gap: 4px; background: none; border: none; padding: 0;">
                        <i class="ti ti-ruler-2" style="font-size: 1rem;"></i> Guía de tallas
                    </button>
                </div>
                <div class="size-options">
                    ${product.sizes.map(size => `<button class="size-btn">${size}</button>`).join('')}
                </div>
            </div>
        `;
    }

    // Extract unique colors based on variants or default title mapping
    let uniqueColors = [];
    if (product.variants && product.variants.length > 0) {
        uniqueColors = [...new Set(product.variants.map(v => v.color))];
    } else {
        if (product.title.toLowerCase().includes('indigo')) uniqueColors = ['Indigo'];
        else if (product.title.toLowerCase().includes('blue')) uniqueColors = ['Classic Blue'];
        else if (product.title.toLowerCase().includes('light')) uniqueColors = ['Light Wash'];
        else if (product.title.toLowerCase().includes('cuero') || product.title.toLowerCase().includes('monarch')) uniqueColors = ['Negro'];
        else uniqueColors = ['Único'];
    }

    let colorsHTML = '';
    if (uniqueColors.length > 0) {
        let colorButtons = uniqueColors.map((color, index) => {
            return `<button class="color-btn" data-color="${color}" style="padding: 8px 16px; border: 1px solid #d1d5db; background: none; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; cursor: pointer; border-radius: 2px; transition: all 0.2s;">${color}</button>`;
        }).join('');
        
        colorsHTML = `
            <div class="product-colors" style="margin-bottom: 20px;">
                <h4 style="margin-bottom: 8px;">Color</h4>
                <div class="color-options" style="display: flex; gap: 10px;">
                    ${colorButtons}
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
                                <div class="product-price-card">
                                    ${p.compare_at_price && p.compare_at_price > p.price ? `
                                        <span class="sale-price" style="font-weight: 600; color: #ef4444;">${formatCurrency(p.price)} CLP</span>
                                        <span class="original-price" style="text-decoration: line-through; color: #9ca3af; font-size: 0.85rem; margin-left: 8px;">${formatCurrency(p.compare_at_price)} CLP</span>
                                    ` : `
                                        <span class="current-price" style="font-weight: 600;">${formatCurrency(p.price)} CLP</span>
                                    `}
                                </div>
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

    let galleryHTML = '';
    if (product.colors && product.colors.length > 1) {
        let thumbs = product.colors.map((color, index) => {
            const activeClass = index === 0 ? 'active' : '';
            return `<img src="${color}" class="gallery-thumb ${activeClass}" alt="Vista de producto" onclick="selectProductGalleryImg(event, this, '${color}')" style="width: 60px; height: 80px; object-fit: cover; border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.2s; border-radius: 2px; flex-shrink: 0;">`;
        }).join('');
        
        galleryHTML = `
            <div class="product-gallery" style="display: flex; gap: 10px; overflow-x: auto; margin-top: 15px; justify-content: center; padding-bottom: 5px;">
                ${thumbs}
            </div>
        `;
    }

    let priceHTML = `<div class="price" style="font-size: 2rem; font-weight: 700; margin-bottom: 20px;">${formatCurrency(product.price)}</div>`;
    if (product.compare_at_price && product.compare_at_price > product.price) {
        const discountPercent = Math.round(((product.compare_at_price - product.price) / product.compare_at_price) * 100);
        priceHTML = `
            <div class="price-container" style="display: flex; align-items: baseline; gap: 15px; margin-bottom: 20px;">
                <span class="sale-price" style="font-size: 2rem; font-weight: 700; color: #ef4444;">${formatCurrency(product.price)}</span>
                <span class="original-price" style="font-size: 1.25rem; text-decoration: line-through; color: #9ca3af;">${formatCurrency(product.compare_at_price)}</span>
                <span class="discount-badge" style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-left: 5px; position: relative; top: -3px;">${discountPercent}% OFF</span>
            </div>
        `;
    }

    container.innerHTML = `
        <style>
            .gallery-thumb.active {
                border-color: var(--color-accent, #c49a45) !important;
                border-width: 2px !important;
            }
            .color-btn.active {
                border-color: var(--color-primary, #0b1c3a) !important;
                background-color: var(--color-primary, #0b1c3a) !important;
                color: white !important;
            }
        </style>
        <div class="product-page-layout">
            <div class="product-page-image" style="display: flex; flex-direction: column;">
                <img id="product-page-main-img" src="${product.image}" alt="${product.title}" style="width: 100%; border-radius: 4px; object-fit: cover; max-height: 550px;">
                ${galleryHTML}
            </div>
            <div class="product-page-info">
                <div class="breadcrumbs">
                    <a href="index.html">Inicio</a> / <a href="index.html#productos">${product.category}</a> / <span>${product.title}</span>
                </div>
                <div class="brand">${product.brand}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-bottom: 5px; text-transform: uppercase;">SKU: CV-${1000 + product.id}</div>
                <h1 class="title">${product.title}</h1>
                ${priceHTML}
                
                <p class="description">${product.description || 'Sin descripción disponible.'}</p>
                
                ${colorsHTML}
                ${sizesHTML}
                
                <div id="stock-status-msg" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #10b981;">
                    Stock disponible: --
                </div>
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
                            <li><strong>Origen:</strong> Fabricado en Chile</li>
                            <li><strong>Garantía:</strong> 6 meses por defectos de fábrica</li>
                            <li><strong>Fit:</strong> Fiel a la talla. Sugerimos elegir tu talla habitual.</li>
                        </ul>
                    </div>
                </div>

                <div class="tech-specs-accordion" style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                    <button class="accordion-btn" id="size-guide-accordion-btn" style="width: 100%; padding: 15px 0; display: flex; justify-content: space-between; align-items: center; background: none; border: none; font-size: 1rem; font-weight: 600; color: #1f2937; cursor: pointer; text-align: left;">
                        Guía de Tallas
                        <i class="ti ti-chevron-down" style="transition: transform 0.3s;"></i>
                    </button>
                    <div class="accordion-content" id="size-guide-accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; font-size: 0.9rem; color: #4b5563;">
                        <div class="size-guide-container" style="padding: 10px 0 20px 0;">
                            <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 250px;">
                                    <p style="margin-bottom: 12px; font-weight: 600; color: #1f2937;">¿Cómo medirte?</p>
                                    <ul style="list-style-type: none; padding-left: 0; line-height: 1.6; font-size: 0.85rem; color: #4b5563;">
                                        <li style="margin-bottom: 10px; display: flex; align-items: flex-start;">
                                            <span style="display: inline-block; min-width: 20px; height: 20px; border-radius: 50%; background-color: var(--color-primary, #0b1c3a); color: white; text-align: center; line-height: 20px; font-weight: bold; margin-right: 10px; font-size: 0.75rem; flex-shrink: 0;">A</span>
                                            <span><strong>Contorno de Cintura:</strong> Mide la parte más estrecha de tu cintura, justo por encima del ombligo.</span>
                                        </li>
                                        <li style="margin-bottom: 10px; display: flex; align-items: flex-start;">
                                            <span style="display: inline-block; min-width: 20px; height: 20px; border-radius: 50%; background-color: var(--color-primary, #0b1c3a); color: white; text-align: center; line-height: 20px; font-weight: bold; margin-right: 10px; font-size: 0.75rem; flex-shrink: 0;">B</span>
                                            <span><strong>Contorno de Cadera:</strong> Mide la parte más ancha de tu cadera, aproximadamente a la altura de tus glúteos.</span>
                                        </li>
                                        <li style="margin-bottom: 10px; display: flex; align-items: flex-start;">
                                            <span style="display: inline-block; min-width: 20px; height: 20px; border-radius: 50%; background-color: var(--color-primary, #0b1c3a); color: white; text-align: center; line-height: 20px; font-weight: bold; margin-right: 10px; font-size: 0.75rem; flex-shrink: 0;">C</span>
                                            <span><strong>Contorno de Muslo:</strong> Mide el contorno de tu muslo en su parte más ancha.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p style="margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tabla de Medidas</p>
                            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                                <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.85rem; border: 1px solid #e5e7eb;">
                                    <thead>
                                        <tr style="background-color: var(--color-primary, #0b1c3a); color: white;">
                                            <th style="padding: 12px 10px; font-weight: 600; border: 1px solid #e5e7eb;">Talla Chilena</th>
                                            <th style="padding: 12px 10px; font-weight: 600; border: 1px solid #e5e7eb;">A - Cintura (cm)</th>
                                            <th style="padding: 12px 10px; font-weight: 600; border: 1px solid #e5e7eb;">B - Cadera (cm)</th>
                                            <th style="padding: 12px 10px; font-weight: 600; border: 1px solid #e5e7eb;">C - Muslo (cm)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #ffffff;">
                                            <td style="padding: 10px; font-weight: bold; border: 1px solid #e5e7eb; background-color: #f9fafb; color: #1f2937;">38</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">68 - 71</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">94 - 97</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">56 - 58</td>
                                        </tr>
                                        <tr style="background-color: #f9fafb;">
                                            <td style="padding: 10px; font-weight: bold; border: 1px solid #e5e7eb; background-color: #f3f4f6; color: #1f2937;">40</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">72 - 75</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">98 - 101</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">59 - 61</td>
                                        </tr>
                                        <tr style="background-color: #ffffff;">
                                            <td style="padding: 10px; font-weight: bold; border: 1px solid #e5e7eb; background-color: #f9fafb; color: #1f2937;">42</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">76 - 79</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">102 - 105</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">62 - 64</td>
                                        </tr>
                                        <tr style="background-color: #f9fafb;">
                                            <td style="padding: 10px; font-weight: bold; border: 1px solid #e5e7eb; background-color: #f3f4f6; color: #1f2937;">44</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">80 - 83</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">106 - 109</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">65 - 67</td>
                                        </tr>
                                        <tr style="background-color: #ffffff;">
                                            <td style="padding: 10px; font-weight: bold; border: 1px solid #e5e7eb; background-color: #f9fafb; color: #1f2937;">46</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">84 - 87</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">110 - 113</td>
                                            <td style="padding: 10px; border: 1px solid #e5e7eb;">68 - 70</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <p style="margin-top: 15px; font-style: italic; font-size: 0.825rem; color: #6b7280; text-align: center; line-height: 1.4;">
                                *Consejo: Si estás entre dos tallas, te recomendamos elegir la más grande.*
                            </p>
                        </div>
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
            updateSelectedVariant();
        });
    });

    // Color selection logic
    const colorBtns = container.querySelectorAll('.color-btn');
    colorBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            colorBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            updateSelectedVariant();
        });
    });

    // Qty logic
    const qtyDec = container.querySelector('#prod-page-qty-dec');
    const qtyInc = container.querySelector('#prod-page-qty-inc');
    const qtyInput = container.querySelector('#prod-page-qty');
    
    // Function to calculate selected variant stock
    const getSelectedStock = () => {
        const activeSizeBtn = container.querySelector('.size-btn.active');
        const activeColorBtn = container.querySelector('.color-btn.active');
        
        const selectedSize = activeSizeBtn ? activeSizeBtn.textContent.trim() : null;
        const selectedColor = activeColorBtn ? activeColorBtn.getAttribute('data-color') : null;
        
        if (product.variants && product.variants.length > 0) {
            const variant = product.variants.find(v => 
                (selectedSize ? v.size === selectedSize : true) && 
                (selectedColor ? v.color === selectedColor : true)
            );
            return variant ? variant.stock : 0;
        } else {
            return 5; // Default stock for static products
        }
    };

    // Function to update visual controls based on selected variant stock
    const updateSelectedVariant = () => {
        const stock = getSelectedStock();
        const addToCartBtn = container.querySelector('.product-actions button.btn-primary');
        const stockStatusEl = container.querySelector('#stock-status-msg');
        
        if (stock <= 0) {
            if (qtyInput) qtyInput.value = 0;
            if (addToCartBtn) {
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Sin Stock';
                addToCartBtn.style.backgroundColor = '#9ca3af';
                addToCartBtn.style.borderColor = '#9ca3af';
            }
            if (stockStatusEl) {
                stockStatusEl.textContent = 'Sin stock disponible en esta combinación';
                stockStatusEl.style.color = '#ef4444';
            }
        } else {
            if (qtyInput) {
                let currentVal = parseInt(qtyInput.value) || 0;
                if (currentVal <= 0) qtyInput.value = 1;
                else if (currentVal > stock) qtyInput.value = stock;
            }
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = 'Añadir al Carrito';
                addToCartBtn.style.backgroundColor = '';
                addToCartBtn.style.borderColor = '';
            }
            if (stockStatusEl) {
                stockStatusEl.textContent = `Stock disponible: ${stock} unidades`;
                stockStatusEl.style.color = '#10b981';
            }
        }

        // Update quantity button visual states
        if (qtyDec && qtyInc && qtyInput) {
            let val = parseInt(qtyInput.value) || 0;
            qtyDec.disabled = (val <= 1);
            qtyInc.disabled = (val >= stock || stock <= 0);
            
            qtyDec.style.opacity = qtyDec.disabled ? '0.5' : '1';
            qtyInc.style.opacity = qtyInc.disabled ? '0.5' : '1';
        }
    };

    if (qtyDec && qtyInc && qtyInput) {
        qtyDec.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 0;
            if (val > 1) {
                qtyInput.value = val - 1;
                updateSelectedVariant();
            }
        });
        qtyInc.addEventListener('click', () => {
            const stock = getSelectedStock();
            let val = parseInt(qtyInput.value) || 0;
            if (val < stock) {
                qtyInput.value = val + 1;
                updateSelectedVariant();
            }
        });
    }

    // Select first size and color by default
    const firstSizeBtn = container.querySelector('.size-btn');
    if (firstSizeBtn) firstSizeBtn.classList.add('active');
    
    const firstColorBtn = container.querySelector('.color-btn');
    if (firstColorBtn) firstColorBtn.classList.add('active');
    
    updateSelectedVariant();

    // Size guide trigger click logic
    const sizeGuideTrigger = container.querySelector('#size-guide-trigger');
    if (sizeGuideTrigger) {
        sizeGuideTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            const sizeGuideBtn = container.querySelector('#size-guide-accordion-btn');
            const sizeGuideContent = container.querySelector('#size-guide-accordion-content');
            if (sizeGuideBtn && sizeGuideContent) {
                // If not open, open it
                if (!sizeGuideBtn.classList.contains('active')) {
                    sizeGuideBtn.classList.add('active');
                    const icon = sizeGuideBtn.querySelector('i');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                    sizeGuideContent.style.maxHeight = sizeGuideContent.scrollHeight + "px";
                }
                // Scroll to it
                sizeGuideBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Accordion logic for all accordion buttons
    const accordionBtns = container.querySelectorAll('.accordion-btn');
    accordionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
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
    });

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

window.selectProductGalleryImg = (event, element, newSrc) => {
    event.preventDefault();
    event.stopPropagation();
    
    // Update active class on gallery thumbnails
    const container = element.closest('.product-gallery');
    if (container) {
        container.querySelectorAll('.gallery-thumb').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }
    
    // Update main image
    const mainImg = document.getElementById('product-page-main-img');
    if (mainImg) {
        mainImg.src = newSrc;
    }
};

