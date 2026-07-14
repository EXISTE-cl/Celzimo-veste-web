const fs = require("fs");
const path = require("path");

const basePath = "C:\\Users\\Cristobal\\.gemini\\antigravity\\scratch\\celzimo-veste";

// 1. Remove navigation menus in all HTML files
function cleanNavigation() {
    const htmlFiles = fs.readdirSync(basePath).filter(f => f.endsWith(".html"));
    
    const regexHombre = /<li class="nav-item\s+has-dropdown">\s*<a href="[^"]*">DENIM HOMBRE<\/a>[\s\S]*?<\/li>/gi;
    const regexAccesorios = /<li class="nav-item\s+has-dropdown">\s*<a href="[^"]*">ACCESORIOS<\/a>[\s\S]*?<\/li>/gi;

    for (const file of htmlFiles) {
        const filePath = path.join(basePath, file);
        let content = fs.readFileSync(filePath, "utf8");
        
        let modified = false;
        if (regexHombre.test(content)) {
            content = content.replace(regexHombre, "");
            modified = true;
        }
        if (regexAccesorios.test(content)) {
            content = content.replace(regexAccesorios, "");
            modified = true;
        }
        
        if (modified) {
            fs.writeFileSync(filePath, content, "utf8");
            console.log(`Cleaned navigation in ${file}`);
        }
    }
}

// 2. Modify index.html and index_root_ftp.html sections
function cleanIndexSections() {
    const indexFiles = ["index.html", "index_root_ftp.html"];
    
    // Banner replacement
    const bannerRegex = /<a href="shop\.html\?category=jeans" class="banner-item">[^]*?alt="Todo Hombre"[^]*?<\/a>/gi;
    
    // Categories row replacement
    const categoriesTarget = /<div class="categories-row">[^]*?<\/div>/gi;
    const categoriesReplacement = `<div class="categories-row" style="grid-template-columns: repeat(3, 1fr);">
            <a href="shop.html?category=jeans" class="category-square-link">
                <img src="assets/cv_ig_4_1782608818924.png" alt="Jeans Mujer">
                <h4>JEANS MUJER</h4>
                <span>Ver colección</span>
            </a>
            <a href="shop.html?category=chaquetas" class="category-square-link">
                <img src="assets/jacket.png" alt="Chaquetas Mujer">
                <h4>CHAQUETAS MUJER</h4>
                <span>Ver colección</span>
            </a>
            <a href="shop.html?category=camisas" class="category-square-link">
                <img src="assets/cv_ig_2_1782608801421.png" alt="Poleras Mujer">
                <h4>POLERAS MUJER</h4>
                <span>Ver colección</span>
            </a>
        </div>`;

    for (const file of indexFiles) {
        const filePath = path.join(basePath, file);
        if (!fs.existsSync(filePath)) continue;
        
        let content = fs.readFileSync(filePath, "utf8");
        
        // Remove Todo Hombre banner
        if (bannerRegex.test(content)) {
            content = content.replace(bannerRegex, "");
            console.log(`Removed Todo Hombre banner from ${file}`);
        }
        
        // Replace categories row
        if (categoriesTarget.test(content)) {
            content = content.replace(categoriesTarget, categoriesReplacement);
            console.log(`Replaced categories row in ${file}`);
        }
        
        fs.writeFileSync(filePath, content, "utf8");
    }
}

// 3. Remove accessories filter in shop.html
function cleanShopFilters() {
    const shopPath = path.join(basePath, "shop.html");
    if (!fs.existsSync(shopPath)) return;
    
    let content = fs.readFileSync(shopPath, "utf8");
    const filterRegex = /<li><label><input type="radio" name="category" value="accesorios"> Accesorios<\/label><\/li>/gi;
    
    if (filterRegex.test(content)) {
        content = content.replace(filterRegex, "");
        fs.writeFileSync(shopPath, content, "utf8");
        console.log("Removed accessories filter from shop.html");
    }
}

// 4. Remove accessories products from js/data.js
function cleanDataJs() {
    const dataJsPath = path.join(basePath, "js", "data.js");
    if (!fs.existsSync(dataJsPath)) return;
    
    let content = fs.readFileSync(dataJsPath, "utf8");
    
    // Let's parse the products array out of the file
    // To do it safely, since it's simple JS, we can read the file and filter the items
    // by category !== "accesorios".
    // Let's load the products list
    const products = require(dataJsPath);
    const filteredProducts = products.filter(p => p.category !== "accesorios");
    
    const newContent = `// data.js\nconst products = ${JSON.stringify(filteredProducts, null, 4)};\n\nif (typeof module !== 'undefined' && module.exports) {\n    module.exports = products;\n}\n`;
    fs.writeFileSync(dataJsPath, newContent, "utf8");
    console.log("Filtered out accessories products from js/data.js");
}

function run() {
    cleanNavigation();
    cleanIndexSections();
    cleanShopFilters();
    cleanDataJs();
}

run();
