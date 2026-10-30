# DeSanur Team Setup Script
# Jalankan dari folder root project: .\setup-desanur.ps1

$ErrorActionPreference = "Stop"

function Write-Step($Message) {
    Write-Host "`n==> $Message" -ForegroundColor Cyan
}

function Test-Command($Name) {
    return [bool](Get-Command $Name -ErrorAction SilentlyContinue)
}

Write-Host "======================================" -ForegroundColor Green
Write-Host "       DeSanur - Team Setup           " -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Green

if (-not (Test-Path ".\artisan") -or -not (Test-Path ".\composer.json") -or -not (Test-Path ".\package.json")) {
    Write-Host "ERROR: Jalankan script dari folder utama DeSanur (folder yang berisi artisan)." -ForegroundColor Red
    exit 1
}

if (-not (Test-Path ".\composer.lock")) {
    Write-Host "ERROR: composer.lock tidak ditemukan. Maintainer harus menjalankan composer update lalu commit composer.lock." -ForegroundColor Red
    exit 1
}
if (-not (Test-Path ".\package-lock.json")) {
    Write-Host "ERROR: package-lock.json tidak ditemukan. Maintainer harus menjalankan npm install lalu commit package-lock.json." -ForegroundColor Red
    exit 1
}

Write-Step "Memeriksa tools yang dibutuhkan"
foreach ($tool in @("php", "composer", "node", "npm")) {
    if (-not (Test-Command $tool)) {
        Write-Host "ERROR: $tool belum terpasang atau belum masuk PATH. Install dahulu, lalu restart terminal VS Code." -ForegroundColor Red
        exit 1
    }
}

php --version
composer --version
node --version
npm --version

Write-Step "Memeriksa versi PHP dan ekstensi database"
$phpVersion = (& php -r "echo PHP_VERSION;").Trim()
$phpMajorMinor = [version]($phpVersion.Split("-")[0])
if ($phpMajorMinor -lt [version]"8.3") {
    Write-Host "ERROR: Laravel 13 memerlukan PHP 8.3 atau lebih baru. PHP terdeteksi: $phpVersion" -ForegroundColor Red
    exit 1
}

$extensions = @("ctype", "curl", "dom", "fileinfo", "filter", "hash", "mbstring", "openssl", "pcre", "pdo", "session", "tokenizer", "xml", "pdo_mysql")
$loadedExtensions = (& php -m) | ForEach-Object { $_.Trim().ToLowerInvariant() }
$missingExtensions = @($extensions | Where-Object { $_ -notin $loadedExtensions })
if ($missingExtensions.Count -gt 0) {
    Write-Host "PERINGATAN: Ekstensi PHP berikut belum terdeteksi: $($missingExtensions -join ', ')" -ForegroundColor Yellow
    Write-Host "Aktifkan ekstensi tersebut pada php.ini yang digunakan terminal PHP." -ForegroundColor Yellow
}

Write-Step "Memasang dependency PHP sesuai composer.lock"
composer install --prefer-dist --no-interaction
if ($LASTEXITCODE -ne 0) { throw "composer install gagal." }

Write-Step "Memasang dependency JavaScript sesuai package-lock.json"
npm ci
if ($LASTEXITCODE -ne 0) { throw "npm ci gagal." }

Write-Step "Menyiapkan file environment"
if (-not (Test-Path ".\.env")) {
    Copy-Item ".\.env.example" ".\.env"
    Write-Host "File .env dibuat dari .env.example." -ForegroundColor Green
} else {
    Write-Host "File .env sudah ada, tidak ditimpa." -ForegroundColor Yellow
}

$envText = Get-Content ".\.env" -Raw
if ($envText -match '(?m)^APP_KEY=\s*$') {
    php artisan key:generate --force
    if ($LASTEXITCODE -ne 0) { throw "Gagal membuat APP_KEY." }
} else {
    Write-Host "APP_KEY sudah terisi; tidak diubah." -ForegroundColor Green
}

Write-Step "Membersihkan cache konfigurasi"
php artisan config:clear
if ($LASTEXITCODE -ne 0) { throw "config:clear gagal." }

Write-Host "`nSetup dependency selesai." -ForegroundColor Green
Write-Host "PENTING: Periksa file .env dan sesuaikan DB_PORT dengan MySQL/MariaDB lokal (misalnya 3306 atau 3307)." -ForegroundColor Yellow
Write-Host "Pastikan database bernama 'desanur' sudah dibuat sebelum menjalankan migration." -ForegroundColor Yellow

$runMigrations = Read-Host "Jalankan php artisan migrate sekarang? (y/n)"
if ($runMigrations -match '^(y|yes)$') {
    php artisan migrate
    if ($LASTEXITCODE -ne 0) { throw "Migration gagal. Periksa koneksi database pada .env." }
}

Write-Host "`nSelesai! Jalankan aplikasi dengan dua terminal:" -ForegroundColor Green
Write-Host "Terminal 1: php artisan serve" -ForegroundColor White
Write-Host "Terminal 2: npm run dev" -ForegroundColor White
