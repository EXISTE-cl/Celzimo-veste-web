const ftp = require("basic-ftp");
const path = require("path");

async function upload() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP de Celzimo...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Conexión establecida. Asegurando el directorio remoto...");
        await client.ensureDir("/wp-content/plugins/bluex-for-woocommerce");
        
        console.log("Subiendo archivos del plugin...");
        const localDir = "c:\\Users\\Cristobal\\.gemini\\antigravity\\scratch\\tmp_bluex\\bluex-for-woocommerce";
        await client.uploadFromDir(localDir);
        
        console.log("¡Plugin subido correctamente a /wp-content/plugins/bluex-for-woocommerce!");
    } catch (err) {
        console.error("Error durante la subida:", err);
    } finally {
        client.close();
    }
}

upload();
