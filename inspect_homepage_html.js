const fs = require("fs");
const path = require("path");

function inspectHtml() {
    const file = path.join(__dirname, "homepage_output.html");
    if (!fs.existsSync(file)) {
        console.error("No existe homepage_output.html");
        return;
    }
    
    const html = fs.readFileSync(file, "utf8");
    
    // 1. Encontrar clase body
    const bodyMatch = html.match(/<body[^>]*>/i);
    console.log("Body tag encontrada:", bodyMatch ? bodyMatch[0] : "No encontrada");
    
    // 2. Encontrar títulos H1 y H2
    const hMatches = html.match(/<h[12][^>]*>([\s\S]*?)<\/h[12]>/gi);
    console.log("Títulos H1/H2 encontrados:");
    if (hMatches) {
        hMatches.forEach(h => console.log(" - " + h.replace(/<[^>]+>/g, "").trim()));
    } else {
        console.log(" Ninguno");
    }
    
    // 3. Buscar clases de templates comunes o comentarios de bloque de WP
    console.log("¿Contiene 'wp-block-group'? ", html.includes("wp-block-group"));
    console.log("¿Contiene 'Hello world!'? ", html.includes("Hello world!"));
    console.log("¿Contiene 'Twenty Twenty-Five'? ", html.includes("Twenty Twenty-Five"));
    
    // 4. Mostrar una sección representativa de los primeros 2000 caracteres del body
    if (bodyMatch) {
        const bodyIndex = html.indexOf(bodyMatch[0]);
        console.log("\nFragmento del body (2000 caracteres):");
        console.log(html.substring(bodyIndex, bodyIndex + 2000));
    }
}

inspectHtml();
