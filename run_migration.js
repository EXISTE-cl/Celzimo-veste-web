const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const https = require("https");

async function runMigration() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("1. Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Conectado exitosamente.");

        // Subir import_products.php modificado
        const localImporter = path.join(__dirname, "import_products.php");
        const remoteImporter = "/import_products.php";
        
        console.log("2. Subiendo import_products.php actualizado...");
        await client.uploadFrom(localImporter, remoteImporter);
        console.log("import_products.php subido al servidor remoto.");
        client.close();

        // 3. Ejecutar la petición HTTP segura con bypass de verificación SSL por si el dominio está en propagación
        console.log("3. Ejecutando script de migración en el servidor...");
        
        const url = "https://celzimoveste.cl/import_products.php?token=Csc170431Migration";
        
        const requestOptions = {
            rejectUnauthorized: false // Evita fallos por SSL incompleto o en propagación
        };

        https.get(url, requestOptions, (res) => {
            let data = "";
            res.on("data", (chunk) => {
                data += chunk;
            });
            res.on("end", async () => {
                console.log("--- RESPUESTA DEL SERVIDOR ---");
                console.log(data);
                console.log("------------------------------");

                // 4. Limpieza del archivo import_products.php por seguridad
                console.log("4. Conectando al FTP para realizar la limpieza de seguridad...");
                const cleanClient = new ftp.Client();
                try {
                    await cleanClient.access({
                        host: "ftp.todoexiste.com",
                        user: "CELZIMO@celzimoveste.cl",
                        password: "Csc170431*",
                        secure: true,
                        secureOptions: { rejectUnauthorized: false }
                    });
                    
                    console.log("Eliminando import_products.php del servidor...");
                    await cleanClient.remove(remoteImporter);
                    console.log("✓ Archivo de migración eliminado del servidor de producción exitosamente.");
                } catch (cleanErr) {
                    console.error("Error durante la limpieza de seguridad:", cleanErr.message);
                }
                cleanClient.close();
            });
        }).on("error", (err) => {
            console.error("Error al gatillar el script PHP:", err.message);
        });

    } catch (err) {
        console.error("Error general:", err);
        client.close();
    }
}

runMigration();
