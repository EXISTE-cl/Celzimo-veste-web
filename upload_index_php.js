const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function uploadIndexPhp() {
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
        
        console.log("Conectado exitosamente.");

        const localFile = path.join(__dirname, "celzimo-theme", "index.php");
        const remoteFile = "/wp-content/themes/celzimo-theme/index.php";
        
        console.log(`Subiendo ${localFile} a ${remoteFile}...`);
        if (fs.existsSync(localFile)) {
            await client.uploadFrom(localFile, remoteFile);
            console.log("index.php subido exitosamente al servidor.");
        } else {
            console.error("index.php local no encontrado.");
        }
    } catch (err) {
        console.error("Error durante la subida:", err.message);
    }
    client.close();
}

uploadIndexPhp();
