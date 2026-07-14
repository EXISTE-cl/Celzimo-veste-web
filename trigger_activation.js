const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const https = require("https");

async function triggerActivation() {
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

        // Subir activate_theme.php
        const localFile = path.join(__dirname, "activate_theme.php");
        const remoteFile = "/activate_theme.php";
        
        console.log("2. Subiendo activate_theme.php al servidor...");
        await client.uploadFrom(localFile, remoteFile);
        console.log("Subido.");
        client.close();

        // 3. Ejecutar la activación vía HTTPS
        console.log("3. Gatillando la activación del tema...");
        const url = "https://celzimoveste.cl/activate_theme.php?token=Csc170431Activation";
        
        const requestOptions = {
            rejectUnauthorized: false
        };

        https.get(url, requestOptions, (res) => {
            let data = "";
            res.on("data", (chunk) => {
                data += chunk;
            });
            res.on("end", async () => {
                console.log("--- RESPUESTA ACTIVACIÓN ---");
                console.log(data.trim());
                console.log("----------------------------");

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
                    
                    console.log("Eliminando activate_theme.php remoto...");
                    await cleanClient.remove(remoteFile);
                    console.log("✓ Archivo de activación eliminado del servidor remoto.");
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

triggerActivation();
