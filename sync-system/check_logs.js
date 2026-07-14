const ftp = require('basic-ftp');
const fs = require('fs');

async function checkLogs() {
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
        let rootDir = "/";
        if (list.some(i => i.name === 'public_html')) {
            rootDir = "/public_html";
        } else if (list.some(i => i.name === 'htdocs')) {
            rootDir = "/htdocs";
        }
        await client.cd(rootDir);
        
        let rootList = await client.list();
        for (let file of rootList) {
            if (file.name.includes('error_log') || file.name.includes('debug.log')) {
                console.log("Found log: " + file.name);
                await client.downloadTo("C:\\Users\\Cristobal\\.gemini\\antigravity\\scratch\\celzimo-veste\\sync-system\\" + file.name, file.name);
            }
        }
        
        await client.cd("wp-content");
        let wpContentList = await client.list();
        for (let file of wpContentList) {
            if (file.name.includes('error_log') || file.name.includes('debug.log')) {
                console.log("Found log in wp-content: " + file.name);
                await client.downloadTo("C:\\Users\\Cristobal\\.gemini\\antigravity\\scratch\\celzimo-veste\\sync-system\\" + file.name, file.name);
            }
        }
        console.log("Done checking logs.");
    } catch (err) {
        console.error(err);
    } finally {
        client.close();
    }
}
checkLogs();
