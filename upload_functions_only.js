const ftp = require("basic-ftp");
const path = require("path");

async function uploadFunctionsOnly() {
    const client = new ftp.Client();
    client.ftp.verbose = false;
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Conectado. Subiendo functions.php...");
        const localFile = path.join(__dirname, "celzimo-theme", "functions.php");
        await client.uploadFrom(localFile, "/wp-content/themes/celzimo-theme/functions.php");
        console.log("✅ functions.php subido exitosamente.");

    } catch (err) {
        console.error("❌ Error:", err.message);
    } finally {
        client.close();
    }
}

uploadFunctionsOnly();
