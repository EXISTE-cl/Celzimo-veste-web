const ftp = require("basic-ftp");
const path = require("path");

async function downloadRootIndex() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Downloading /index.html...");
        await client.downloadTo(path.join(__dirname, "index_root_ftp.html"), "/index.html");
        console.log("Downloaded.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

downloadRootIndex();
