const ftp = require("basic-ftp");
const path = require("path");

async function uploadInspect() {
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
        
        console.log("Conectado. Subiendo check_active_theme.php...");
        const localFile = path.join(__dirname, "check_active_theme.php");
        await client.uploadFrom(localFile, "/check_active_theme.php");
        console.log("✅ check_active_theme.php subido exitosamente.");

    } catch (err) {
        console.error("❌ Error:", err.message);
    } finally {
        client.close();
    }
}

uploadInspect();
