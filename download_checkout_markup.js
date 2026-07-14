const ftp = require("basic-ftp");
const fs = require("fs");

async function downloadMarkup() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Descargando /checkout_markup.html...");
        await client.downloadTo("checkout_markup.html", "/checkout_markup.html");
        console.log("Descarga completada.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

downloadMarkup();
