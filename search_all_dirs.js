const ftp = require("basic-ftp");

async function searchAllDirs() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Searching root /...");
        const rootList = await client.list("/");
        const matchingRoot = rootList.filter(f => f.name.toLowerCase().includes("archivo") || f.name.toLowerCase().includes("html") || f.type === 2);
        console.log("Matching in root /:");
        matchingRoot.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));

        for (const dir of rootList.filter(f => f.type === 2)) {
            console.log(`Searching inside /${dir.name}...`);
            try {
                const subList = await client.list(`/${dir.name}`);
                const matchingSub = subList.filter(f => f.name.toLowerCase().includes("archivo") || f.name.toLowerCase().includes("html") || f.name.toLowerCase().includes("product"));
                if (matchingSub.length > 0) {
                    console.log(`  Matching in /${dir.name}:`);
                    matchingSub.forEach(f => console.log(`    - ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
                }
            } catch (e) {
                console.log(`  Could not list /${dir.name}: ${e.message}`);
            }
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

searchAllDirs();
