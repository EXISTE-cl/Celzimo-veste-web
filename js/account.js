document.addEventListener('DOMContentLoaded', () => {
    // 1. Tab Switching Logic
    const navItems = document.querySelectorAll('.account-nav-item');
    const tabs = document.querySelectorAll('.account-tab');
    const mobileNav = document.getElementById('account-mobile-nav');

    function switchTab(targetId) {
        // Update active class on tabs
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        const targetTab = document.getElementById('tab-' + targetId);
        if(targetTab) targetTab.classList.add('active');

        // Update active class on desktop nav
        navItems.forEach(nav => {
            nav.classList.remove('active');
            if (nav.dataset.target === targetId) {
                nav.classList.add('active');
            }
        });

        // Update mobile select
        if (mobileNav) {
            mobileNav.value = targetId;
        }

        // Close mobile menu if open (optional)
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const target = item.dataset.target;
            switchTab(target);
        });
    });

    if (mobileNav) {
        mobileNav.addEventListener('change', (e) => {
            const target = e.target.value;
            if (target === 'logout') {
                handleLogout();
            } else {
                switchTab(target);
            }
        });
    }

    // Expose switchTab to window for inline onclick handlers (Dashboard buttons)
    window.switchTab = switchTab;

    // 2. Profile Form Simulation
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', (e) => {
            e.preventDefault();
            showToast('Perfil actualizado correctamente');
        });
    }

    // 3. Recommended & Wishlist Products Injection
    // Reuse data from data.js
    const recommendedContainer = document.getElementById('recommended-products');
    const wishlistContainer = document.getElementById('wishlist-container');
    
    if (typeof products !== 'undefined') {
        // Pick 4 random products for recommended
        const shuffled = [...products].sort(() => 0.5 - Math.random());
        const recommended = shuffled.slice(0, 4);
        
        // Pick 3 specific products for wishlist
        const wishlist = products.slice(0, 3);

        const renderMiniProduct = (product) => {
            return `
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-price">$${product.price.toLocaleString('es-CL')}</p>
                        <button class="btn btn-outline btn-block mt-2" onclick="showToast('Agregado al carrito')">Agregar</button>
                    </div>
                </div>
            `;
        };

        if (recommendedContainer) {
            recommendedContainer.innerHTML = recommended.map(renderMiniProduct).join('');
        }
        if (wishlistContainer) {
            wishlistContainer.innerHTML = wishlist.map(renderMiniProduct).join('');
        }
    }

    // 4. Delete Card Simulation
    window.deleteCard = function(btn) {
        if(confirm('¿Estás seguro de que deseas eliminar este método de pago?')) {
            const card = btn.closest('.payment-card-visual');
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                showToast('Método de pago eliminado');
            }, 300);
        }
    };

    // 5. Logout Simulation
    const logoutBtn = document.getElementById('logout-btn');
    const handleLogout = () => {
        localStorage.removeItem('isLoggedIn');
        window.location.href = 'index.html';
    };
    if (logoutBtn) logoutBtn.addEventListener('click', handleLogout);

    // 6. Live Chat Widget
    const chatWidget = document.getElementById('live-chat-widget');
    const chatInput = document.getElementById('chat-input');
    const chatBody = document.getElementById('chat-body');

    window.openLiveChat = function() {
        if(chatWidget) chatWidget.classList.add('active');
        if(chatInput) chatInput.focus();
    };

    window.closeLiveChat = function() {
        if(chatWidget) chatWidget.classList.remove('active');
    };

    window.sendChatMsg = function() {
        const text = chatInput.value.trim();
        if(!text) return;
        
        // Add User Message
        const userMsg = document.createElement('div');
        userMsg.className = 'chat-msg user-msg';
        userMsg.textContent = text;
        chatBody.appendChild(userMsg);
        chatInput.value = '';
        
        chatBody.scrollTop = chatBody.scrollHeight;

        // Simulate Agent Typing
        setTimeout(() => {
            const agentMsg = document.createElement('div');
            agentMsg.className = 'chat-msg agent-msg';
            agentMsg.innerHTML = '<span style="color:#94a3b8">Escribiendo...</span>';
            chatBody.appendChild(agentMsg);
            chatBody.scrollTop = chatBody.scrollHeight;

            // Agent Reply
            setTimeout(() => {
                agentMsg.innerHTML = 'Gracias por tu mensaje. Un asesor revisará tu caso y te responderá en breve.';
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 1500);
        }, 500);
    };

    window.handleChatEnter = function(e) {
        if (e.key === 'Enter') {
            sendChatMsg();
        }
    };

    // 7. Returns
    window.startReturn = function(orderId) {
        const reason = prompt(`Iniciar devolución para pedido #${orderId}.\nPor favor, ingresa el motivo:`);
        if(reason) {
            showToast('Solicitud de devolución enviada');
        }
    };

    // 8. Invoice Generation (Print window simulation)
    window.generateInvoice = function(orderId) {
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Factura Pedido #${orderId}</title>
                <style>
                    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 40px; color: #333; }
                    .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0b1c3a; padding-bottom: 20px; margin-bottom: 30px; }
                    .logo { font-size: 24px; font-weight: bold; color: #0b1c3a; letter-spacing: 2px; }
                    .invoice-details { text-align: right; }
                    .billing-to { margin-bottom: 30px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                    th { border-bottom: 2px solid #ddd; padding: 10px; text-align: left; }
                    td { border-bottom: 1px solid #ddd; padding: 10px; }
                    .total-row { font-weight: bold; font-size: 18px; }
                    .total-row td { border-top: 2px solid #0b1c3a; border-bottom: none; }
                    .print-btn { background: #c49a45; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 4px; margin-bottom: 20px; }
                    @media print { .no-print { display: none; } }
                </style>
            </head>
            <body>
                <button class="no-print print-btn" onclick="window.print()">Imprimir Factura</button>
                <div class="header">
                    <div class="logo">CELZIMO VESTE</div>
                    <div class="invoice-details">
                        <h2>FACTURA</h2>
                        <p>Pedido: <strong>#${orderId}</strong></p>
                        <p>Fecha: ${new Date().toLocaleDateString('es-CL')}</p>
                    </div>
                </div>
                
                <div class="billing-to">
                    <h3>Facturar a:</h3>
                    <p>Cristobal Pizarro</p>
                    <p>Av. Siempre Viva 742, Springfield</p>
                    <p>cristobalmarolla@gmail.com</p>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Cant.</th>
                            <th>Precio Unit.</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Artículo de Lujo CELZIMO VESTE</td>
                            <td>1</td>
                            <td>$150.000</td>
                            <td>$150.000</td>
                        </tr>
                        <tr>
                            <td>Envío Premium (Gratis)</td>
                            <td>1</td>
                            <td>$0</td>
                            <td>$0</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">TOTAL:</td>
                            <td>$150.000 CLP</td>
                        </tr>
                    </tbody>
                </table>
                
                <p style="text-align: center; color: #777; margin-top: 50px; font-size: 12px;">
                    CELZIMO VESTE S.A. | RUT: 76.123.456-7 | contacto@celzimoveste.com
                </p>
            </body>
            </html>
        `;
        
        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();
    };

    // Reuse showToast from script.js, ensure it exists or define a fallback
    window.showToast = window.showToast || function(msg) {
        alert(msg);
    };
});

