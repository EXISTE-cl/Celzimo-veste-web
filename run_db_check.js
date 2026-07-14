const ftp = require("basic-ftp");
const http = require("https");
const fs = require("fs");

async function runCheck() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP de celzimoveste.cl...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Subiendo db_check.php...");
        await client.uploadFrom("db_check.php", "/db_check.php");
        console.log("Subido correctamente.");
        
        // Esperar 1 segundo
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        console.log("Consultando https://celzimoveste.cl/db_check.php...");
        http.get("https://celzimoveste.cl/db_check.php", { rejectUnauthorized: false }, (res) => {
            let data = "";
            res.on("data", chunk => data += chunk);
            res.on("end", async () => {
                console.log("Resultado del servidor:");
                console.log(data);
                
                // Borrar archivo del servidor
                console.log("Borrando db_check.php del servidor...");
                try {
                    await client.remove("/db_check.php");
                    console.log("Archivo borrado con éxito.");
                } catch (e) {
                    console.error("Error al borrar el archivo:", e.message);
                }
                client.close();
            });
        }).on("error", async (err) => {
            console.error("Error de petición HTTP:", err.message);
            // Borrar de todas formas
            console.log("Borrando db_check.php del servidor...");
            try {
                await client.remove("/db_check.php");
                console.log("Archivo borrado con éxito.");
            } catch (e) {
                console.error("Error al borrar el archivo:", e.message);
            }
            client.close();
        });
        
    } catch (err) {
        console.error("Error general:", err);
        client.close();
    }
}

runCheck();
