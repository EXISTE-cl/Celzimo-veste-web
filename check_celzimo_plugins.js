const ftp = require("basic-ftp");

async function checkCelzimoPlugins() {
    const client = new ftp.Client();
    client.ftp.verbose = false;
    try {
        console.log("Conectando al FTP de celzimoveste.cl...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("--- Contenido de /wp-content/plugins ---");
        try {
            const wpPluginsList = await client.list("/wp-content/plugins");
            wpPluginsList.forEach(f => {
                console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`);
            });
        } catch (e) {
            console.log("Error en /wp-content/plugins:", e.message);
        }
        
    } catch (err) {
        console.error("Error general:", err);
    }
    client.close();
}

checkCelzimoPlugins();
