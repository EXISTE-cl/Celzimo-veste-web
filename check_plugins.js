const ftp = require("basic-ftp");

async function checkPlugins() {
    const client = new ftp.Client();
    client.ftp.verbose = false;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("--- Contenido de /plugins ---");
        try {
            const pluginsList = await client.list("/plugins");
            pluginsList.forEach(f => {
                console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`);
            });
        } catch (e) {
            console.log("Error en /plugins:", e.message);
        }

        console.log("--- Contenido de /wp-content ---");
        try {
            const wpContentList = await client.list("/wp-content");
            wpContentList.forEach(f => {
                console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`);
            });
        } catch (e) {
            console.log("Error en /wp-content:", e.message);
        }

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

checkPlugins();
