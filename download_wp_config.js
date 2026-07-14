const ftp = require("basic-ftp");
const fs = require("fs");

async function downloadWpConfig() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Descargando /wp-config.php...");
        await client.downloadTo("wp-config-remote.php", "/wp-config.php");
        console.log("Descarga completada.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

downloadWpConfig();
