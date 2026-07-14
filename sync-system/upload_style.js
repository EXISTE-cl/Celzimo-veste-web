const ftp = require('basic-ftp');
const fs = require('fs');
const path = require('path');

async function uploadSingleProduct() {
    const client = new ftp.Client();
    client.ftp.verbose = true;

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
        
        console.log("Navigating to " + themeDir);
        await client.cd(themeDir);
        
        const localFile = path.resolve(__dirname, "../celzimo-theme/css/style.css");
        console.log("Uploading " + localFile);
        
        await client.uploadFrom(localFile, "css/style.css");
        console.log("Upload successful!");
        
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}

uploadSingleProduct();
