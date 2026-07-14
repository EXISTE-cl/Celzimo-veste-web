const ftp = require("basic-ftp");

async function listExisteDemo() {
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
        
        console.log("Listando /existe-demo...");
        const list = await client.list("/existe-demo");
        console.log("Contenido de /existe-demo:");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listExisteDemo();
