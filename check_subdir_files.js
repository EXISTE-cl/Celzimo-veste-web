const ftp = require("basic-ftp");
const fs = require("fs");
const path = require("path");

async function checkSubdirFiles() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Locating folders on server...");
        // Walk and list files in root that might be sub-web folders
        const list = await client.list("/");
        const subfolders = list.filter(f => f.type === 2 && f.name.startsWith("_"));
        console.log("Subfolders starting with _ in root /:", subfolders.map(f => f.name));
        
        // Let's check public_html directories
        const pubList = await client.list("/public_html");
        console.log("Files/Dirs in /public_html:", pubList.map(f => f.name));
        
        // Let's check if there are subfolders in public_html starting with _ or representing websites
        const pubDirs = pubList.filter(f => f.type === 2);
        for (const dir of pubDirs) {
            console.log(`Checking inside /public_html/${dir.name}...`);
            try {
                const innerList = await client.list(`/public_html/${dir.name}`);
                console.log(`  Files/Dirs in /public_html/${dir.name}:`, innerList.map(f => f.name));
            } catch (e) {
                console.log(`  Error: ${e.message}`);
            }
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

checkSubdirFiles();
