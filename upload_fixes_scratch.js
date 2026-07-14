const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function uploadFixesScratch() {
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
        
        // 1. Upload corrected root index.html
        const rootIndexPath = path.join(__dirname, "index_root_ftp.html");
        console.log("Uploading index_root_ftp.html as /index.html...");
        await client.uploadFrom(rootIndexPath, "/index.html");
        console.log("Uploaded /index.html successfully.");

        // 2. Upload scratch HTML files to /_archivo-html-2026-07-03/
        const targetFtpDir = "/_archivo-html-2026-07-03";
        
        const htmlFiles = [
            "account.html",
            "basket.html",
            "devoluciones-y-garantias.html",
            "index.html",
            "politica-envios.html",
            "politica-privacidad.html",
            "product.html",
            "register.html",
            "shop.html",
            "terminos-y-condiciones.html",
            "welcome-email.html"
        ];
        
        for (const file of htmlFiles) {
            const localPath = path.join(__dirname, file);
            const ftpPath = `${targetFtpDir}/${file}`;
            console.log(`Uploading ${localPath} as ${ftpPath}...`);
            if (fs.existsSync(localPath)) {
                await client.uploadFrom(localPath, ftpPath);
                console.log(`Uploaded ${file} successfully.`);
            } else {
                console.error(`Local file ${localPath} does not exist!`);
            }
        }
        
        console.log("¡Todos los archivos estructurados del scratch han sido subidos con éxito!");
    } catch (err) {
        console.error("Error durante la subida:", err);
    }
    client.close();
}

uploadFixesScratch();
