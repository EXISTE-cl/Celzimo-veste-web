$ErrorActionPreference = 'Stop'
Set-Location -Path "C:\Users\Cristobal\.gemini\antigravity\scratch\celzimo-veste"

# Create directories
New-Item -ItemType Directory -Force -Path "assets"
New-Item -ItemType Directory -Force -Path "css"
New-Item -ItemType Directory -Force -Path "js"

# Move files
Get-ChildItem -Filter *.png | Move-Item -Destination "assets\"
Get-ChildItem -Filter *.jpg | Move-Item -Destination "assets\"
Get-ChildItem -Filter *.css | Move-Item -Destination "css\"
Get-ChildItem -Filter *.js | Where-Object { $_.Name -ne 'organize.ps1' } | Move-Item -Destination "js\"

# Update HTML files
$htmlFiles = Get-ChildItem -Filter *.html
foreach ($f in $htmlFiles) {
    $content = Get-Content $f.FullName -Raw
    # Update script src
    $content = [regex]::Replace($content, 'src="([^"]+\.js)"', 'src="js/$1"')
    # Update css href
    $content = [regex]::Replace($content, 'href="([^"]+\.css)"', 'href="css/$1"')
    # Update img src (png|jpg)
    $content = [regex]::Replace($content, 'src="([^"]+\.(?:png|jpg))"', 'src="assets/$1"')
    # Update background-image url (png|jpg)
    $content = [regex]::Replace($content, 'url\(''([^'']+\.(?:png|jpg))''\)', 'url(''assets/$1'')')
    
    Set-Content -Path $f.FullName -Value $content
}

# Update JS files (for data.js images)
$jsFiles = Get-ChildItem -Path "js" -Filter *.js
foreach ($f in $jsFiles) {
    $content = Get-Content $f.FullName -Raw
    # Update image references in JS (e.g. 'jacket.png' -> 'assets/jacket.png')
    $content = [regex]::Replace($content, '''([^'']+\.(?:png|jpg))''', '''assets/$1''')
    $content = [regex]::Replace($content, '"([^"]+\.(?:png|jpg))"', '"assets/$1"')
    
    Set-Content -Path $f.FullName -Value $content
}
