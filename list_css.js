const ftp = require("basic-ftp");

async function listCSS() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listing /_archivo-html-2026-07-03/css...");
        const list = await client.list("/_archivo-html-2026-07-03/css");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listCSS();
