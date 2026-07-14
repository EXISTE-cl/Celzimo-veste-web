const ftp = require("basic-ftp");
const path = require("path");
const https = require("https");

async function run() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        // Use local path for script
        const localFile = "C:/Users/Cristobal/.gemini/antigravity/brain/e5275044-f5b0-4ed8-be49-5fba21a21b80/scratch/check_remote_media.php";
        const remoteFile = "/check_remote_media.php";
        
        await client.uploadFrom(localFile, remoteFile);
        client.close();

        // Trigger request
        const url = "https://celzimoveste.cl/check_remote_media.php?token=Csc170431Media";
        
        https.get(url, { rejectUnauthorized: false }, (res) => {
            let data = "";
            res.on("data", (chunk) => { data += chunk; });
            res.on("end", async () => {
                console.log("--- RESPUESTA MEDIA ---");
                console.log(data);
                console.log("------------------------");

                // Clean up
                const cleanClient = new ftp.Client();
                await cleanClient.access({
                    host: "ftp.todoexiste.com",
                    user: "CELZIMO@celzimoveste.cl",
                    password: "Csc170431*",
                    secure: true,
                    secureOptions: { rejectUnauthorized: false }
                });
                await cleanClient.remove(remoteFile);
                cleanClient.close();
                console.log("✓ Limpieza completada.");
            });
        });
    } catch (err) {
        console.error("Error:", err);
        client.close();
    }
}

run();
