const fs = require("fs");
const path = require("path");
const ftp = require("basic-ftp");
const https = require("https");

function extractPolicyContent(filename) {
    const filePath = path.join(__dirname, filename);
    if (!fs.existsSync(filePath)) {
        console.error(`File ${filename} does not exist.`);
        return "";
    }
    const html = fs.readFileSync(filePath, "utf8");
    // Extract content inside <div class="policy-content">...</div>
    const match = html.match(/<div class="policy-content">([\s\S]*?)<\/div>\s*<\/article>/);
    if (match && match[1]) {
        return match[1].trim();
    }
    console.warn(`Could not extract policy content from ${filename}`);
    return "";
}

async function run() {
    console.log("Extracting content from local HTML files...");
    const devGarantiasContent = extractPolicyContent("devoluciones-y-garantias.html");
    const enviosContent = extractPolicyContent("politica-envios.html");
    const privacidadContent = extractPolicyContent("politica-privacidad.html");
    const terminosContent = extractPolicyContent("terminos-y-condiciones.html");

    const contactoContent = `
<p>Si tienes alguna consulta, necesitas ayuda con tu compra o quieres realizar el seguimiento de tu pedido, no dudes en ponerte en contacto con nosotros.</p>
<p>Escríbenos directamente a nuestro correo electrónico de atención al cliente:</p>
<div style="background-color: #fafbfc; padding: 25px; text-align: center; border-radius: 4px; margin: 30px 0; border: 1px solid #e5e7eb;">
    <a href="mailto:contacto@celzimoveste.cl" style="font-size: 1.3rem; font-weight: 500; color: #000; text-decoration: none; letter-spacing: 0.5px;">contacto@celzimoveste.cl</a>
</div>
<p>Nuestro horario de atención es de Lunes a Viernes de 9:00 a 18:30 horas. Te responderemos en un plazo máximo de 24 horas hábiles.</p>
`;

    // Generate PHP script content
    const phpCode = `<?php
/**
 * Script para crear las páginas institucionales en WordPress
 */
require_once(__DIR__ . '/wp-load.php');

header('Content-Type: application/json');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Pages') {
    die(json_encode(['success' => false, 'error' => 'No autorizado']));
}

$pages_to_create = [
    [
        'title' => 'Devoluciones y Garantías',
        'slug' => 'devoluciones-y-garantias',
        'content' => <<<'EOT'
${devGarantiasContent}
EOT
    ],
    [
        'title' => 'Política de Envíos',
        'slug' => 'politica-envios',
        'content' => <<<'EOT'
${enviosContent}
EOT
    ],
    [
        'title' => 'Política de Privacidad',
        'slug' => 'politica-privacidad',
        'content' => <<<'EOT'
${privacidadContent}
EOT
    ],
    [
        'title' => 'Términos y Condiciones',
        'slug' => 'terminos-y-condiciones',
        'content' => <<<'EOT'
${terminosContent}
EOT
    ],
    [
        'title' => 'Necesitas Ayuda',
        'slug' => 'contacto',
        'content' => <<<'EOT'
${contactoContent}
EOT
    ]
];

$results = [];

foreach ($pages_to_create as $page_data) {
    // Buscar si ya existe la página por slug
    $existing_page = get_page_by_path($page_data['slug']);
    
    $post_data = [
        'post_title'    => $page_data['title'],
        'post_content'  => $page_data['content'],
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => $page_data['slug'],
    ];
    
    if ($existing_page) {
        // Actualizar página existente
        $post_data['ID'] = $existing_page->ID;
        $res = wp_update_post($post_data);
        $results[$page_data['slug']] = [
            'status' => 'updated',
            'id' => $res
        ];
    } else {
        // Crear página nueva
        $res = wp_insert_post($post_data);
        $results[$page_data['slug']] = [
            'status' => 'created',
            'id' => $res
        ];
    }
}

echo json_encode([
    'success' => true,
    'results' => $results
], JSON_PRETTY_PRINT);
`;

    const localPhpFile = path.join(__dirname, "create_pages.php");
    fs.writeFileSync(localPhpFile, phpCode, "utf8");
    console.log("Generated local create_pages.php");

    // Upload via FTP
    const client = new ftp.Client();
    client.ftp.verbose = true;
    try {
        console.log("Conectando al FTP...");
        await client.access({
            host: "ftp.todoexiste.com",
            user: "CELZIMO@celzimoveste.cl",
            password: "Csc170431*",
            secure: true,
            secureOptions: { rejectUnauthorized: false }
        });

        console.log("Subiendo create_pages.php al servidor...");
        await client.uploadFrom(localPhpFile, "/create_pages.php");
        console.log("Subido con éxito.");
        client.close();

        // Trigger execution via HTTPS
        console.log("Gatillando creación de páginas en el servidor...");
        const url = "https://celzimoveste.cl/create_pages.php?token=Csc170431Pages";
        
        const requestOptions = {
            rejectUnauthorized: false
        };

        https.get(url, requestOptions, (res) => {
            let data = "";
            res.on("data", chunk => data += chunk);
            res.on("end", async () => {
                console.log("--- RESPUESTA DEL SERVIDOR ---");
                console.log(data);
                console.log("------------------------------");

                // Clean up local php file
                if (fs.existsSync(localPhpFile)) {
                    fs.unlinkSync(localPhpFile);
                }

                // Clean up remote php file
                console.log("Conectando al FTP para limpieza...");
                const cleanClient = new ftp.Client();
                try {
                    await cleanClient.access({
                        host: "ftp.todoexiste.com",
                        user: "CELZIMO@celzimoveste.cl",
                        password: "Csc170431*",
                        secure: true,
                        secureOptions: { rejectUnauthorized: false }
                    });
                    await cleanClient.remove("/create_pages.php");
                    console.log("✓ Archivo create_pages.php eliminado de forma segura.");
                } catch (e) {
                    console.error("Error al limpiar:", e.message);
                }
                cleanClient.close();
            });
        }).on("error", (err) => {
            console.error("Error al gatillar:", err.message);
        });

    } catch (e) {
        console.error("Error FTP:", e.message);
        client.close();
    }
}

run();
