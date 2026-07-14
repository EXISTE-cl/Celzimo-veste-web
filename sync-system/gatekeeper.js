const fs = require('fs');

/**
 * Parses a simple CSV file into an array of objects.
 */
function parseCSV(filePath) {
    if (!fs.existsSync(filePath)) {
        console.warn(`[Gatekeeper] Archivo CSV no encontrado en: ${filePath}`);
        return [];
    }
    const content = fs.readFileSync(filePath, 'utf8');
    const lines = content.split(/\r?\n/);
    if (lines.length < 2) return [];
    
    // Parse header
    const headers = lines[0].split(';').map(h => h.trim().toLowerCase());
    
    const records = [];
    for (let i = 1; i < lines.length; i++) {
        const line = lines[i].trim();
        if (!line) continue;
        
        const values = line.split(';').map(v => v.trim());
        const record = {};
        headers.forEach((header, index) => {
            record[header] = values[index] || '';
        });
        records.push(record);
    }
    return records;
}

/**
 * Cleans HTML tags from a string.
 */
function cleanHtml(html) {
    if (!html) return '';
    return html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

/**
 * Determines the category of a product based on its tags, type, or title.
 */
function parseCategory(product) {
    const title = product.title.toLowerCase();
    const type = (product.product_type || '').toLowerCase();
    const tags = (product.tags || []).map(t => t.toLowerCase());

    if (title.includes('pantalon') || title.includes('jeans') || title.includes('denim') || tags.includes('jeans') || type.includes('jeans')) {
        return 'jeans';
    }
    if (title.includes('chaqueta') || title.includes('tapa') || title.includes('vest') || tags.includes('chaquetas') || type.includes('jacket')) {
        return 'chaquetas';
    }
    if (title.includes('vestido') || title.includes('pollera') || tags.includes('vestidos')) {
        return 'vestidos';
    }
    // Default category
    return 'accesorios';
}

/**
 * Filters the scraped catalog using the manual approval list.
 * Only returns products and variants that are explicitly approved and in stock.
 */
function filterAndNormalize(scrapedProducts, csvPath) {
    const approvalList = parseCSV(csvPath);
    console.log(`[Gatekeeper] Cargadas ${approvalList.length} reglas de aprobación desde CSV.`);

    // Build map of approvals by variant SKU
    const approvalMap = {};
    for (const rule of approvalList) {
        if (rule.variant_sku) {
            approvalMap[rule.variant_sku.trim().toUpperCase()] = {
                status: rule.status.trim().toLowerCase(),
                maxStock: parseInt(rule.max_publish_stock) || 0,
                color: rule.color,
                size: rule.size
            };
        }
    }

    const approvedProducts = [];
    let totalSkippedVariants = 0;
    let totalApprovedVariants = 0;

    for (const product of scrapedProducts) {
        const approvedVariantsForProduct = [];
        const approvedSizes = new Set();
        const approvedColors = new Set();

        // Evaluate variants of the product
        for (const variant of product.variants) {
            const skuKey = (variant.sku || '').trim().toUpperCase();
            
            if (!skuKey || !approvalMap[skuKey]) {
                totalSkippedVariants++;
                continue;
            }

            const rule = approvalMap[skuKey];
            
            // Check status
            if (rule.status !== 'published') {
                console.log(`[Gatekeeper] SKU ${variant.sku} omitido (Estado: ${rule.status})`);
                totalSkippedVariants++;
                continue;
            }

            // Cross reference stock: limit by manual max stock, set to 0 if out of stock in source
            const scrapedStockAvailable = variant.available; // Shopify indicates true/false availability
            const maxAllowedStock = rule.maxStock;
            const finalStock = scrapedStockAvailable ? maxAllowedStock : 0;

            if (finalStock <= 0) {
                console.log(`[Gatekeeper] SKU ${variant.sku} omitido por falta de stock (Calculado: 0)`);
                totalSkippedVariants++;
                continue;
            }

            // Variant approved and has stock
            totalApprovedVariants++;
            approvedVariantsForProduct.push({
                sku: variant.sku,
                price: parseFloat(variant.price),
                compare_at_price: variant.compare_at_price ? parseFloat(variant.compare_at_price) : null,
                size: rule.size || variant.title,
                color: rule.color || 'Único',
                stock: finalStock
            });

            if (rule.size) approvedSizes.add(rule.size);
            else if (variant.title) approvedSizes.add(variant.title);

            if (rule.color) approvedColors.add(rule.color);
        }

        // If product has approved variants, format and add it
        if (approvedVariantsForProduct.length > 0) {
            // Generate standard colors array using product images (simulate colors)
            const images = (product.images || []).map(img => img.src);
            const mainImage = images[0] || '';

            // Map to celzimoveste.cl format
            const normalizedProduct = {
                id: null, // Assigned later in publisher
                title: product.title,
                brand: 'Carola Miccono',
                price: approvedVariantsForProduct[0].price, // Use first variant price
                compare_at_price: approvedVariantsForProduct[0].compare_at_price || null,
                category: parseCategory(product),
                image: mainImage,
                description: cleanHtml(product.body_html) || 'Sin descripción disponible.',
                sizes: Array.from(approvedSizes),
                colors: images.slice(0, 4), // Use up to 4 images as color thumbs
                availableDelivery: true,
                availableStore: true,
                // Meta fields for sync logic
                isSynced: true,
                parentSku: product.id.toString(),
                variants: approvedVariantsForProduct
            };

            approvedProducts.push(normalizedProduct);
        }
    }

    console.log(`[Gatekeeper] Filtrado completado. Aprobados: ${approvedProducts.length} productos (${totalApprovedVariants} variantes). Omitidas: ${totalSkippedVariants} variantes.`);
    return approvedProducts;
}

module.exports = {
    filterAndNormalize
};
