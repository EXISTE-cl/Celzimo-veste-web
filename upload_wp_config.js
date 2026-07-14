const ftp = require("basic-ftp");
const path = require("path");

async function uploadWpConfig() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Subiendo wp-config-remote.php como /wp-config.php...");
        const localFile = path.join(__dirname, "wp-config-remote.php");
        await client.uploadFrom(localFile, "/wp-config.php");
        console.log("✓ wp-config.php subido correctamente.");
    } catch (err) {
        console.error("Error al subir wp-config.php:", err.message);
    }
    client.close();
}

uploadWpConfig();
