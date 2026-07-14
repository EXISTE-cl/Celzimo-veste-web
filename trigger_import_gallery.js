const ftp = require("basic-ftp");
const path = require("path");
const https = require("https");

function requestSkuIndex(sku, index) {
    return new Promise((resolve, reject) => {
        console.log(`Iniciando importación para SKU ${sku} [Imagen ${index}]...`);
        const url = `https://celzimoveste.cl/import_gallery_images.php?token=Csc170431Sideload&sku=${sku}&index=${index}`;
        
        https.get(url, { rejectUnauthorized: false, timeout: 60000 }, (res) => {
            let data = "";
            res.on("data", (chunk) => { data += chunk; });
            res.on("end", () => {
                console.log(`--- RESPUESTA SKU ${sku} [${index}] ---`);
                console.log(data.trim());
                console.log("------------------------------\n");
                resolve();
            });
        }).on("error", (err) => {
            console.error(`Error al procesar SKU ${sku} [${index}]:`, err.message);
            resolve(); // Continuar a pesar de fallas individuales
        });
    });
}

async function run() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        const localFile = "C:/Users/Cristobal/.gemini/antigravity/brain/e5275044-f5b0-4ed8-be49-5fba21a21b80/scratch/import_gallery_images.php";
        const remoteFile = "/import_gallery_images.php";
        
        console.log("Subiendo import_gallery_images.php...");
        await client.uploadFrom(localFile, remoteFile);
        client.close();
        console.log("Subido.\n");

        // Ejecutar de forma secuencial una por una para evitar timeouts/503
        const skus = ["JE57NE", "JE40RA", "JE12GR"];
        for (const sku of skus) {
            for (let i = 0; i < 4; i++) {
                await requestSkuIndex(sku, i);
            }
        }

        // Limpieza final
        console.log("Conectando para limpieza...");
        const cleanClient = new ftp.Client();
        await cleanClient.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        await cleanClient.remove(remoteFile);
        cleanClient.close();
        console.log("✓ Archivo temporal de importación eliminado del servidor.");
    } catch (err) {
        console.error("Error:", err);
        client.close();
    }
}

run();
