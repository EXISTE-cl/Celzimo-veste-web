const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const https = require("https");

async function runPhpRemotely(localPhpFilename) {
    const client = new ftp.Client();
    // client.ftp.verbose = true;
    try {
        console.log(`Connecting to FTP to upload ${localPhpFilename}...`);
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        const localFile = path.join(__dirname, localPhpFilename);
        const remoteFile = `/${localPhpFilename}`;
        
        await client.uploadFrom(localFile, remoteFile);
        console.log(`Uploaded ${localPhpFilename} to remote root.`);
        client.close();

        // Trigger execution
        console.log(`Triggering remote script execution via HTTPS...`);
        const url = `https://celzimoveste.cl/${localPhpFilename}?token=Csc170431Activation&t=${Date.now()}`;
        
        const requestOptions = {
            rejectUnauthorized: false
        };

        function triggerRequest(cookieHeader) {
            const headers = {};
            if (cookieHeader) {
                headers['Cookie'] = cookieHeader;
            }
            
            const reqOpts = {
                headers: headers,
                rejectUnauthorized: false
            };

            https.get(url, reqOpts, (res) => {
                let data = "";
                res.on("data", (chunk) => {
                    data += chunk;
                });
                res.on("end", async () => {
                    // Check if we got challenged by Imunify360
                    if (data.indexOf('document.cookie = "') > -1) {
                        const match = data.match(/document\.cookie = "([^"]+)"/);
                        if (match && match[1] && !cookieHeader) {
                            const cookie = match[1];
                            console.log(`Detected firewall cookie challenge. Solving and retrying with cookie: ${cookie}...`);
                            triggerRequest(cookie);
                            return;
                        }
                    }

                    console.log("\n--- REMOTE SCRIPT OUTPUT ---");
                    console.log(data);
                    console.log("----------------------------\n");

                    // Cleanup
                    console.log("Connecting to FTP to delete remote script...");
                    const cleanClient = new ftp.Client();
                    try {
                        await cleanClient.access({
                            host: "ftp.todoexiste.com",
                            user: "CELZIMO@celzimoveste.cl",
                            password: "Csc170431*",
                            secure: true,
                            secureOptions: { rejectUnauthorized: false }
                        });
                        await cleanClient.remove(remoteFile);
                        console.log("✓ Remote script deleted successfully.");
                    } catch (cleanErr) {
                        console.error("Error during cleanup:", cleanErr.message);
                    }
                    cleanClient.close();
                });
            }).on("error", (err) => {
                console.error("Error triggering remote script:", err.message);
            });
        }

        triggerRequest(null);

    } catch (err) {
        console.error("Error:", err);
        client.close();
    }
}

// Get script filename from command line arguments
const scriptName = process.argv[2];
if (!scriptName) {
    console.error("Please specify the PHP script name to run, e.g. node run_php_remotely.js my_script.php");
    process.exit(1);
}
runPhpRemotely(scriptName);
