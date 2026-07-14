const ftp = require("basic-ftp");

async function checkSubfolders() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Checking folder list in root...");
        const list = await client.list("/");
        const dirs = list.filter(f => f.type === 2);
        
        for (const dir of dirs) {
            console.log(`Checking inside /${dir.name} for subfolders...`);
            try {
                const sublist = await client.list(`/${dir.name}`);
                const subdirs = sublist.filter(f => f.type === 2);
                if (subdirs.length > 0) {
                    console.log(`  Subdirs in /${dir.name}:`, subdirs.map(s => s.name));
                }
                const matches = sublist.filter(f => f.name.toLowerCase().includes("archivo"));
                if (matches.length > 0) {
                    console.log(`  MATCHING FILES in /${dir.name}:`, matches.map(s => s.name));
                }
            } catch (e) {
                console.log(`  Error: ${e.message}`);
            }
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

checkSubfolders();
