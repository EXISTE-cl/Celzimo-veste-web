const ftp = require("basic-ftp");
const fs = require("fs");
const path = require("path");

async function testUpload() {
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        await client.access({
            host: "ftp.todoexiste.com",
            user: "ASISTENTE@todoexiste.com",
            password: "Cm31103110*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });
        
        const testFilePath = path.join(__dirname, "test_upload_file.txt");
        fs.writeFileSync(testFilePath, "FTP UPLOAD TEST SUCCEEDED - " + new Date().toISOString());

        console.log("Uploading test file to /public_html/test_upload_file.txt...");
        try {
            await client.uploadFrom(testFilePath, "/public_html/test_upload_file.txt");
            console.log("Uploaded to /public_html/test_upload_file.txt");
        } catch (e) {
            console.log("Failed to upload to /public_html:", e.message);
        }

        console.log("Uploading test file to /test_upload_file.txt...");
        try {
            await client.uploadFrom(testFilePath, "/test_upload_file.txt");
            console.log("Uploaded to /test_upload_file.txt");
        } catch (e) {
            console.log("Failed to upload to /:", e.message);
        }

        console.log("Uploading test file to /existe-demo/test_upload_file.txt...");
        try {
            await client.uploadFrom(testFilePath, "/existe-demo/test_upload_file.txt");
            console.log("Uploaded to /existe-demo/test_upload_file.txt");
        } catch (e) {
            console.log("Failed to upload to /existe-demo:", e.message);
        }

    } catch (err) {
        console.error("Error:", err);
    }
    client.close();
}

testUpload();
