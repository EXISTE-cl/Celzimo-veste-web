const ftp = require('basic-ftp');
const fs = require('fs');
const path = require('path');

async function uploadHeader() {
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
        
        console.log("Connected to FTP. Navigating to wp-content/themes/celzimo-theme...");
        
        // Wait, what if the root of the FTP is the wordpress installation? Let's check.
        // I will just use cd to /wp-content/themes/celzimo-theme. If it fails, I'll log pwd.
        let pwd = await client.pwd();
        console.log("PWD is", pwd);
        
        // It could be in public_html or directly in root. Let's list root.
        let list = await client.list();
        let hasWpContent = list.some(i => i.name === 'wp-content');
        
        let themeDir = "/wp-content/themes/celzimo-theme";
        if (!hasWpContent) {
             if (list.some(i => i.name === 'public_html')) {
                 themeDir = "/public_html/wp-content/themes/celzimo-theme";
             } else if (list.some(i => i.name === 'htdocs')) {
                 themeDir = "/htdocs/wp-content/themes/celzimo-theme";
             }
        }
        
        console.log("Target theme dir:", themeDir);
        await client.cd(themeDir);
        
        const localFile = path.resolve(__dirname, "../celzimo-theme/header.php");
        console.log("Uploading " + localFile);
        
        await client.uploadFrom(localFile, "header.php");
        console.log("Upload successful!");
        
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}

uploadHeader();
