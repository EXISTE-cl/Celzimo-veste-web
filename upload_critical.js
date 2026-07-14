const ftp = require("basic-ftp");
const path = require("path");

async function uploadCriticalFiles() {
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

        const base = "/wp-content/themes/celzimo-theme";
        const files = [
            { local: "celzimo-theme/index.php",        remote: base + "/index.php" },
            { local: "celzimo-theme/functions.php",    remote: base + "/functions.php" },
            { local: "celzimo-theme/front-page.php",   remote: base + "/front-page.php" },
        ];

        for (const f of files) {
            const local = path.join(__dirname, f.local);
            await client.uploadFrom(local, f.remote);
            console.log("✅ " + f.remote);
        }
        console.log("Todos los archivos subidos.");
    } catch (err) {
        console.error("❌ Error:", err.message);
    } finally {
        client.close();
    }
}

uploadCriticalFiles();
