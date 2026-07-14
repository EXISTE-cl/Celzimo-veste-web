const ftp = require("basic-ftp");

async function downloadTodoExisteWpConfig() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP de todoexiste.com...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Descargando /wp-config.php...");
        await client.downloadTo("wp-config-todoexiste.php", "/wp-config.php");
        console.log("Descarga completada.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

downloadTodoExisteWpConfig();
