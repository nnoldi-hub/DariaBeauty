# Script pentru deschiderea paginilor de servicii în browser

$baseUrl = "http://localhost/Daria-Beauty/dariabeauty/public"

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "     Deschidere pagini servicii DariaBeauty    " -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Deschid paginile în browser..." -ForegroundColor Yellow
Write-Host ""

# Pagina de test
Write-Host "1. Pagina de test status..." -ForegroundColor White
Start-Process "$baseUrl/test-servicii.html"
Start-Sleep -Seconds 2

# Toate serviciile
Write-Host "2. Toate serviciile..." -ForegroundColor White
Start-Process "$baseUrl/servicii"
Start-Sleep -Seconds 2

# dariaNails
Write-Host "3. dariaNails..." -ForegroundColor Magenta
Start-Process "$baseUrl/darianails"
Start-Sleep -Seconds 2

# dariaHair
Write-Host "4. dariaHair..." -ForegroundColor Blue
Start-Process "$baseUrl/dariahair"
Start-Sleep -Seconds 2

# dariaGlow
Write-Host "5. dariaGlow..." -ForegroundColor Yellow
Start-Process "$baseUrl/dariaglow"

Write-Host ""
Write-Host "================================================" -ForegroundColor Green
Write-Host "     Toate paginile au fost deschise!           " -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Green
Write-Host ""
Write-Host "Verifică tab-urile din browser pentru a vedea serviciile!" -ForegroundColor White
Write-Host ""
