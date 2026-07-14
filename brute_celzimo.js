const ftp = require("basic-ftp");

async function testPassword(pwd) {
    const client = new ftp.Client();
    try {
        console.log(`Testing password: ${pwd}`);
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: pwd,
            secure: false
        });
        console.log(`SUCCESS with password: ${pwd}`);
        client.close();
        return true;
    } catch (e) {
        console.log(`Failed with password ${pwd}: ${e.message}`);
    }
    client.close();
    return false;
}

async function start() {
    const pwds = [
        "Cm31103110*",
        "Cm31103110",
        "ASISTENTE@todoexiste.com",
        "CELZIMO*",
        "celzimoveste"
    ];
    for (const pwd of pwds) {
        if (await testPassword(pwd)) {
            break;
        }
    }
}

start();
