const ftp = require("basic-ftp");

async function searchBooking() {
    const client = new ftp.Client();
    client.ftp.verbose = false;
    try {
        console.log("Conectando al FTP con ASISTENTE@todoexiste.com...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        console.log("Listando raíz /...");
        const rootList = await client.list("/");
        console.log("Carpetas y archivos en raíz que contienen 'booking' o 'reserva':");
        rootList.forEach(f => {
            const nameLower = f.name.toLowerCase();
            if (nameLower.includes("booking") || nameLower.includes("reserva") || nameLower.includes("appointment") || nameLower.includes("turno")) {
                console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`);
            }
        });

        // Buscar subcarpetas interesantes en raíz
        const targetDirs = rootList.filter(f => f.type === 2);
        console.log("\nCarpetas encontradas en raíz:", targetDirs.map(d => d.name));

        for (const dir of targetDirs) {
            console.log(`\nListando carpeta /${dir.name}...`);
            try {
                const subList = await client.list(`/${dir.name}`);
                subList.forEach(f => {
                    const nameLower = f.name.toLowerCase();
                    if (nameLower.includes("booking") || nameLower.includes("reserva") || nameLower.includes("appointment") || nameLower.includes("turno")) {
                        console.log(`  [MATCH] /${dir.name}/${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`);
                    }
                });
            } catch (e) {
                console.log(`  Error listando /${dir.name}:`, e.message);
            }
        }
        
    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

searchBooking();
