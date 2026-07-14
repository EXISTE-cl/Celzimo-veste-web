const ftp = require("basic-ftp");

async function listCelzimoOnTodoExiste() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando a ftp.todoexiste.com como CELZIMO@celzimoveste.cl...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("¡ÉXITO DE LOGUEO!");
        console.log("Listando raíz /...");
        const list = await client.list("/");
        list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

listCelzimoOnTodoExiste();
