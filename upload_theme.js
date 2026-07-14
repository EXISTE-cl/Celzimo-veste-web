const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");

async function uploadThemeAndConfig() {
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

        // 1. Subir recursivamente celzimo-theme a /wp-content/themes/celzimo-theme
        const localThemeDir = path.join(__dirname, "celzimo-theme");
        const remoteThemeDir = "/wp-content/themes/celzimo-theme";
        
        console.log(`Subiendo tema desde ${localThemeDir} hacia ${remoteThemeDir}...`);
        await uploadDirRecursive(client, localThemeDir, remoteThemeDir);
        console.log("Tema subido exitosamente.");

        // 2. Modificar y subir .htaccess a la raíz /
        const localHtaccess = path.join(__dirname, ".htaccess");
        const remoteHtaccess = "/.htaccess";
        console.log(`Subiendo .htaccess a la raíz...`);
        if (fs.existsSync(localHtaccess)) {
            await client.uploadFrom(localHtaccess, remoteHtaccess);
            console.log(".htaccess actualizado en el servidor.");
        } else {
            console.error(".htaccess local no encontrado.");
        }

        // 3. Modificar wp-config.php en el servidor de forma segura
        const localWpConfigTemp = path.join(__dirname, "wp-config-temp.php");
        const remoteWpConfig = "/wp-config.php";
        
        console.log("Descargando wp-config.php del servidor...");
        await client.downloadTo(localWpConfigTemp, remoteWpConfig);
        
        let configContent = fs.readFileSync(localWpConfigTemp, "utf8");
        
        // Verificar si ya tiene las definiciones de HTTPS
        if (!configContent.includes("FORCE_SSL_ADMIN")) {
            console.log("Inyectando configuraciones de seguridad HTTPS en wp-config.php...");
            
            const secureCode = `
// Forzar HTTPS y redirecciones en URLs de WordPress
define( 'WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'FORCE_SSL_ADMIN', true );
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
    \$_SERVER['HTTPS'] = 'on';
}
define( 'DISALLOW_FILE_EDIT', true );
`;
            
            // Buscar punto de inserción antes de la línea de stop editing
            const searchPhrases = [
                "/* That's all, stop editing!",
                "/* ¡Eso es todo, deja de editar!",
                "/** Absolute path to the WordPress directory"
            ];
            
            let inserted = false;
            for (const phrase of searchPhrases) {
                if (configContent.includes(phrase)) {
                    configContent = configContent.replace(phrase, secureCode + "\n" + phrase);
                    inserted = true;
                    break;
                }
            }
            
            if (!inserted) {
                // Si no se encuentra, agregar al final del archivo
                configContent += "\n" + secureCode;
            }
            
            fs.writeFileSync(localWpConfigTemp, configContent, "utf8");
            console.log("Subiendo wp-config.php modificado...");
            await client.uploadFrom(localWpConfigTemp, remoteWpConfig);
            console.log("wp-config.php actualizado con HTTPS y seguridad.");
        } else {
            console.log("wp-config.php ya contenía configuraciones de seguridad HTTPS.");
        }
        
        // Eliminar temporal local
        if (fs.existsSync(localWpConfigTemp)) {
            fs.unlinkSync(localWpConfigTemp);
        }

    } catch (err) {
        console.error("Error durante el despliegue:", err);
    }
    client.close();
}

async function uploadDirRecursive(client, localDir, remoteDir) {
    await client.ensureDir(remoteDir);
    const items = fs.readdirSync(localDir);
    
    for (const item of items) {
        const localItemPath = path.join(localDir, item);
        const remoteItemPath = `${remoteDir}/${item}`;
        const stat = fs.lstatSync(localItemPath);
        
        if (stat.isDirectory()) {
            await uploadDirRecursive(client, localItemPath, remoteItemPath);
            // basic-ftp cambia el directorio de trabajo tras asegurar/subir, 
            // así que debemos volver a asegurar el directorio padre
            await client.cd(remoteDir);
        } else if (stat.isFile()) {
            console.log(`Subiendo archivo: ${item} -> ${remoteItemPath}`);
            await client.uploadFrom(localItemPath, remoteItemPath);
        }
    }
}

uploadThemeAndConfig();
