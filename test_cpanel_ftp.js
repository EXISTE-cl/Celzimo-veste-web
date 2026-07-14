const ftp = require("basic-ftp");

async function testCpanelFTP() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    
    // We will test both main cPanel usernames: "celzimo" and "celzimov"
    const usernames = ["celzimo", "celzimov", "todoexiste"];
    const passwords = ["Cm31103110*", "Cm31103110"];
    
    for (const user of usernames) {
        for (const pwd of passwords) {
            try {
                console.log(`Testing FTP login: host=ftp.celzimoveste.cl, user=${user}, password=${pwd}`);
                await client.access({
                    host: "ftp.celzimoveste.cl",
                    user: user,
                    password: pwd,
                    secure: false
                });
                console.log(`SUCCESS! Connected as ${user}`);
                
                console.log("Listing root directory...");
                const list = await client.list("/");
                list.forEach(f => console.log(`- ${f.name} (${f.type === 2 ? 'DIR' : 'FILE'})`));
                
                client.close();
                return;
            } catch (e) {
                console.log(`Failed for user ${user}: ${e.message}`);
            }
        }
    }
    client.close();
}

testCpanelFTP();
