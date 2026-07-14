const ftp = require("basic-ftp");
const path = require("path");

async function uploadInfo() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Subiendo info.php...");
        
        await client.uploadFrom(path.join(__dirname, "info.php"), "/info.php");
        
        try {
            await client.uploadFrom(path.join(__dirname, "info.php"), "/public_html/info.php");
        } catch(e) {}
        
        try {
            await client.uploadFrom(path.join(__dirname, "info.php"), "/public_html/celzimoveste.cl/info.php");
        } catch(e) {}

        console.log("Subido.");
    }
    catch(err) {
        console.error("Error:", err);
    }
    client.close();
}

uploadInfo();
