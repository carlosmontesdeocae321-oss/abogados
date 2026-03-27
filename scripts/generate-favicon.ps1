# generate-favicon.ps1
# Generates images/favicon.ico from images/logo.png using ImageMagick
# Usage: Open PowerShell in the repo root and run: .\scripts\generate-favicon.ps1

$src = "images/logo.png"
$dst = "images/favicon.ico"

if (-not (Test-Path $src)) {
    Write-Error "Source file $src not found."
    exit 1
}

# Prefer 'magick' (ImageMagick v7+). Fallback to 'convert' if available.
$magick = Get-Command magick -ErrorAction SilentlyContinue
if ($magick) {
    Write-Host "Using magick to create favicon.ico..."
    magick convert $src -define icon:auto-resize=64,48,32,16 $dst
} else {
    $convert = Get-Command convert -ErrorAction SilentlyContinue
    if ($convert) {
        Write-Host "Using convert to create favicon.ico..."
        convert $src -define icon:auto-resize=64,48,32,16 $dst
    } else {
        Write-Error "Neither 'magick' nor 'convert' found. Install ImageMagick and re-run this script."
        exit 1
    }
}

if (Test-Path $dst) {
    Write-Host "Favicon created: $dst"
} else {
    Write-Error "Failed to create favicon.ico"
}
