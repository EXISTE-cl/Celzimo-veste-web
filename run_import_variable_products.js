const ftp = require("basic-ftp");
const https = require("https");

async function runImport() {
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
        
        console.log("Subiendo import_variable_products.php...");
        await client.uploadFrom("import_variable_products.php", "/import_variable_products.php");
        console.log("Subido correctamente.");
        
        // Esperar 1 segundo
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        console.log("Consultando https://celzimoveste.cl/import_variable_products.php...");
        const requestOptions = {
            rejectUnauthorized: false
        };
        
        https.get("https://celzimoveste.cl/import_variable_products.php?token=Csc170431Variable", requestOptions, (res) => {
            let data = "";
            res.on("data", chunk => data += chunk);
            res.on("end", async () => {
                console.log("--- RESPUESTA DEL SERVIDOR ---");
                console.log(data);
                console.log("------------------------------");
                
                // Borrar archivo del servidor
                console.log("Borrando import_variable_products.php del servidor...");
                const cleanClient = new ftp.Client();
                try {
                    await cleanClient.access({
                        host: "ftp.todoexiste.com",
                        user: "CELZIMO@celzimoveste.cl",
                        password: "Csc170431*",
                        secure: true,
                        secureOptions: { rejectUnauthorized: false }
                    });
                    await cleanClient.remove("/import_variable_products.php");
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
            console.log("Borrando import_variable_products.php del servidor...");
            const cleanClient = new ftp.Client();
            try {
                await cleanClient.access({
                    host: "ftp.todoexiste.com",
                    user: "CELZIMO@celzimoveste.cl",
                    password: "Csc170431*",
                    secure: true,
                    secureOptions: { rejectUnauthorized: false }
                });
                await cleanClient.remove("/import_variable_products.php");
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

runImport();
