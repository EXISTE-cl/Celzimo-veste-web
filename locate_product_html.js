const ftp = require("basic-ftp");

async function locateProductHtml() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Starting search for product.html / index.html (excluding .git and node_modules)...");
        
        async function walk(dir) {
            console.log(`Walking directory: ${dir}`);
            try {
                const list = await client.list(dir);
                for (const item of list) {
                    if (item.name === ".git" || item.name === "node_modules") {
                        continue;
                    }
                    const fullPath = dir === "/" ? `/${item.name}` : `${dir}/${item.name}`;
                    if (item.type === 2) {
                        // Directory
                        await walk(fullPath);
                    } else {
                        // File
                        if (item.name === "product.html" || item.name === "index.html") {
                            console.log(`FOUND: ${fullPath} (size: ${item.size})`);
                        }
                    }
                }
            } catch (e) {
                console.log(`Error listing ${dir}: ${e.message}`);
            }
        }

        await walk("/");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

locateProductHtml();
