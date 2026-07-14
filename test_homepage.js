const https = require("https");
const path = require("path");
const fs = require("fs");

const requestOptions = {
    rejectUnauthorized: false
};

https.get("https://celzimoveste.cl/?nocache=1", requestOptions, (res) => {
    let data = "";
    res.on("data", (chunk) => {
        data += chunk;
    });
    res.on("end", () => {
        console.log("Status Code:", res.statusCode);
        console.log("Headers:", res.headers);
        fs.writeFileSync(path.join(__dirname, "homepage_output.html"), data, "utf8");
        console.log("HTML guardado en homepage_output.html. Longitud:", data.length);
    });
}).on("error", (err) => {
    console.error("Error al consultar la raíz:", err.message);
});
