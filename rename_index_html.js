const ftp = require("basic-ftp");

async function renameIndexHtml() {
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

        console.log("Renombrando index.html de la raíz a index-old.html...");
        await client.rename("/index.html", "/index-old.html");
        console.log("✓ index.html de la raíz ha sido renombrado a index-old.html de forma segura.");

    } catch (err) {
        console.error("Error al renombrar index.html:", err.message);
    }
    client.close();
}

renameIndexHtml();
