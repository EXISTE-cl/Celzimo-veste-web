const ftp = require('basic-ftp');
const fs = require('fs');
const path = require('path');

async function uploadFiles() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        let list = await client.list();
        let themeDir = "/wp-content/themes/celzimo-theme";
        if (!list.some(i => i.name === 'wp-content')) {
             if (list.some(i => i.name === 'public_html')) {
                 themeDir = "/public_html/wp-content/themes/celzimo-theme";
             } else if (list.some(i => i.name === 'htdocs')) {
                 themeDir = "/htdocs/wp-content/themes/celzimo-theme";
             }
        }
        
        await client.cd(themeDir);
        
        // Upload functions.php
        await client.uploadFrom(path.resolve(__dirname, "../celzimo-theme/functions.php"), "functions.php");
        console.log("functions.php uploaded!");
        
        // Upload style.css
        await client.uploadFrom(path.resolve(__dirname, "../celzimo-theme/css/style.css"), "css/style.css");
        console.log("style.css uploaded!");

        // Upload mini-cart.php
        await client.ensureDir("woocommerce/cart");
        await client.uploadFrom(path.resolve(__dirname, "../celzimo-theme/woocommerce/cart/mini-cart.php"), "mini-cart.php");
        console.log("mini-cart.php uploaded!");
        
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}

uploadFiles();
