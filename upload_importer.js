const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function uploadImporter() {
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

        const localImporter = path.join(__dirname, "import_products.php");
        const remoteImporter = "/import_products.php";
        
        console.log(`Subiendo ${localImporter} a la raíz del FTP como ${remoteImporter}...`);
        if (fs.existsSync(localImporter)) {
            await client.uploadFrom(localImporter, remoteImporter);
            console.log("import_products.php subido exitosamente a la raíz.");
        } else {
            console.error("import_products.php local no encontrado.");
        }
    } catch (err) {
        console.error("Error durante la subida del importador:", err);
    }
    client.close();
}

uploadImporter();
