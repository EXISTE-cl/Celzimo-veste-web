const ftp = require("basic-ftp");
const fs = require("fs");
const path = require("path");

async function checkDomains() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Locating public_html...");
        
        // Let's do a find for folders matching "celzimoveste.cl" on the entire server recursively
        async function walk(dir) {
            try {
                const list = await client.list(dir);
                for (const item of list) {
                    if (item.name === ".git" || item.name === "node_modules" || item.name === "wp-content") continue;
                    const fullPath = dir === "/" ? `/${item.name}` : `${dir}/${item.name}`;
                    if (item.type === 2) {
                        if (item.name.includes("celzimo") || item.name.includes("archivo")) {
                            console.log(`FOUND DIR MATCH: ${fullPath}`);
                        }
                        await walk(fullPath);
                    }
                }
            } catch (e) {}
        }
        
        await walk("/");
        console.log("Done checking.");
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

checkDomains();
