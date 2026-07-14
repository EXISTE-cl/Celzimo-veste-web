const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function downloadErrorLog() {
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

        const localLog = path.join(__dirname, "server_error_log.txt");
        const remoteLog = "/error_log";
        
        console.log("Descargando error_log desde el servidor...");
        await client.downloadTo(localLog, remoteLog);
        console.log("Descargado.");
        
        if (fs.existsSync(localLog)) {
            const content = fs.readFileSync(localLog, "utf8");
            const lines = content.split("\n");
            console.log("Últimas 150 líneas del error_log del servidor:");
            console.log("-------------------------------------------");
            lines.slice(-150).forEach(line => {
                if (line.includes("Fatal") || line.includes("import_products") || line.includes("Error") || line.includes("error")) {
                    console.log(line);
                } else if (lines.indexOf(line) >= lines.length - 10) {
                    // Mostrar siempre las últimas 10 líneas
                    console.log(line);
                }
            });
            console.log("-------------------------------------------");
            // Borrar local
            fs.unlinkSync(localLog);
        } else {
            console.log("No se pudo leer el log local.");
        }
    } catch (err) {
        console.error("Error al descargar error_log:", err.message);
    }
    client.close();
}

downloadErrorLog();
