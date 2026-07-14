const fs = require('fs');
const path = require('path');
const scraper = require('./scraper');
const gatekeeper = require('./gatekeeper');
const publisher = require('./publisher');

// Cargar configuración
const configPath = path.resolve(__dirname, 'config.json');
let config;
try {
    config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
} catch (error) {
    console.error('Error al cargar config.json:', error.message);
    process.exit(1);
}

/**
 * Registra logs tanto en consola como en el archivo de auditoría local.
 */
function logAudit(message) {
    const timestamp = new Date().toISOString();
    const logLine = `[${timestamp}] ${message}`;
    console.log(message);

    try {
        const logDir = path.resolve(__dirname, '../logs');
        if (!fs.existsSync(logDir)) {
            fs.mkdirSync(logDir, { recursive: true });
        }
        fs.appendFileSync(path.join(logDir, 'sync_audit.log'), logLine + '\n', 'utf8');
    } catch (err) {
        console.error('Error al escribir en el archivo de logs:', err.message);
    }
}

/**
 * Función principal para ejecutar el lote de sincronización de stock y catálogo.
 */
async function runSync() {
    logAudit('================================================================');
    logAudit('INICIANDO PROCESO DE SINCRONIZACIÓN DE STOCK E INVENTARIO');
    logAudit(`Origen: ${config.sourceUrl}`);
    logAudit(`Destino: Local (${config.localDataJsPath}) y FTP (${config.ftp.host})`);
    logAudit('================================================================');

    try {
        // 1. Extraer catálogo completo del origen (carolamiccono.cl)
        const scrapedProducts = await scraper.fetchAllProducts(config.sourceUrl, config.rateLimitMs);
        logAudit(`[Scraper] Se extrajeron ${scrapedProducts.length} productos en total.`);

        if (scrapedProducts.length === 0) {
            logAudit('[WARN] No se extrajeron productos del origen. Abortando sincronización para evitar pérdida de datos.');
            return;
        }

        // 2. Filtrar y Normalizar contra la lista de aprobación manual (CSV)
        const csvPath = path.resolve(__dirname, config.approvalCsvPath);
        logAudit(`[Gatekeeper] Procesando lista de aprobación: ${csvPath}`);
        const approvedProducts = gatekeeper.filterAndNormalize(scrapedProducts, csvPath);
        
        logAudit(`[Gatekeeper] ${approvedProducts.length} productos aprobados listos para fusionar.`);

        // 3. Leer catálogo de productos existente en celzimoveste.cl (js/data.js)
        const existingProducts = publisher.readExistingProducts(config.localDataJsPath);
        logAudit(`[Publisher] Leídos ${existingProducts.length} productos actuales del archivo de datos.`);

        // 4. Fusionar manteniendo los manuales y actualizando/añadiendo los aprobados con ID estable
        const finalMergedProducts = publisher.mergeProducts(existingProducts, approvedProducts);

        // 5. Guardar localmente
        publisher.writeLocalDataJs(config.localDataJsPath, finalMergedProducts);
        logAudit('[Publisher] Catálogo actualizado localmente de forma exitosa.');

        // 6. Subir por FTP el archivo de datos actualizado
        await publisher.uploadToFTP(config.ftp, config.localDataJsPath);
        logAudit('[SUCCESS] Sincronización y despliegue FTP finalizados con éxito.');

    } catch (error) {
        logAudit(`[FATAL ERROR] Fallo general en la sincronización: ${error.stack}`);
        process.exit(1);
    }
}

// Ejecutar
runSync();
