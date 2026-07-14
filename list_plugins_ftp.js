const ftp = require("basic-ftp");

async function main() {
    const client = new ftp.Client();
    client.ftp.verbose = false;
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Connected. Listing /wp-content/plugins...");
        const list = await client.list("/wp-content/plugins");
        for (const item of list) {
            if (item.isDirectory) {
                console.log("DIR:", item.name);
            } else {
                console.log("FILE:", item.name);
            }
        }
    } catch (err) {
        console.error("Error:", err.message);
    } finally {
        client.close();
    }
}

main();
