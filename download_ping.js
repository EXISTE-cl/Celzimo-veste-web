const ftp = require("basic-ftp");
const path = require("path");

async function downloadFile() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        await client.downloadTo(path.join(__dirname, "ping-celzimo.php"), "/ping-celzimo.php");
        console.log("Descargado ping-celzimo.php con éxito.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

downloadFile();
