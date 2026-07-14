const https = require('https');

/**
 * Helper to perform HTTPS GET requests returning a Promise with the response body.
 */
function getRequest(url) {
    return new Promise((resolve, reject) => {
        const options = {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            }
        };
        https.get(url, options, (res) => {
            if (res.statusCode !== 200) {
                reject(new Error(`Request failed with status code ${res.statusCode}`));
                return;
            }
            let body = '';
            res.on('data', (chunk) => body += chunk);
            res.on('end', () => resolve(body));
        }).on('error', (err) => reject(err));
    });
}

/**
 * Helper to pause execution.
 */
function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

/**
 * Fetches all products from the Shopify store carolamiccono.cl.
 * Handles pagination.
 */
async function fetchAllProducts(baseUrl, rateLimitMs = 1500) {
    let page = 1;
    let allProducts = [];
    let hasMore = true;

    console.log(`[Scraper] Iniciando extracción de catálogo desde ${baseUrl}...`);

    while (hasMore) {
        const url = `${baseUrl}/products.json?limit=250&page=${page}`;
        console.log(`[Scraper] Extrayendo página ${page}...`);
        
        try {
            const responseText = await getRequest(url);
            const data = JSON.parse(responseText);
            
            if (data && data.products && data.products.length > 0) {
                allProducts = allProducts.concat(data.products);
                console.log(`[Scraper] Se extrajeron ${data.products.length} productos de la página ${page}. (Total acumulado: ${allProducts.length})`);
                page++;
                // Respetar rate limiting
                await sleep(rateLimitMs);
            } else {
                hasMore = false;
                console.log(`[Scraper] Fin de catálogo alcanzado en página ${page}.`);
            }
        } catch (error) {
            console.error(`[Scraper] Error al extraer página ${page}:`, error.message);
            hasMore = false; // Detener en caso de error crítico
        }
    }

    return allProducts;
}

module.exports = {
    fetchAllProducts
};
