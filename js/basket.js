// basket.js
document.addEventListener('DOMContentLoaded', () => {
    // State
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let appliedCoupon = null;
    let couponDiscountPercent = 0;
    
    // Blue Express shipping state
    let selectedRegion = 'metropolitana';
    let selectedCommune = 'santiago';
    let selectedShippingMethod = 'home'; // 'home' or 'pickup'
    let selectedPickupPoint = '';
    let trackingCodeGenerated = '';

    const BLUE_EXPRESS_POINTS = {
        'santiago': [
            { id: 'stgo-1', name: 'Punto Blue Express - Kiosko Huérfanos (Huérfanos 1020)' },
            { id: 'stgo-2', name: 'Punto Blue Express - Farmacia Ahumada (Paseo Ahumada 250)' },
            { id: 'stgo-3', name: 'Punto Blue Express - Bazar Central (San Diego 150)' }
        ],
        'las-condes': [
            { id: 'lc-1', name: 'Punto Blue Express - Minimarket Apoquindo (Av. Apoquindo 5500)' },
            { id: 'lc-2', name: 'Punto Blue Express - Kiosko Manquehue (Av. Manquehue Sur 320)' },
            { id: 'lc-3', name: 'Punto Blue Express - Tabaquería Vitacura (Av. Vitacura 3800)' }
        ],
        'providencia': [
            { id: 'prov-1', name: 'Punto Blue Express - Librería Providencia (Av. Providencia 2200)' },
            { id: 'prov-2', name: 'Punto Blue Express - Farmacia Cruz Verde (Pedro de Valdivia 150)' },
            { id: 'prov-3', name: 'Punto Blue Express - Kiosko Los Leones (Av. Los Leones 120)' }
        ],
        'vitacura': [
            { id: 'vit-1', name: 'Punto Blue Express - Tabaquería Vitacura (Av. Vitacura 4500)' },
            { id: 'vit-2', name: 'Punto Blue Express - Minimarket Américo Vespucio (Av. Américo Vespucio 1800)' }
        ],
        'nunoa': [
            { id: 'nun-1', name: 'Punto Blue Express - Bazar Irarrázaval (Av. Irarrázaval 3200)' },
            { id: 'nun-2', name: 'Punto Blue Express - Kiosko Plaza Ñuñoa (Plaza Ñuñoa 15)' }
        ]
    };
    
    // shipping delivery types per product
    // Key: product ID, Value: 'delivery' or 'pickup'
    let deliveryChoices = {};

    // DOM Elements
    const basketItemsCount = document.getElementById('basket-items-count');
    const basketItemsList = document.getElementById('basket-items-list');
    const emptyBasketMsg = document.getElementById('empty-basket');
    const summaryPanel = document.getElementById('basket-summary-panel');
    const summaryProdQty = document.getElementById('summary-prod-qty');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryDiscountRow = document.getElementById('summary-discount-row');
    const couponTagName = document.getElementById('coupon-tag-name');
    const summaryDiscount = document.getElementById('summary-discount');
    const summaryShipping = document.getElementById('summary-shipping');
    const summaryTotal = document.getElementById('summary-total');
    
    const shippingPromo = document.getElementById('shipping-promo');
    const promoShippingMsg = document.getElementById('promo-shipping-msg');
    const shippingProgress = document.getElementById('shipping-progress');

    // Coupon UI
    const couponToggle = document.getElementById('coupon-toggle');
    const couponWrapper = document.getElementById('coupon-wrapper');
    const couponCodeInput = document.getElementById('coupon-code');
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponMessage = document.getElementById('coupon-message');

    // Checkout modal elements
    const checkoutModalOverlay = document.getElementById('checkout-modal-overlay');
    const checkoutModal = document.getElementById('checkout-modal');
    const btnProceedCheckout = document.getElementById('btn-proceed-checkout');
    const closeCheckoutModal = document.getElementById('close-checkout-modal');
    
    const tabIndicatorDelivery = document.getElementById('tab-indicator-delivery');
    const tabIndicatorPayment = document.getElementById('tab-indicator-payment');
    const tabIndicatorConfirm = document.getElementById('tab-indicator-confirm');
    
    const modalStepDelivery = document.getElementById('modal-step-delivery');
    const modalStepPayment = document.getElementById('modal-step-payment');
    const modalStepConfirm = document.getElementById('modal-step-confirm');
    
    const deliveryForm = document.getElementById('delivery-form');
    const paymentForm = document.getElementById('payment-form');
    const modalPaymentTotal = document.getElementById('modal-payment-total');
    const btnFinishAll = document.getElementById('btn-finish-all');

    // Card Input Visual Sync
    const cardNumInput = document.getElementById('card-number');
    const cardHolderInput = document.getElementById('card-holder');
    const cardExpiryInput = document.getElementById('card-expiry');
    const cardNumDisp = document.getElementById('card-num-disp');
    const cardHolderDisp = document.getElementById('card-holder-disp');
    const cardExpDisp = document.getElementById('card-exp-disp');

    // Helpers
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(amount);
    };

    // Coupons database
    const VALID_COUPONS = {
        'CELZIMO10': 0.10,
        'DESCUENTO20': 0.20,
        'FALABELLA': 0.15
    };

    // Initialize delivery choices
    cart.forEach(item => {
        if (!deliveryChoices[item.id]) {
            deliveryChoices[item.id] = 'delivery'; // Default is delivery
        }
    });

    const saveCartAndRefresh = () => {
        localStorage.setItem('cart', JSON.stringify(cart));
        renderBasket();
    };

    // Render Cart Items
    const renderBasket = () => {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        basketItemsCount.innerText = totalItems;
        summaryProdQty.innerText = totalItems;

        if (cart.length === 0) {
            basketItemsList.innerHTML = '';
            emptyBasketMsg.style.display = 'block';
            summaryPanel.style.opacity = '0.5';
            summaryPanel.style.pointerEvents = 'none';
            shippingPromo.style.display = 'none';
            return;
        }

        emptyBasketMsg.style.display = 'none';
        summaryPanel.style.opacity = '1';
        summaryPanel.style.pointerEvents = 'all';
        shippingPromo.style.display = 'flex';

        basketItemsList.innerHTML = '';

        cart.forEach(item => {
            const isDelivery = deliveryChoices[item.id] === 'delivery';
            
            const itemEl = document.createElement('div');
            itemEl.className = 'basket-item-card';
            
            itemEl.innerHTML = `
                <div class="item-main-details">
                    <img src="${item.image}" alt="${item.title}" class="item-img">
                    <div class="item-info">
                        <span class="item-brand">${item.brand}</span>
                        <h4 class="item-title">${item.title}</h4>
                        <p class="item-spec">SKU: CV-${1000 + item.id} | Talla: M | Color: Único</p>
                        
                        <div class="qty-and-delete">
                            <div class="qty-control">
                                <button class="qty-btn dec-btn" data-id="${item.id}">-</button>
                                <input type="number" class="qty-input" value="${item.quantity}" readonly>
                                <button class="qty-btn inc-btn" data-id="${item.id}">+</button>
                            </div>
                            <button class="action-btn delete-btn" data-id="${item.id}">
                                <i class="ti ti-trash"></i> Eliminar
                            </button>
                            <button class="action-btn wishlist-btn-basket">
                                <i class="ti ti-heart"></i> Guardar
                            </button>
                        </div>
                    </div>
                    <div class="item-pricing">
                        <span class="current-price">${formatCurrency(item.price * item.quantity)}</span>
                        ${item.quantity > 1 ? `<span class="unit-price">${formatCurrency(item.price)} c/u</span>` : ''}
                    </div>
                </div>
                
                <!-- Shipping / Pickup choice section (Falabella style) -->
                <div class="item-delivery-selector">
                    <div class="delivery-option-box active" data-id="${item.id}" data-type="delivery">
                        <input type="radio" name="delivery-choice-${item.id}" id="del-${item.id}" checked>
                        <label for="del-${item.id}">
                            <i class="ti ti-truck"></i>
                            <div>
                                <strong>Despacho a domicilio</strong>
                                <span class="delivery-status">Llega mañana</span>
                            </div>
                        </label>
                    </div>
                </div>
            `;

            basketItemsList.appendChild(itemEl);
        });

        // Set up event listeners on newly generated items
        setupItemEventListeners();
        calculateSummary();
    };

    const setupItemEventListeners = () => {
        // Quantity Controls
        document.querySelectorAll('.dec-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.target.dataset.id);
                const item = cart.find(p => p.id === id);
                if (item) {
                    if (item.quantity > 1) {
                        item.quantity--;
                        saveCartAndRefresh();
                    } else {
                        // Confirm deletion
                        cart = cart.filter(p => p.id !== id);
                        saveCartAndRefresh();
                    }
                }
            });
        });

        document.querySelectorAll('.inc-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.target.dataset.id);
                const item = cart.find(p => p.id === id);
                if (item && item.quantity < 10) {
                    item.quantity++;
                    saveCartAndRefresh();
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.currentTarget.dataset.id);
                cart = cart.filter(p => p.id !== id);
                saveCartAndRefresh();
            });
        });

        // Delivery option boxes
        document.querySelectorAll('.delivery-option-box').forEach(box => {
            box.addEventListener('click', (e) => {
                const id = parseInt(box.dataset.id);
                const type = box.dataset.type;
                deliveryChoices[id] = type;
                
                // Toggle checked on inputs inside this box
                const radio = box.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                
                saveCartAndRefresh();
            });
        });
    };

    // Calculate subtotal, shipping, discounts, and total
    const calculateSummary = () => {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        summarySubtotal.innerText = formatCurrency(subtotal);

        // Free shipping target is $150.000
        const freeShippingLimit = 150000;
        let isShippingFree = subtotal >= freeShippingLimit;
        
        // Update Free Shipping Promo Banner
        if (isShippingFree) {
            promoShippingMsg.innerHTML = "<strong>¡Felicitaciones!</strong> Tienes despacho a domicilio gratis para tus productos.";
            shippingProgress.style.width = "100%";
        } else {
            const missingAmount = freeShippingLimit - subtotal;
            promoShippingMsg.innerHTML = `Te faltan <strong>${formatCurrency(missingAmount)}</strong> para obtener despacho GRATIS.`;
            const percent = Math.min((subtotal / freeShippingLimit) * 100, 100);
            shippingProgress.style.width = `${percent}%`;
        }

        // Calculate shipping costs with Blue Express
        let shippingTotal = 0;
        if (cart.length > 0) {
            if (!isShippingFree) {
                const isRM = selectedRegion === 'metropolitana';
                const isHome = selectedShippingMethod === 'home';
                
                if (isRM) {
                    shippingTotal = isHome ? 3990 : 2990;
                } else {
                    shippingTotal = isHome ? 5990 : 4990;
                }
            }
        }

        if (isShippingFree) {
            summaryShipping.innerText = 'Gratis';
            summaryShipping.className = 'free-badge';
        } else {
            const methodLabel = selectedShippingMethod === 'home' ? 'Domicilio (Blue Express)' : 'Punto Blue Express';
            summaryShipping.innerHTML = `${formatCurrency(shippingTotal)} <span style="font-size: 0.75rem; color: var(--color-text-muted); font-weight: normal; display: block; text-align: right; margin-top: 2px;">${methodLabel}</span>`;
            summaryShipping.classList.remove('free-badge');
        }

        // Apply discount coupon
        let discount = 0;
        if (appliedCoupon) {
            discount = Math.round(subtotal * couponDiscountPercent);
            summaryDiscount.innerText = `-${formatCurrency(discount)}`;
            couponTagName.innerText = appliedCoupon;
            summaryDiscountRow.style.display = 'flex';
        } else {
            summaryDiscountRow.style.display = 'none';
        }

        // Grand Total
        const total = subtotal - discount + (isShippingFree ? 0 : shippingTotal);
        summaryTotal.innerText = formatCurrency(total);
        modalPaymentTotal.innerText = formatCurrency(total);
    };

    // Toggle Coupon section
    if (couponToggle) {
        couponToggle.addEventListener('click', () => {
            couponToggle.classList.toggle('active');
            const icon = couponToggle.querySelector('i');
            if (couponToggle.classList.contains('active')) {
                couponWrapper.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                couponWrapper.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Apply coupon logic
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', () => {
            const rawCode = couponCodeInput.value.trim().toUpperCase();
            if (!rawCode) {
                couponMessage.innerText = 'Por favor ingresa un código.';
                couponMessage.className = 'coupon-feedback error';
                return;
            }

            if (VALID_COUPONS.hasOwnProperty(rawCode)) {
                appliedCoupon = rawCode;
                couponDiscountPercent = VALID_COUPONS[rawCode];
                
                couponMessage.innerText = `¡Cupón ${rawCode} aplicado con éxito (${couponDiscountPercent * 100}% desc.)!`;
                couponMessage.className = 'coupon-feedback success';
                
                calculateSummary();
            } else {
                appliedCoupon = null;
                couponDiscountPercent = 0;
                couponMessage.innerText = 'Cupón inválido o vencido.';
                couponMessage.className = 'coupon-feedback error';
                calculateSummary();
            }
        });
    }

    // Modal checkout flow controls
    const showCheckoutModal = () => {
        checkoutModalOverlay.classList.add('active');
        checkoutModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Autofill registered customer data if logged in
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        const registeredUser = JSON.parse(localStorage.getItem('registeredUser'));
        
        if (isLoggedIn && registeredUser) {
            const nameField = document.getElementById('checkout-name');
            const phoneField = document.getElementById('checkout-phone');
            
            if (nameField && registeredUser.name) {
                nameField.value = registeredUser.name;
            }
            if (phoneField && registeredUser.phone) {
                phoneField.value = registeredUser.phone;
            }
            
            // Optionally prefill a default address if none is present
            const addressField = document.getElementById('checkout-address');
            if (addressField && !addressField.value) {
                addressField.value = 'Av. Vitacura 2670, Oficina 501';
            }
        }
    };

    const hideCheckoutModal = () => {
        checkoutModalOverlay.classList.remove('active');
        checkoutModal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset tabs/steps
        switchModalStep('delivery');
    };

    const switchModalStep = (stepName) => {
        // Deactivate all steps/indicators
        tabIndicatorDelivery.classList.remove('active');
        tabIndicatorPayment.classList.remove('active');
        tabIndicatorConfirm.classList.remove('active');
        
        modalStepDelivery.classList.remove('active');
        modalStepPayment.classList.remove('active');
        modalStepConfirm.classList.remove('active');

        if (stepName === 'delivery') {
            tabIndicatorDelivery.classList.add('active');
            modalStepDelivery.classList.add('active');
        } else if (stepName === 'payment') {
            tabIndicatorDelivery.classList.add('active'); // keep delivery active too
            tabIndicatorPayment.classList.add('active');
            modalStepPayment.classList.add('active');
        } else if (stepName === 'confirm') {
            tabIndicatorDelivery.classList.add('active');
            tabIndicatorPayment.classList.add('active');
            tabIndicatorConfirm.classList.add('active');
            modalStepConfirm.classList.add('active');
            
            // Hide the close modal button so they complete the checkout
            closeCheckoutModal.style.display = 'none';
        }
    };

    if (btnProceedCheckout) {
        btnProceedCheckout.addEventListener('click', showCheckoutModal);
    }
    
    if (closeCheckoutModal) {
        closeCheckoutModal.addEventListener('click', hideCheckoutModal);
    }
    
    if (checkoutModalOverlay) {
        checkoutModalOverlay.addEventListener('click', (e) => {
            // only close if clicking directly on overlay, and not during confirmation step
            if (e.target === checkoutModalOverlay && !tabIndicatorConfirm.classList.contains('active')) {
                hideCheckoutModal();
            }
        });
    }

    // Boleta/Factura Toggle Logic
    const radioBoleta = document.querySelector('input[name="document-type"][value="boleta"]');
    const radioFactura = document.querySelector('input[name="document-type"][value="factura"]');
    const labelBoleta = document.getElementById('label-boleta');
    const labelFactura = document.getElementById('label-factura');
    const facturaDetails = document.getElementById('factura-details');
    const facturaRut = document.getElementById('factura-rut');
    const facturaRazon = document.getElementById('factura-razon');
    const facturaGiro = document.getElementById('factura-giro');
    const facturaSameAddress = document.getElementById('factura-same-address');
    const facturaAddressGroup = document.getElementById('factura-address-group');
    const facturaAddress = document.getElementById('factura-address');

    const updateDocumentTypeUI = () => {
        if (radioFactura && radioFactura.checked) {
            if (labelFactura) labelFactura.classList.add('active');
            if (labelBoleta) labelBoleta.classList.remove('active');
            if (facturaDetails) facturaDetails.style.display = 'block';
            if (facturaRut) facturaRut.required = true;
            if (facturaRazon) facturaRazon.required = true;
            if (facturaGiro) facturaGiro.required = true;
            updateFacturaAddressRequired();
        } else {
            if (labelBoleta) labelBoleta.classList.add('active');
            if (labelFactura) labelFactura.classList.remove('active');
            if (facturaDetails) facturaDetails.style.display = 'none';
            if (facturaRut) facturaRut.required = false;
            if (facturaRazon) facturaRazon.required = false;
            if (facturaGiro) facturaGiro.required = false;
            if (facturaAddress) facturaAddress.required = false;
        }
    };

    const updateFacturaAddressRequired = () => {
        if (facturaSameAddress && facturaSameAddress.checked) {
            if (facturaAddressGroup) facturaAddressGroup.style.display = 'none';
            if (facturaAddress) facturaAddress.required = false;
        } else {
            if (facturaAddressGroup) facturaAddressGroup.style.display = 'block';
            if (facturaAddress) facturaAddress.required = true;
        }
    };

    if (radioBoleta) radioBoleta.addEventListener('change', updateDocumentTypeUI);
    if (radioFactura) radioFactura.addEventListener('change', updateDocumentTypeUI);
    if (facturaSameAddress) facturaSameAddress.addEventListener('change', updateFacturaAddressRequired);

    // Blue Express UI and logic
    const selectRegion = document.getElementById('checkout-region');
    const selectCommune = document.getElementById('checkout-commune');
    const pickupDetails = document.getElementById('pickup-details');
    const selectPickupPoint = document.getElementById('checkout-pickup-point');
    const labelShipHome = document.getElementById('label-ship-home');
    const labelShipPickup = document.getElementById('label-ship-pickup');

    const populatePickupPoints = (commune) => {
        if (!selectPickupPoint) return;
        selectPickupPoint.innerHTML = '<option value="">Selecciona tu Punto Blue Express</option>';
        
        if (BLUE_EXPRESS_POINTS[commune]) {
            BLUE_EXPRESS_POINTS[commune].forEach(point => {
                const opt = document.createElement('option');
                opt.value = point.id;
                opt.textContent = point.name;
                selectPickupPoint.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.value = 'fallback';
            opt.textContent = 'Punto Blue Express - Centro de Distribución Comunal';
            selectPickupPoint.appendChild(opt);
        }
    };

    // Initialize pickup points
    populatePickupPoints('santiago');

    if (selectRegion) {
        selectRegion.addEventListener('change', (e) => {
            selectedRegion = e.target.value;
            calculateSummary();
        });
    }

    if (selectCommune) {
        selectCommune.addEventListener('change', (e) => {
            selectedCommune = e.target.value;
            populatePickupPoints(selectedCommune);
            calculateSummary();
        });
    }

    const updateShippingMethodUI = () => {
        const radioHomeChecked = document.querySelector('input[name="shipping-method"][value="home"]').checked;
        if (radioHomeChecked) {
            selectedShippingMethod = 'home';
            if (labelShipHome) labelShipHome.classList.add('active');
            if (labelShipPickup) labelShipPickup.classList.remove('active');
            if (pickupDetails) pickupDetails.style.display = 'none';
            if (selectPickupPoint) selectPickupPoint.required = false;
        } else {
            selectedShippingMethod = 'pickup';
            if (labelShipPickup) labelShipPickup.classList.add('active');
            if (labelShipHome) labelShipHome.classList.remove('active');
            if (pickupDetails) pickupDetails.style.display = 'block';
            if (selectPickupPoint) selectPickupPoint.required = true;
        }
        calculateSummary();
    };

    const shipHomeRadio = document.querySelector('input[name="shipping-method"][value="home"]');
    const shipPickupRadio = document.querySelector('input[name="shipping-method"][value="pickup"]');
    if (shipHomeRadio) shipHomeRadio.addEventListener('change', updateShippingMethodUI);
    if (shipPickupRadio) shipPickupRadio.addEventListener('change', updateShippingMethodUI);

    // Also support clicking labels directly
    if (labelShipHome) {
        labelShipHome.addEventListener('click', () => {
            const input = labelShipHome.querySelector('input');
            if (input) {
                input.checked = true;
                updateShippingMethodUI();
            }
        });
    }
    if (labelShipPickup) {
        labelShipPickup.addEventListener('click', () => {
            const input = labelShipPickup.querySelector('input');
            if (input) {
                input.checked = true;
                updateShippingMethodUI();
            }
        });
    }

    // Step 1: Submit delivery details
    if (deliveryForm) {
        deliveryForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Validation
            if (selectedShippingMethod === 'pickup' && selectPickupPoint && !selectPickupPoint.value) {
                alert('Por favor selecciona un Punto Blue Express para el retiro.');
                return;
            }
            
            // Fill card holder with name inputted
            const nameInput = document.getElementById('checkout-name').value;
            cardHolderInput.value = nameInput.toUpperCase();
            cardHolderDisp.innerText = nameInput.toUpperCase();
            
            switchModalStep('payment');
        });
    }

    // Step 2: Submit payment / Card input sync
    if (cardNumInput) {
        cardNumInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            for (let i = 0; i < val.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += val[i];
            }
            e.target.value = formatted.slice(0, 19);
            cardNumDisp.innerText = e.target.value || '•••• •••• •••• ••••';
        });
    }

    if (cardHolderInput) {
        cardHolderInput.addEventListener('input', (e) => {
            cardHolderDisp.innerText = e.target.value.toUpperCase() || 'NOMBRE COMPLETO';
        });
    }

    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val.length >= 2) {
                e.target.value = val.slice(0, 2) + '/' + val.slice(2, 4);
            } else {
                e.target.value = val;
            }
            cardExpDisp.innerText = e.target.value || 'MM/AA';
        });
    }

    if (paymentForm) {
        paymentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const payBtn = document.getElementById('btn-pay-now');
            payBtn.disabled = true;
            payBtn.innerText = 'Procesando pago...';

            // Simulate server payment transaction latency
            setTimeout(() => {
                // Generate a random order number
                const orderNum = 'CV-' + Math.floor(100000 + Math.random() * 900000);
                const successOrderNumber = document.getElementById('success-order-number');
                if (successOrderNumber) {
                    successOrderNumber.innerText = orderNum;
                }

                // Generate Blue Express tracking code
                trackingCodeGenerated = 'BX-' + Math.floor(10000000 + Math.random() * 90000000) + '-CL';
                const successTrackingCode = document.getElementById('success-tracking-code');
                if (successTrackingCode) {
                    successTrackingCode.innerText = trackingCodeGenerated;
                }

                switchModalStep('confirm');
                
                // Clear cart state
                cart = [];
                localStorage.setItem('cart', JSON.stringify(cart));
            }, 2000);
        });
    }

    if (btnFinishAll) {
        btnFinishAll.addEventListener('click', () => {
            hideCheckoutModal();
            window.location.href = 'index.html';
        });
    }

    // Autofill phone prefix +56 and prevent deleting it
    const checkoutPhone = document.getElementById('checkout-phone');
    if (checkoutPhone) {
        if (!checkoutPhone.value) {
            checkoutPhone.value = '+56 ';
        }

        checkoutPhone.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && checkoutPhone.value.length <= 4) {
                e.preventDefault();
            }
            if (e.key === 'Delete' && checkoutPhone.selectionStart < 4) {
                e.preventDefault();
            }
        });

        checkoutPhone.addEventListener('input', (e) => {
            if (!checkoutPhone.value.startsWith('+56 ')) {
                checkoutPhone.value = '+56 ' + checkoutPhone.value.replace(/^\+?5?6?\s?/, '');
            }
        });
    }

    // Tracking Modal Controls & Simulation
    const trackingModal = document.getElementById('tracking-modal');
    const trackingModalOverlay = document.getElementById('tracking-modal-overlay');
    const closeTrackingModal = document.getElementById('close-tracking-modal');
    const trackModalCode = document.getElementById('track-modal-code');
    const trackModalStatusSummary = document.getElementById('track-modal-status-summary');

    let trackingTimer = null;

    const openTrackingModal = () => {
        if (!trackingModal || !trackingModalOverlay) return;
        trackingModal.classList.add('active');
        trackingModalOverlay.classList.add('active');
        
        if (trackModalCode) {
            trackModalCode.innerText = trackingCodeGenerated || 'BX-49201948-CL';
        }
        
        // Reset timeline UI
        const items = document.querySelectorAll('.tracking-timeline .timeline-item');
        items.forEach((item, index) => {
            item.className = 'timeline-item';
            const timeSpan = item.querySelector('.timeline-time');
            if (index === 0) {
                item.classList.add('active');
                if (timeSpan) timeSpan.innerText = 'Hace unos instantes';
            } else {
                if (timeSpan) timeSpan.innerText = 'Pendiente';
            }
        });
        
        if (trackModalStatusSummary) {
            trackModalStatusSummary.innerHTML = 'Estado: <strong>Pedido Recibido (Bodega Celzimo)</strong>';
        }

        // Start tracking step simulation
        if (trackingTimer) clearInterval(trackingTimer);
        let currentStep = 0;
        
        trackingTimer = setInterval(() => {
            currentStep++;
            if (currentStep > 3) {
                clearInterval(trackingTimer);
                return;
            }
            
            const timelineItems = document.querySelectorAll('.tracking-timeline .timeline-item');
            
            if (timelineItems[currentStep - 1]) {
                timelineItems[currentStep - 1].classList.remove('active');
                timelineItems[currentStep - 1].classList.add('completed');
                const prevTime = timelineItems[currentStep - 1].querySelector('.timeline-time');
                if (prevTime) prevTime.innerText = 'Completado';
            }
            
            if (timelineItems[currentStep]) {
                timelineItems[currentStep].classList.add('active');
                const currTime = timelineItems[currentStep].querySelector('.timeline-time');
                if (currTime) currTime.innerText = 'En curso';
                
                if (trackModalStatusSummary) {
                    if (currentStep === 1) {
                        trackModalStatusSummary.innerHTML = 'Estado: <strong>Recibido por Blue Express (En Tránsito a Hub)</strong>';
                    } else if (currentStep === 2) {
                        trackModalStatusSummary.innerHTML = 'Estado: <strong>En Ruta de Entrega</strong>';
                    } else if (currentStep === 3) {
                        trackModalStatusSummary.innerHTML = 'Estado: <strong>Entregado</strong>';
                        if (currTime) currTime.innerText = 'Entregado hace un momento';
                    }
                }
            }
        }, 4000);
    };

    const hideTrackingModal = () => {
        if (!trackingModal || !trackingModalOverlay) return;
        trackingModal.classList.remove('active');
        trackingModalOverlay.classList.remove('active');
        if (trackingTimer) clearInterval(trackingTimer);
    };

    document.addEventListener('click', (e) => {
        if (e.target && (e.target.id === 'btn-track-shipping' || e.target.closest('#btn-track-shipping'))) {
            openTrackingModal();
        }
    });

    if (closeTrackingModal) closeTrackingModal.addEventListener('click', hideTrackingModal);
    if (trackingModalOverlay) {
        trackingModalOverlay.addEventListener('click', (e) => {
            if (e.target === trackingModalOverlay) {
                hideTrackingModal();
            }
        });
    }

    // Render initially
    renderBasket();
});

