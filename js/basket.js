// basket.js
document.addEventListener('DOMContentLoaded', () => {
    // State
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let appliedCoupon = null;
    let couponDiscountPercent = 0;
    
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
                    <div class="delivery-option-box ${isDelivery ? 'active' : ''}" data-id="${item.id}" data-type="delivery">
                        <input type="radio" name="delivery-choice-${item.id}" id="del-${item.id}" ${isDelivery ? 'checked' : ''}>
                        <label for="del-${item.id}">
                            <i class="ti ti-truck"></i>
                            <div>
                                <strong>Despacho a domicilio</strong>
                                <span class="delivery-status">Llega mañana</span>
                            </div>
                        </label>
                    </div>
                    <div class="delivery-option-box ${!isDelivery ? 'active' : ''}" data-id="${item.id}" data-type="pickup">
                        <input type="radio" name="delivery-choice-${item.id}" id="pick-${item.id}" ${!isDelivery ? 'checked' : ''}>
                        <label for="pick-${item.id}">
                            <i class="ti ti-building-store"></i>
                            <div>
                                <strong>Retiro en tienda</strong>
                                <span class="delivery-status">Disponible hoy - Gratis</span>
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

        // Calculate shipping costs
        // Let's say shipping is $4.990 per item marked as 'delivery' if not free shipping.
        let shippingTotal = 0;
        let hasDeliveries = false;
        
        cart.forEach(item => {
            if (deliveryChoices[item.id] === 'delivery') {
                hasDeliveries = true;
                if (!isShippingFree) {
                    shippingTotal += 4990;
                }
            }
        });

        if (hasDeliveries) {
            if (isShippingFree) {
                summaryShipping.innerText = 'Gratis';
                summaryShipping.className = 'free-badge';
            } else {
                summaryShipping.innerText = formatCurrency(shippingTotal);
                summaryShipping.classList.remove('free-badge');
            }
        } else {
            summaryShipping.innerText = 'Gratis';
            summaryShipping.className = 'free-badge';
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

    // Step 1: Submit delivery details
    if (deliveryForm) {
        deliveryForm.addEventListener('submit', (e) => {
            e.preventDefault();
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
        // Prefill default
        if (!checkoutPhone.value) {
            checkoutPhone.value = '+56 ';
        }

        checkoutPhone.addEventListener('keydown', (e) => {
            // Prevent backspace or delete from removing "+56 "
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

    // Render initially
    renderBasket();
});

