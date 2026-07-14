const ftp = require("basic-ftp");

async function testHost() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Probando con ftp.celzimoveste.cl como host...");
        await client.access({
            host: "ftp.celzimoveste.cl",
            user: "CELZIMO@celzimoveste.cl",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        console.log("¡ÉXITO!");
    } catch (e) {
        console.log("Fallo:", e.message);
    }
    client.close();
}

testHost();
