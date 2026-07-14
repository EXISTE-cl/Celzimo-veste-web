const ftp = require("basic-ftp");
const path = require("path");

async function deploy() {
    const client = new ftp.Client();
    // client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: false
        });
        console.log("Conectado exitosamente. Subiendo archivos...");
        
        // Cargar todo excepto node_modules, .git y archivos de config que no van al host
        await client.uploadFromDir(__dirname, "/", {
            ignore: (file) => {
                const ignoreFiles = ['node_modules', '.git', '.agents', '.gitignore', 'package.json', 'package-lock.json', 'deploy.js', 'README.md'];
                return ignoreFiles.includes(file.name);
            }
        });
        
        console.log("¡Despliegue completado con éxito!");
    }
    catch(err) {
        console.error("Error durante el despliegue:", err);
    }
    client.close();
}

deploy();
