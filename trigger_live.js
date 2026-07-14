const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const https = require("https");

async function triggerLive() {
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

        // Subir disable_coming_soon.php
        const localFile = path.join(__dirname, "disable_coming_soon.php");
        const remoteFile = "/disable_coming_soon.php";
        
        console.log("2. Subiendo disable_coming_soon.php al servidor...");
        await client.uploadFrom(localFile, remoteFile);
        console.log("Subido.");
        client.close();

        // 3. Ejecutar la desactivación vía HTTPS
        console.log("3. Gatillando puesta en vivo del sitio...");
        const url = "https://celzimoveste.cl/disable_coming_soon.php?token=Csc170431Live";
        
        const requestOptions = {
            rejectUnauthorized: false
        };

        https.get(url, requestOptions, (res) => {
            let data = "";
            res.on("data", (chunk) => {
                data += chunk;
            });
            res.on("end", async () => {
                console.log("--- RESPUESTA PUESTA EN VIVO ---");
                console.log(data.trim());
                console.log("---------------------------------");

                // 4. Limpieza del archivo por seguridad
                console.log("4. Conectando al FTP para la limpieza...");
                const cleanClient = new ftp.Client();
                try {
                    await cleanClient.access({
                        host: "ftp.todoexiste.com",
                        user: "CELZIMO@celzimoveste.cl",
                        password: "Csc170431*",
                        secure: true,
                        secureOptions: { rejectUnauthorized: false }
                    });
                    
                    console.log("Eliminando disable_coming_soon.php remoto...");
                    await cleanClient.remove(remoteFile);
                    console.log("✓ Archivo de puesta en vivo eliminado del servidor remoto.");
                } catch (cleanErr) {
                    console.error("Error al limpiar:", cleanErr.message);
                }
                cleanClient.close();
            });
        }).on("error", (err) => {
            console.error("Error al gatillar el script PHP:", err.message);
        });

    } catch (err) {
        console.error("Error:", err);
        client.close();
    }
}

triggerLive();
