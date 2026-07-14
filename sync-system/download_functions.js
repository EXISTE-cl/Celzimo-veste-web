const ftp = require('basic-ftp');
const fs = require('fs');

async function downloadFunctions() {
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
        await client.downloadTo("C:\\Users\\Cristobal\\.gemini\\antigravity\\scratch\\celzimo-veste\\celzimo-theme\\functions.php.remote", "functions.php");
        console.log("Download successful!");
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}
downloadFunctions();
