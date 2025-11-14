# Script pentru popularea bazei de date cu servicii pentru fiecare sub-brand

Write-Host "====================================" -ForegroundColor Cyan
Write-Host "  Populare servicii DariaBeauty" -ForegroundColor Cyan
Write-Host "====================================" -ForegroundColor Cyan
Write-Host ""

# Navigate to project directory
Set-Location "c:\wamp64\www\Daria-Beauty\dariabeauty"

Write-Host "1. Verificare seeders existenti..." -ForegroundColor Yellow
php artisan db:seed --class=SpecialistUserSeeder
Write-Host "Specialisti creati!" -ForegroundColor Green

Write-Host ""
Write-Host "2. Populare servicii..." -ForegroundColor Yellow
php artisan db:seed --class=ServicesSeeder
Write-Host "Servicii create!" -ForegroundColor Green

Write-Host ""
Write-Host "3. Verificare servicii create..." -ForegroundColor Yellow
php artisan tinker --execute="echo 'dariaNails: ' . App\Models\Service::where('sub_brand', 'dariaNails')->count() . ' servicii'; echo PHP_EOL; echo 'dariaHair: ' . App\Models\Service::where('sub_brand', 'dariaHair')->count() . ' servicii'; echo PHP_EOL; echo 'dariaGlow: ' . App\Models\Service::where('sub_brand', 'dariaGlow')->count() . ' servicii';"

Write-Host ""
Write-Host "====================================" -ForegroundColor Cyan
Write-Host "  Succes! Serviciile sunt gata!" -ForegroundColor Green
Write-Host "====================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Acum poti accesa:" -ForegroundColor White
Write-Host "  - http://localhost/Daria-Beauty/dariabeauty/public/servicii" -ForegroundColor Cyan
Write-Host "  - http://localhost/Daria-Beauty/dariabeauty/public/darianails" -ForegroundColor Magenta
Write-Host "  - http://localhost/Daria-Beauty/dariabeauty/public/dariahair" -ForegroundColor Blue  
Write-Host "  - http://localhost/Daria-Beauty/dariabeauty/public/dariaglow" -ForegroundColor Yellow
Write-Host ""
