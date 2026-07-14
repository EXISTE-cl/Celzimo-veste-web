const ftp = require("basic-ftp");
const path = require("path");

async function getDeploy() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Downloading /deploy.js...");
        await client.downloadTo(path.join(__dirname, "deploy.js"), "/deploy.js");
        console.log("Downloaded.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

getDeploy();
