const https = require('https');

https.get('https://celzimoveste.cl/carrito/', { rejectUnauthorized: false }, (res) => {
    let data = '';
    res.on('data', (chunk) => { data += chunk; });
    res.on('end', () => {
        console.log("Includes wrapper:", data.includes('celzimo-free-shipping-progress-wrapper'));
        console.log("Includes progress:", data.includes('cz-free-shipping-progress'));
        
        // Output around where the wrapper is
        const idx = data.indexOf('celzimo-free-shipping-progress-wrapper');
        if (idx !== -1) {
            console.log("\nSnippet around progress bar:");
            console.log(data.substring(idx - 100, idx + 500));
        } else {
            console.log("Not found.");
        }
    });
});
