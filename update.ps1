$ErrorActionPreference = 'Stop'
Set-Location -Path "C:\Users\Cristobal\.gemini\antigravity\scratch\celzimo-veste"

# 1. Create shop.html from index.html
Copy-Item index.html shop.html -Force

# 2. Modify shop.html
$shop = Get-Content shop.html -Raw
# Remove hero section
$shop = [regex]::Replace($shop, '(?s)<!-- Hero Section -->.*?<!-- Products Catalog Section -->', '<!-- Products Catalog Section -->')
# Remove instagram section
$shop = [regex]::Replace($shop, '(?s)<!-- Instagram Feed Section -->.*?<!-- Footer -->', '<!-- Footer -->')
# Fix title
$shop = [regex]::Replace($shop, '<title>CELZIMO VESTE \| Ropa y Accesorios Premium</title>', '<title>Tienda | CELZIMO VESTE</title>')
Set-Content shop.html -Value $shop

# 3. Modify index.html
$index = Get-Content index.html -Raw
$index = [regex]::Replace($index, '(?s)<!-- Products Catalog Section -->.*?<!-- Instagram Feed Section -->', '<!-- Instagram Feed Section -->')
Set-Content index.html -Value $index

# 4. Replace links in all HTML files
$files = Get-ChildItem -Filter *.html
foreach ($f in $files) {
    $content = Get-Content $f.FullName -Raw
    $content = [regex]::Replace($content, 'href="index\.html#productos"( target="_blank")?( class="mega-image-card"(?:\s|\n|\r)*data-category="([^"]+)")', 'href="shop.html?category=$3"$1$2')
    Set-Content -Path $f.FullName -Value $content
}
