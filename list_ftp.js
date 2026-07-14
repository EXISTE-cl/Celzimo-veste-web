const ftp = require("basic-ftp");

async function listFTP() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listando raíz...");
        const list = await client.list("/");
        console.log(JSON.stringify(list, null, 2));

        console.log("Listando /public_html...");
        try {
            const listPublic = await client.list("/public_html");
            console.log(JSON.stringify(listPublic, null, 2));
        } catch (e) {
            console.log("Error listing /public_html:", e.message);
        }

        console.log("Listando /public_html/celzimoveste.cl...");
        try {
            const listCelzimo = await client.list("/public_html/celzimoveste.cl");
            console.log(JSON.stringify(listCelzimo, null, 2));
        } catch (e) {
            console.log("Error listing /public_html/celzimo.cl:", e.message);
        }
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listFTP();
