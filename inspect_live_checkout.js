const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

async function main() {
    try {
        console.log("Fetching home page to get cookies...");
        const response = await axios.get("https://celzimoveste.cl/");
        const cookies = response.headers['set-cookie'] || [];
        const cookieStr = cookies.map(c => c.split(';')[0]).join('; ');
        console.log("Cookies:", cookieStr);

        // Let's try adding product ID 1391 or similar to cart
        console.log("Adding product to cart...");
        const addResponse = await axios.post("https://celzimoveste.cl/?wc-ajax=add_to_cart", 
            "product_id=1391&quantity=1", 
            {
                headers: {
                    'Cookie': cookieStr,
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                }
            }
        );
        console.log("Add to cart status:", addResponse.status);

        console.log("Fetching checkout page...");
        const checkoutResponse = await axios.get("https://celzimoveste.cl/finalizar-compra/", {
            headers: {
                'Cookie': cookieStr,
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            }
        });
        console.log("Checkout page status:", checkoutResponse.status);

        fs.writeFileSync('checkout_dom.html', checkoutResponse.data, 'utf-8');
        console.log("Saved HTML to checkout_dom.html");

        const $ = cheerio.load(checkoutResponse.data);

        console.log("\n--- CHECKING CHECKOUT DOM STRUCTURE ---");
        const nativeCheckbox = $('#ship-to-different-address-checkbox');
        if (nativeCheckbox.length) {
            console.log("SUCCESS: Found #ship-to-different-address-checkbox");
            console.log("Checked:", nativeCheckbox.attr('checked'), "Type:", nativeCheckbox.attr('type'));
        } else {
            console.log("WARNING: #ship-to-different-address-checkbox NOT found by ID!");
            const cb = $('input[name="ship_to_different_address"]');
            if (cb.length) {
                console.log("Found ship_to_different_address checkbox by name:", cb.attr('id'), cb.attr('class'));
            } else {
                console.log("No ship_to_different_address checkbox found at all!");
            }
        }

        const shippingAddress = $('.shipping_address');
        if (shippingAddress.length) {
            console.log("SUCCESS: Found class .shipping_address wrapper");
            console.log("Class list:", shippingAddress.attr('class'), "Style:", shippingAddress.attr('style'));
        } else {
            console.log("WARNING: .shipping_address wrapper NOT found by class!");
            const shipAddr1 = $('#shipping_address_1');
            if (shipAddr1.length) {
                console.log("Found #shipping_address_1 input, so shipping fields exist in the DOM.");
            } else {
                console.log("No #shipping_address_1 input found in the DOM!");
            }
        }

        console.log("\n--- CUSTOM BILLING FIELDS ---");
        ['billing_solicita_factura', 'billing_factura_diff_address', 'billing_rut', 'billing_razon_social', 'billing_giro'].forEach(id => {
            const el = $('#' + id);
            if (el.length) {
                console.log(`Found ${id}: tag=${el[0].name} type=${el.attr('type')} class=${el.attr('class')}`);
            } else {
                console.log(`NOT found: ${id}`);
            }
        });

    } catch (err) {
        console.error("Error:", err.message);
    }
}

main();
