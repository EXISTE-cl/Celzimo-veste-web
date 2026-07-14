const ftp = require("basic-ftp");
const crypto = require("crypto");

// Decryption function for CoreFTP password in .coreftp file
// The ciphertext is usually a hex string. Let's try to decrypt it if we find the encoded string.
// Let's write a script to look inside CELZIMO@celzimoveste.cl.coreftp for the PW field value
// and decrypt it using AES-128-ECB with key "hdfzpysvpzimorhk".

const fs = require("fs");

function decryptCoreFTP(ciphertextHex) {
    try {
        const key = Buffer.from("hdfzpysvpzimorhk", "utf8");
        const cipherbytes = Buffer.from(ciphertextHex, "hex");
        const decipher = crypto.createDecipheriv("aes-128-ecb", key, null);
        decipher.setAutoPadding(false);
        let decrypted = decipher.update(cipherbytes);
        decrypted = Buffer.concat([decrypted, decipher.final()]);
        // Clean padding / null bytes
        return decrypted.toString("utf8").replace(/\0/g, "").trim();
    } catch (e) {
        return "Error decrypting: " + e.message;
    }
}

// Let's read the .coreftp file
const filePath = "C:\\Users\\Cristobal\\Desktop\\empresa de jean\\CELZIMO@celzimoveste.cl.coreftp";
if (fs.existsSync(filePath)) {
    const lines = fs.readFileSync(filePath, "utf8").split("\n");
    let encodedPw = "";
    for (const line of lines) {
        if (line.startsWith("PW,")) {
            encodedPw = line.substring(3).trim();
            break;
        }
    }
    
    if (encodedPw) {
        console.log("Encoded PW found in .coreftp:", encodedPw);
        const decrypted = decryptCoreFTP(encodedPw);
        console.log("Decrypted password:", decrypted);
    } else {
        console.log("PW field is empty in the .coreftp file.");
    }
} else {
    console.log(".coreftp file not found.");
}
