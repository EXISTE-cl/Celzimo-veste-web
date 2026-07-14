const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const https = require("https");

const files = [
    {
        name: "carolamiconno-736p.jpg",
        url: "https://cdn.shopify.com/s/files/1/0884/6534/2763/files/carolamiconno-736p.jpg?v=1764774363"
    },
    {
        name: "carolamiconno-738p.jpg",
        url: "https://cdn.shopify.com/s/files/1/0884/6534/2763/files/carolamiconno-738p.jpg?v=1764774363"
    },
    {
        name: "carolamiconno-753p.jpg",
        url: "https://cdn.shopify.com/s/files/1/0884/6534/2763/files/carolamiconno-753p.jpg?v=1764774363"
    }
];

function downloadFile(url, dest) {
    return new Promise((resolve, reject) => {
        const file = fs.createWriteStream(dest);
        https.get(url, (response) => {
            response.pipe(file);
            file.on("finish", () => {
                file.close(resolve);
            });
        }).on("error", (err) => {
            fs.unlink(dest, () => {});
            reject(err);
        });
    });
}

function requestLocalIndex(index) {
    return new Promise((resolve, reject) => {
        console.log(`Ejecutando importación local para imagen index ${index}...`);
        const url = `https://celzimoveste.cl/import_local_emilia.php?token=Csc170431SideloadLocal&index=${index}`;
        
        https.get(url, { rejectUnauthorized: false, timeout: 60000 }, (res) => {
            let data = "";
            res.on("data", (chunk) => { data += chunk; });
            res.on("end", () => {
                console.log(`--- RESPUESTA INDEX ${index} ---`);
                console.log(data.trim());
                console.log("-------------------------------\n");
                resolve();
            });
        }).on("error", (err) => {
            console.error(`Error al procesar index ${index}:`, err.message);
            resolve();
        });
    });
}

async function run() {
    // 1. Descargar las imágenes localmente
    console.log("1. Descargando imágenes desde Shopify CDN...");
    for (const f of files) {
        const destPath = path.join(__dirname, f.name);
        console.log(`Descargando ${f.name}...`);
        await downloadFile(f.url, destPath);
    }
    console.log("✓ Descarga completada.\n");

    // 2. Subir las imágenes y el PHP al servidor mediante FTP
    const client = new ftp.Client();
    try {
        console.log("2. Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        console.log("Conectado.\n");

        // Subir imágenes
        for (const f of files) {
            const localImg = path.join(__dirname, f.name);
            console.log(`Subiendo ${f.name}...`);
            await client.uploadFrom(localImg, `/${f.name}`);
        }

        // Subir script PHP
        const localPhp = "C:/Users/Cristobal/.gemini/antigravity/brain/e5275044-f5b0-4ed8-be49-5fba21a21b80/scratch/import_local_emilia.php";
        const remotePhp = "/import_local_emilia.php";
        console.log("Subiendo import_local_emilia.php...");
        await client.uploadFrom(localPhp, remotePhp);
        client.close();
        console.log("✓ Archivos subidos exitosamente.\n");

        // 3. Ejecutar secuencialmente para index 0, 1, 2
        console.log("3. Iniciando importaciones secuenciales en el servidor...");
        await requestLocalIndex(0);
        await requestLocalIndex(1);
        await requestLocalIndex(2);

        // 4. Limpieza del servidor remoto y local
        console.log("4. Iniciando limpieza...");
        
        // Limpieza remota
        const cleanClient = new ftp.Client();
        await cleanClient.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        for (const f of files) {
            console.log(`Eliminando ${f.name} remoto...`);
            await cleanClient.remove(`/${f.name}`);
        }
        console.log("Eliminando script PHP remoto...");
        await cleanClient.remove(remotePhp);
        cleanClient.close();
        console.log("✓ Archivos remotos eliminados.");

        // Limpieza local
        for (const f of files) {
            const localImg = path.join(__dirname, f.name);
            if (fs.existsSync(localImg)) {
                fs.unlinkSync(localImg);
            }
        }
        console.log("✓ Archivos locales temporales eliminados.");
        console.log("PROCESO COMPLETADO EXITOSAMENTE.");

    } catch (err) {
        console.error("Error:", err);
        client.close();
    }
}

run();
