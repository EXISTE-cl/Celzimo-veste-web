const fs = require("fs");
const path = require("path");

function testRegex() {
    const file = path.join(__dirname, "index.html");
    let content = fs.readFileSync(file, "utf8");
    
    const initialLen = content.length;
    
    // Regex for DENIM HOMBRE
    const regexHombre = /<li class="nav-item\s+has-dropdown">\s*<a href="[^"]*">DENIM HOMBRE<\/a>[\s\S]*?<\/li>/gi;
    content = content.replace(regexHombre, "");
    
    // Regex for ACCESORIOS
    const regexAccesorios = /<li class="nav-item\s+has-dropdown">\s*<a href="[^"]*">ACCESORIOS<\/a>[\s\S]*?<\/li>/gi;
    content = content.replace(regexAccesorios, "");
    
    console.log(`Original size: ${initialLen}, New size: ${content.length}`);
    console.log("Matches remaining for HOMBRE:", content.includes("DENIM HOMBRE"));
    console.log("Matches remaining for ACCESORIOS:", content.includes("ACCESORIOS"));
}

testRegex();
