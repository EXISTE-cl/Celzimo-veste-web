const https = require('https');
const fs = require('fs');

https.get('https://celzimoveste.cl/', (res) => {
    let data = '';
    res.on('data', (chunk) => { data += chunk; });
    res.on('end', () => {
        fs.writeFileSync('homepage.html', data);
        console.log("Saved to homepage.html");
    });
});
