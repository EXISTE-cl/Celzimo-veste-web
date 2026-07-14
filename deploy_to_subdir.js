const ftp = require("basic-ftp");
const path = require("path");

async function deployToSubdir() {
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
        
        console.log("Conectado exitosamente. Subiendo directorio a /_archivo-html-2026-07-03...");
        
        // Subir todo el directorio local al destino en el FTP
        await client.uploadFromDir(__dirname, "/_archivo-html-2026-07-03", {
            ignore: (relativePath) => {
                const parts = relativePath.split(/[/\\]/);
                const ignoreList = [
                    'node_modules', 
                    '.git', 
                    '.agents', 
                    '.gitignore', 
                    'package.json', 
                    'package-lock.json', 
                    'deploy.js', 
                    'README.md',
                    'upload_fixes.js',
                    'upload_fixes_scratch.js',
                    'deploy_to_subdir.js',
                    'brute_celzimo.js',
                    'check_domains.js',
                    'check_domains_on_ip.js',
                    'check_remote_folders.js',
                    'check_subdir_files.js',
                    'check_subfolders.js',
                    'decrypt_coreftp.js',
                    'download_root_index.js',
                    'find_folder.js',
                    'get_deploy.js',
                    'list_archive.js',
                    'list_celzimo_ftp.js',
                    'list_celzimo_on_todoexiste.js',
                    'list_css.js',
                    'list_dist.js',
                    'list_ftp.js',
                    'list_public_html_unsecure.js',
                    'list_real_archive.js',
                    'list_real_celzimo.js',
                    'list_root.js',
                    'locate_product_html.js',
                    'remove_options_everywhere.js',
                    'search_all_dirs.js',
                    'test_cpanel_ftp.js',
                    'test_host.js',
                    'test_regex.js',
                    'test_upload.js',
                    'test_upload_file.txt',
                    'upload_info.js',
                    'sync-system',
                    'logs'
                ];
                return parts.some(part => ignoreList.includes(part));
            }
        });
        
        console.log("¡Despliegue completo en /_archivo-html-2026-07-03 finalizado con éxito!");
    } catch(err) {
        console.error("Error durante el despliegue:", err);
    }
    client.close();
}

deployToSubdir();
