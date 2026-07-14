const ftp = require("basic-ftp");

async function listCelzimoFTP() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP de celzimoveste.cl...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listando raíz /...");
        const list = await client.list("/");
        console.log("Archivos en raíz /:");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listCelzimoFTP();
