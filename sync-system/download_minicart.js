const ftp = require('basic-ftp');
const fs = require('fs');
const path = require('path');

async function downloadMiniCart() {
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
        let pluginDir = "/wp-content/plugins/woocommerce/templates/cart";
        if (!list.some(i => i.name === 'wp-content')) {
             if (list.some(i => i.name === 'public_html')) {
                 pluginDir = "/public_html/wp-content/plugins/woocommerce/templates/cart";
             } else if (list.some(i => i.name === 'htdocs')) {
                 pluginDir = "/htdocs/wp-content/plugins/woocommerce/templates/cart";
             }
        }
        
        await client.cd(pluginDir);
        
        const localDir = path.resolve(__dirname, "../celzimo-theme/woocommerce/cart");
        if (!fs.existsSync(localDir)) {
            fs.mkdirSync(localDir, { recursive: true });
        }
        const localFile = path.resolve(localDir, "mini-cart.php");
        
        await client.downloadTo(localFile, "mini-cart.php");
        console.log("Download successful!");
        
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}

downloadMiniCart();
