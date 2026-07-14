const ftp = require("basic-ftp");
const path = require("path");

async function checkRemoteFolders() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Checking if /public_html exists and walking it...");
        const root = await client.list("/");
        console.log("Root files:", root.map(f => f.name));
        
        // Let's list some known directories to see their contents
        const dirsToCheck = ["/public_html", "/existe-demo", "/dist"];
        for (const dir of dirsToCheck) {
            console.log(`Contents of ${dir}:`);
            try {
                const files = await client.list(dir);
                files.forEach(f => console.log(`  - ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
            } catch (e) {
                console.log(`  Error listing ${dir}: ${e.message}`);
            }
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

checkRemoteFolders();
