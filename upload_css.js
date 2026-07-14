const ftp = require("basic-ftp");
const path = require("path");

async function uploadCSS() {
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
        await client.uploadFrom(
            path.join(__dirname, "celzimo-theme/css/style.css"),
            base + "/css/style.css"
        );
        console.log("✅ style.css subido");
    } catch(e) {
        console.error("❌", e.message);
    } finally {
        client.close();
    }
}
uploadCSS();
