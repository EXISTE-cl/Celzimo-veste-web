const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

function run() {
    try {
        console.log("Cargando credenciales del archivo .env...");
        const envPath = "C:\\Users\\Cristobal\\.env";
        if (!fs.existsSync(envPath)) {
            throw new Error("No se encontró el archivo .env en " + envPath);
        }

        const envContent = fs.readFileSync(envPath, 'utf8');
        let token = "";
        
        const lines = envContent.split(/\r?\n/);
        for (const line of lines) {
            const trimLine = line.trim();
            if (trimLine.startsWith('GITHUB_TOKEN=')) {
                token = trimLine.substring('GITHUB_TOKEN='.length).replace(/['"]/g, '');
                break;
            }
        }

        if (!token) {
            throw new Error("No se encontró GITHUB_TOKEN configurado en tu archivo .env. Por favor, regístralo primero con el comando de PowerShell.");
        }

        console.log("Token de GitHub cargado de forma segura. Configurando acceso remoto...");
        const recoveryRepo = "github.com/EXISTE-cl/Celzimo-veste-web.git";
        const authenticatedUrl = `https://${token}@${recoveryRepo}`;
        
        execSync(`git remote set-url origin "${authenticatedUrl}"`);
        console.log("Subiendo commits a la rama main de GitHub de forma desatendida...");
        
        execSync("git push origin main --force");
        console.log("✓ ¡Subida completada con éxito en GitHub!");
        
    } catch (err) {
        console.error("❌ Error en el proceso de recovery:", err.message);
    } finally {
        try {
            console.log("Restaurando URL remota limpia por seguridad...");
            execSync('git remote set-url origin "https://github.com/EXISTE-cl/Celzimo-veste-web.git"');
            console.log("✓ URL remota limpia restaurada.");
        } catch (cleanErr) {
            console.error("Error al restaurar URL remota:", cleanErr.message);
        }
    }
}

run();
