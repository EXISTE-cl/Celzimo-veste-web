const ftp = require("basic-ftp");
const https = require("https");
const fs = require("fs");

async function runCheck() {
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
        
        console.log("Subiendo check_wp_data.php...");
        await client.uploadFrom("check_wp_data.php", "/check_wp_data.php");
        console.log("Subido correctamente.");
        
        // Esperar 1 segundo
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        console.log("Consultando https://celzimoveste.cl/check_wp_data.php...");
        const requestOptions = {
            rejectUnauthorized: false
        };
        
        https.get("https://celzimoveste.cl/check_wp_data.php", requestOptions, (res) => {
            let data = "";
            res.on("data", chunk => data += chunk);
            res.on("end", async () => {
                console.log("--- RESULTADO DEL SERVIDOR ---");
                console.log(data);
                console.log("------------------------------");
                
                // Borrar archivo del servidor
                console.log("Borrando check_wp_data.php del servidor...");
                const cleanClient = new ftp.Client();
                try {
                    await cleanClient.access({
                        host: "ftp.todoexiste.com",
                        user: "CELZIMO@celzimoveste.cl",
                        password: "Csc170431*",
                        secure: true,
                        secureOptions: { rejectUnauthorized: false }
                    });
                    await cleanClient.remove("/check_wp_data.php");
                    console.log("Archivo borrado con éxito.");
                } catch (e) {
                    console.error("Error al borrar el archivo:", e.message);
                }
                cleanClient.close();
                client.close();
            });
        }).on("error", async (err) => {
            console.error("Error de petición HTTP:", err.message);
            // Borrar de todas formas
            console.log("Borrando check_wp_data.php del servidor...");
            const cleanClient = new ftp.Client();
            try {
                await cleanClient.access({
                    host: "ftp.todoexiste.com",
                    user: "CELZIMO@celzimoveste.cl",
                    password: "Csc170431*",
                    secure: true,
                    secureOptions: { rejectUnauthorized: false }
                });
                await cleanClient.remove("/check_wp_data.php");
                console.log("Archivo borrado con éxito.");
            } catch (e) {
                console.error("Error al borrar el archivo:", e.message);
            }
            cleanClient.close();
            client.close();
        });
        
    } catch (err) {
        console.error("Error general:", err);
        client.close();
    }
}

runCheck();
