const ftp = require("basic-ftp");

async function listPublicHtml() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP (secure: false)...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: false
        });
        
        console.log("Listando /public_html...");
        const list = await client.list("/public_html");
        console.log("Archivos en /public_html:");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listPublicHtml();
