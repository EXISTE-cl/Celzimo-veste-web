const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function deployClean() {
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
        const baseFtpDir = "/_archivo-html-2026-07-03";
        
        // 1. Crear directorios base si no existen
        try {
            await client.ensureDir(baseFtpDir);
            console.log(`Directorio base asegurado: ${baseFtpDir}`);
        } catch (e) {
            console.log(`Asegurando directorio base: ${e.message}`);
        }

        // 2. Subir archivos HTML a la raíz del subdirectorio
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
            const ftpPath = `${baseFtpDir}/${file}`;
            console.log(`Subiendo HTML: ${localPath} -> ${ftpPath}`);
            if (fs.existsSync(localPath)) {
                await client.uploadFrom(localPath, ftpPath);
            } else {
                console.error(`Archivo no existe: ${localPath}`);
            }
        }

        // 3. Subir archivos JS a /_archivo-html-2026-07-03/js/
        const jsFtpDir = `${baseFtpDir}/js`;
        try {
            await client.ensureDir(jsFtpDir);
            console.log(`Directorio JS asegurado: ${jsFtpDir}`);
        } catch (e) {}

        const jsFiles = [
            "account.js",
            "basket.js",
            "data.js",
            "product.js",
            "register.js",
            "script.js"
        ];

        for (const file of jsFiles) {
            const localPath = path.join(__dirname, "js", file);
            const ftpPath = `${jsFtpDir}/${file}`;
            console.log(`Subiendo JS: ${localPath} -> ${ftpPath}`);
            if (fs.existsSync(localPath)) {
                await client.uploadFrom(localPath, ftpPath);
            } else {
                console.error(`Archivo no existe: ${localPath}`);
            }
        }

        // 4. Subir archivos CSS a /_archivo-html-2026-07-03/css/
        const cssFtpDir = `${baseFtpDir}/css`;
        try {
            await client.ensureDir(cssFtpDir);
            console.log(`Directorio CSS asegurado: ${cssFtpDir}`);
        } catch (e) {}

        const cssFiles = [
            "style.css"
        ];

        for (const file of cssFiles) {
            const localPath = path.join(__dirname, "css", file);
            const ftpPath = `${cssFtpDir}/${file}`;
            console.log(`Subiendo CSS: ${localPath} -> ${ftpPath}`);
            if (fs.existsSync(localPath)) {
                await client.uploadFrom(localPath, ftpPath);
            } else {
                console.error(`Archivo no existe: ${localPath}`);
            }
        }

        // 5. Subir imágenes/assets a /_archivo-html-2026-07-03/assets/
        const assetsFtpDir = `${baseFtpDir}/assets`;
        try {
            await client.ensureDir(assetsFtpDir);
            console.log(`Directorio Assets asegurado: ${assetsFtpDir}`);
        } catch (e) {}

        const localAssetsDir = path.join(__dirname, "assets");
        if (fs.existsSync(localAssetsDir)) {
            const assetsFiles = fs.readdirSync(localAssetsDir);
            for (const file of assetsFiles) {
                const localPath = path.join(localAssetsDir, file);
                const ftpPath = `${assetsFtpDir}/${file}`;
                // Solo subir si es un archivo
                if (fs.lstatSync(localPath).isFile()) {
                    console.log(`Subiendo Asset: ${localPath} -> ${ftpPath}`);
                    await client.uploadFrom(localPath, ftpPath);
                }
            }
        } else {
            console.error(`Directorio de assets local no existe: ${localAssetsDir}`);
        }

        console.log("¡Despliegue limpio completado con éxito!");
    } catch (err) {
        console.error("Error durante el despliegue limpio:", err);
    }
    client.close();
}

deployClean();
