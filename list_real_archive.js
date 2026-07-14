const ftp = require("basic-ftp");

async function listRealArchive() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listing /_archivo-html-2026-07-03...");
        const list = await client.list("/_archivo-html-2026-07-03");
        console.log("Files in archive directory:");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listRealArchive();
