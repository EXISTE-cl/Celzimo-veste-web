const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function main() {
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
        
        console.log("Connected. Downloading /wp-content/themes/celzimo-theme/functions.php...");
        const dest = path.join(__dirname, "functions.php.downloaded");
        await client.downloadTo(dest, "/wp-content/themes/celzimo-theme/functions.php");
        console.log("✅ Downloaded to functions.php.downloaded");
        
        // Compare with local file
        const local = fs.readFileSync(path.join(__dirname, "celzimo-theme", "functions.php"), "utf-8");
        const downloaded = fs.readFileSync(dest, "utf-8");
        
        if (local === downloaded) {
            console.log("SUCCESS: Local and remote functions.php are IDENTICAL!");
        } else {
            console.log("WARNING: Local and remote functions.php DIFFER!");
            console.log("Local size:", local.length, "Remote size:", downloaded.length);
        }
    } catch (err) {
        console.error("Error:", err.message);
    } finally {
        client.close();
    }
}

main();
