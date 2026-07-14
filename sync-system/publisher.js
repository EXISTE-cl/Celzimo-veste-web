const fs = require('fs');
const path = require('path');
const vm = require('vm');
const ftp = require('basic-ftp');

/**
 * Reads the local js/data.js file and parses the existing products array.
 */
function readExistingProducts(dataJsPath) {
    const absolutePath = path.resolve(__dirname, dataJsPath);
    if (!fs.existsSync(absolutePath)) {
        console.warn(`[Publisher] Archivo js/data.js no encontrado en ${absolutePath}. Iniciando catálogo vacío.`);
        return [];
    }

    try {
        const fileContent = fs.readFileSync(absolutePath, 'utf8');
        // Evaluate the file in a sandbox to safely retrieve the products variable
        const sandbox = {};
        const result = vm.runInNewContext(fileContent + '\n; products;', sandbox);
        return result || [];
    } catch (error) {
        console.error('[Publisher] Error al parsear js/data.js existente:', error.message);
        return [];
    }
}

/**
 * Merges manual products and newly synced products, maintaining stable IDs.
 */
function mergeProducts(existingProducts, newSyncedProducts) {
    const oldSyncedProducts = existingProducts.filter(p => p.isSynced);

    console.log(`[Publisher] Encontrados ${oldSyncedProducts.length} productos previamente sincronizados.`);

    // 2. Build stable ID map for previously synced products
    const skuToIdMap = {};
    let nextId = 100; // Start synced product IDs at 100

    oldSyncedProducts.forEach(p => {
        if (p.parentSku) {
            skuToIdMap[p.parentSku] = p.id;
            if (p.id >= nextId) {
                nextId = p.id + 1;
            }
        }
    });

    // 3. Map new synced products, assigning them their old ID or a new sequential ID
    const processedSyncedProducts = newSyncedProducts.map(product => {
        let assignedId = skuToIdMap[product.parentSku];
        if (!assignedId) {
            assignedId = nextId++;
        }
        return {
            ...product,
            id: assignedId
        };
    });

    console.log(`[Publisher] Catálogo final con ${processedSyncedProducts.length} productos aprobados y sincronizados.`);
    return processedSyncedProducts;
}

/**
 * Writes the merged product array back to js/data.js locally.
 */
function writeLocalDataJs(dataJsPath, mergedProducts) {
    const absolutePath = path.resolve(__dirname, dataJsPath);
    
    // Format output code
    const fileContent = `// data.js\nconst products = ${JSON.stringify(mergedProducts, null, 4)};\n\nif (typeof module !== 'undefined' && module.exports) {\n    module.exports = products;\n}\n`;
    
    fs.writeFileSync(absolutePath, fileContent, 'utf8');
    console.log(`[Publisher] Escrito localmente en: ${absolutePath}`);
}

/**
 * Uploads the updated js/data.js file to the remote FTP server.
 */
async function uploadToFTP(config, localDataJsPath) {
    const client = new ftp.Client();
    client.ftp.verbose = false; // Set to true if debugging

    const localFile = path.resolve(__dirname, localDataJsPath);

    console.log(`[Publisher] Conectando al FTP ${config.host} para subir el archivo de datos...`);
    try {
        await client.access({
            host: config.host,
            user: config.user,
            password: config.password,
            secure: config.secure,
            secureOptions: { rejectUnauthorized: false }
        });

        console.log(`[Publisher] Conexión establecida. Asegurando directorio remoto /js...`);
        await client.ensureDir("/js");
        console.log(`[Publisher] Subiendo ${localDataJsPath} como /js/data.js...`);
        await client.uploadFrom(localFile, "data.js");

        console.log(`[Publisher] Asegurando directorio remoto /_archivo-html-2026-07-03/js...`);
        await client.ensureDir("/_archivo-html-2026-07-03/js");
        console.log(`[Publisher] Subiendo ${localDataJsPath} como /_archivo-html-2026-07-03/js/data.js...`);
        await client.uploadFrom(localFile, "data.js");

        console.log(`[Publisher] ¡Despliegue de stock e inventario en celzimoveste completado con éxito!`);
    } catch (error) {
        console.error('[Publisher] Error durante la subida por FTP:', error.message);
        throw error;
    } finally {
        client.close();
    }
}

module.exports = {
    readExistingProducts,
    mergeProducts,
    writeLocalDataJs,
    uploadToFTP
};
