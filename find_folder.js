const ftp = require("basic-ftp");

async function findFolder() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        const paths = [
            "/_archivo-html-2026-07-03",
            "/public_html/_archivo-html-2026-07-03",
            "/celzimoveste.cl/_archivo-html-2026-07-03",
            "/public_html/celzimoveste.cl/_archivo-html-2026-07-03"
        ];

        for (const p of paths) {
            console.log(`Checking path: ${p}...`);
            try {
                const list = await client.list(p);
                console.log(`SUCCESS: Found ${list.length} files in ${p}`);
                list.slice(0, 10).forEach(f => console.log(`  - ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
                return p;
            } catch (e) {
                console.log(`  Not found or error: ${e.message}`);
            }
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

findFolder();
