const ftp = require("basic-ftp");

async function listPublicHtml() {
    const client = new ftp.Client();
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listando /public_html...");
        const list = await client.list("/public_html");
        console.log("Contenido de /public_html:");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listPublicHtml();
