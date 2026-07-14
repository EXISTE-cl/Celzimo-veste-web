const ftp = require("basic-ftp");

async function checkDomainsOnIP() {
    const client = new ftp.Client();
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listing contents of /public_html on todoexiste.com...");
        const rootDirs = await client.list("/");
        const publicHtmlDir = rootDirs.find(d => d.name === "public_html");
        
        if (publicHtmlDir) {
            console.log("public_html exists as root directory.");
        } else {
            console.log("public_html NOT in root. Let's look for user home folders or domains.");
        }
        
        // Let's do a wild search for folders under other users if any, or see if we are in a jail.
        // What is our current directory?
        console.log("PWD:", await client.pwd());
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

checkDomainsOnIP();
