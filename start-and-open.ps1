# Script pentru pornirea serverului Laravel și deschiderea brandurilor

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "     DariaBeauty - Start Development Server    " -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Set location
Set-Location "c:\wamp64\www\Daria-Beauty\dariabeauty"

Write-Host "Verific dacă serverul rulează deja..." -ForegroundColor Yellow

# Check if port 8000 is already in use
$port = 8000
$portInUse = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue

if ($portInUse) {
    Write-Host "Serverul rulează deja pe portul $port!" -ForegroundColor Green
} else {
    Write-Host "Pornesc Laravel Development Server..." -ForegroundColor Yellow
    
    # Start Laravel server in background
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd c:\wamp64\www\Daria-Beauty\dariabeauty; php artisan serve"
    
    Write-Host "Aștept 3 secunde pentru pornirea serverului..." -ForegroundColor Yellow
    Start-Sleep -Seconds 3
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Green
Write-Host "     Serverul este gata! Deschid paginile...   " -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Green
Write-Host ""

# Base URL
$baseUrl = "http://127.0.0.1:8000"

# Open pages
Write-Host "1. Deschid Homepage..." -ForegroundColor White
Start-Process "$baseUrl"
Start-Sleep -Seconds 2

Write-Host "2. Deschid Toate Serviciile..." -ForegroundColor White
Start-Process "$baseUrl/servicii"
Start-Sleep -Seconds 2

Write-Host "3. Deschid dariaNails..." -ForegroundColor Magenta
Start-Process "$baseUrl/darianails"
Start-Sleep -Seconds 2

Write-Host "4. Deschid dariaHair..." -ForegroundColor Blue
Start-Process "$baseUrl/dariahair"
Start-Sleep -Seconds 2

Write-Host "5. Deschid dariaGlow..." -ForegroundColor Yellow
Start-Process "$baseUrl/dariaglow"
Start-Sleep -Seconds 2

Write-Host "6. Deschid Galeria..." -ForegroundColor Green
Start-Process "$baseUrl/galerie"

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "     Toate paginile au fost deschise!           " -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "URL-uri disponibile:" -ForegroundColor White
Write-Host "  • Homepage:    $baseUrl" -ForegroundColor Cyan
Write-Host "  • Servicii:    $baseUrl/servicii" -ForegroundColor Cyan
Write-Host "  • dariaNails:  $baseUrl/darianails" -ForegroundColor Magenta
Write-Host "  • dariaHair:   $baseUrl/dariahair" -ForegroundColor Blue
Write-Host "  • dariaGlow:   $baseUrl/dariaglow" -ForegroundColor Yellow
Write-Host "  • Galerie:     $baseUrl/galerie" -ForegroundColor Green
Write-Host ""
Write-Host "Pentru a opri serverul, închide fereastra PowerShell cu serverul." -ForegroundColor Yellow
Write-Host ""
