const ftp = require("basic-ftp");

async function listRemoteThemeFiles() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Conectado exitosamente.");
        const themeDir = "/wp-content/themes/celzimo-theme";
        
        console.log(`Listando archivos en ${themeDir}:`);
        const list = await client.list(themeDir);
        list.forEach(f => console.log(`- ${f.name} (Size: ${f.size} bytes, Type: ${f.type === 2 ? 'DIR' : 'FILE'})`));
        
        // Listar subdirectorios si es necesario
        console.log("Listando subdirectorio css:");
        const cssList = await client.list(`${themeDir}/css`);
        cssList.forEach(f => console.log(`  - css/${f.name} (Size: ${f.size} bytes)`));

        console.log("Listando subdirectorio js:");
        const jsList = await client.list(`${themeDir}/js`);
        jsList.forEach(f => console.log(`  - js/${f.name} (Size: ${f.size} bytes)`));

    } catch (err) {
        console.error("Error al listar archivos remotos:", err.message);
    }
    client.close();
}

listRemoteThemeFiles();
