
# ============================================================
#              DeSanur Automated Installer
# ============================================================
# OS          : Windows 10 / Windows 11
# Framework   : Laravel 13
# PHP         : 8.5
# Composer    : Stable
# Node.js     : LTS
#
# RUN:
# powershell -ExecutionPolicy Bypass -File .\setup-desanur-automated.ps1
#
# ============================================================

$ErrorActionPreference = "Stop"

# ------------------------------------------------------------
# CONFIGURATION
# ------------------------------------------------------------

$ProjectName = "DeSanur"

$RequiredPhpVersion = [version]"8.5"
$PhpPackage = "PHP.PHP.8.5"
$ComposerPackage = "Composer.Composer"
$NodePackage = "OpenJS.NodeJS.LTS"

$RequiredExtensions = @(
    "ctype",
    "curl",
    "dom",
    "fileinfo",
    "filter",
    "hash",
    "mbstring",
    "openssl",
    "pcre",
    "pdo",
    "pdo_mysql",
    "session",
    "tokenizer",
    "xml"
)

$ErrorActionPreference = "Stop"

# ------------------------------------------------------------
# HELPER FUNCTIONS
# ------------------------------------------------------------

function Write-Header($Message) {
    Write-Host ""
    Write-Host "============================================================" -ForegroundColor Cyan
    Write-Host " $Message" -ForegroundColor Cyan
    Write-Host "============================================================" -ForegroundColor Cyan
}

function Write-Info($Message) {
    Write-Host "[INFO] $Message" -ForegroundColor Cyan
}

function Write-OK($Message) {
    Write-Host "[READY] $Message" -ForegroundColor Green
}

function Write-Warn($Message) {
    Write-Host "[WARNING] $Message" -ForegroundColor Yellow
}

function Write-ErrorMessage($Message) {
    Write-Host "[ERROR] $Message" -ForegroundColor Red
}

function Test-Command($Name) {
    return [bool](Get-Command $Name -ErrorAction SilentlyContinue)
}

function Refresh-Path {

    $machinePath = [Environment]::GetEnvironmentVariable(
        "Path",
        "Machine"
    )

    $userPath = [Environment]::GetEnvironmentVariable(
        "Path",
        "User"
    )

    $env:Path = "$machinePath;$userPath"
}

function Get-CommandVersion($Command, $Arguments) {

    if (-not (Test-Command $Command)) {
        return $null
    }

    try {

        $result = & $Command $Arguments 2>&1 |
            Select-Object -First 1

        return "$result".Trim()

    }
    catch {

        return $null

    }
}

function Get-PhpVersion {

    if (-not (Test-Command "php")) {
        return $null
    }

    try {

        $versionText = (& php -r "echo PHP_VERSION;" 2>&1).Trim()

        $versionCore = ($versionText -split "[-+]")[0]

        return [version]$versionCore

    }
    catch {

        return $null

    }
}

function Install-WingetPackage($PackageId) {

    Write-Info "Memproses paket $PackageId"

    & winget install `
        --id $PackageId `
        --exact `
        --source winget `
        --accept-package-agreements `
        --accept-source-agreements

    if ($LASTEXITCODE -ne 0) {

        Write-Warn "Instalasi gagal atau paket sudah terpasang. Mencoba upgrade."

        & winget upgrade `
            --id $PackageId `
            --exact `
            --source winget `
            --accept-package-agreements `
            --accept-source-agreements

        if ($LASTEXITCODE -ne 0) {
            throw "Tidak dapat memasang atau memperbarui $PackageId"
        }
    }
}

function Test-ProjectFiles {

    $requiredFiles = @(
        ".\artisan",
        ".\composer.json",
        ".\composer.lock",
        ".\package.json",
        ".\package-lock.json",
        ".\.env.example"
    )

    foreach ($file in $requiredFiles) {

        if (-not (Test-Path $file)) {

            throw "File project tidak ditemukan: $file"

        }
    }

    Write-OK "Struktur project DeSanur valid."
}

# ------------------------------------------------------------
# SMART COMPONENT DETECTION
# ------------------------------------------------------------

function Show-ComponentStatus {

    Write-Header "SMART COMPONENT DETECTION"

    $phpVersion = Get-PhpVersion

    $composerVersion = Get-CommandVersion `
        "composer" "--version"

    $nodeVersion = Get-CommandVersion `
        "node" "--version"

    $npmVersion = Get-CommandVersion `
        "npm" "--version"

    $allReady = $true

    # PHP
    if ($null -ne $phpVersion -and $phpVersion -ge $RequiredPhpVersion) {

        Write-OK "PHP $phpVersion - sesuai."

    }
    else {

        Write-Warn "PHP belum tersedia atau versinya belum sesuai."

        $allReady = $false

    }

    # Composer
    if ($composerVersion) {

        Write-OK "Composer $composerVersion - tersedia."

    }
    else {

        Write-Warn "Composer belum tersedia."

        $allReady = $false

    }

    # Node
    if ($nodeVersion) {

        Write-OK "Node.js $nodeVersion - tersedia."

    }
    else {

        Write-Warn "Node.js belum tersedia."

        $allReady = $false

    }

    # npm
    if ($npmVersion) {

        Write-OK "npm $npmVersion - tersedia."

    }
    else {

        Write-Warn "npm belum tersedia."

        $allReady = $false

    }

    return $allReady
}

# ------------------------------------------------------------
# INSTALL REQUIRED COMPONENTS
# ------------------------------------------------------------

function Install-RequiredComponents {

    Write-Header "INSTALLING REQUIRED COMPONENTS"

    # PHP
    $phpVersion = Get-PhpVersion

    if ($null -eq $phpVersion -or $phpVersion -lt $RequiredPhpVersion) {

        Write-Warn "PHP belum sesuai. Memasang PHP 8.5."

        Install-WingetPackage $PhpPackage

    }
    else {

        Write-OK "PHP sudah sesuai. Instalasi dilewati."

    }

    # Composer
    if (-not (Test-Command "composer")) {

        Write-Warn "Composer belum tersedia."

        Install-WingetPackage $ComposerPackage

    }
    else {

        Write-OK "Composer sudah tersedia. Instalasi dilewati."

    }

    # Node.js
    if (-not (Test-Command "node") -or -not (Test-Command "npm")) {

        Write-Warn "Node.js LTS atau npm belum tersedia."

        Install-WingetPackage $NodePackage

    }
    else {

        Write-OK "Node.js dan npm sudah tersedia. Instalasi dilewati."

    }

    Refresh-Path

    # Check after installation
    foreach ($tool in @("php", "composer", "node", "npm")) {

        if (-not (Test-Command $tool)) {

            throw "$tool belum dikenali. Tutup PowerShell, buka kembali, lalu jalankan installer."

        }
    }
}

# ------------------------------------------------------------
# PHP VALIDATION
# ------------------------------------------------------------

function Test-PhpEnvironment {

    Write-Header "PHP VERSION AND EXTENSION CHECK"

    $phpVersion = Get-PhpVersion

    if ($null -eq $phpVersion) {

        throw "PHP tidak dapat ditemukan."

    }

    if ($phpVersion -lt $RequiredPhpVersion) {

        throw "Versi PHP aktif $phpVersion tidak sesuai. DeSanur membutuhkan PHP 8.5."

    }

    Write-OK "PHP $phpVersion kompatibel."

    $loadedExtensions = @(
        & php -m | ForEach-Object {
            $_.Trim().ToLowerInvariant()
        }
    )

    $missingExtensions = @(
        $RequiredExtensions | Where-Object {
            $_ -notin $loadedExtensions
        }
    )

    if ($missingExtensions.Count -gt 0) {

        Write-Warn "Ekstensi PHP belum aktif:"

        foreach ($extension in $missingExtensions) {

            Write-Host " - $extension" -ForegroundColor Yellow

        }

        php --ini

        throw "Aktifkan ekstensi PHP di php.ini yang digunakan oleh PHP CLI, lalu jalankan kembali installer."

    }

    Write-OK "Seluruh ekstensi PHP wajib tersedia."
}

# ------------------------------------------------------------
# COMPOSER VALIDATION
# ------------------------------------------------------------

function Update-Composer {

    Write-Header "COMPOSER VALIDATION"

    composer --version

    Write-Info "Memeriksa Composer stable."

    composer self-update --stable

    if ($LASTEXITCODE -ne 0) {

        throw "Composer self-update gagal."

    }

    Write-OK "Composer siap digunakan."
}

# ------------------------------------------------------------
# PHP DEPENDENCIES
# ------------------------------------------------------------

function Install-PhpDependencies {

    Write-Header "INSTALLING PHP DEPENDENCIES"

    Write-Info "Menggunakan composer.lock agar versi tim tetap konsisten."

    composer install --prefer-dist --no-interaction

    if ($LASTEXITCODE -ne 0) {

        throw "Composer install gagal."

    }

    Write-OK "Dependency PHP berhasil dipasang."
}

# ------------------------------------------------------------
# JAVASCRIPT DEPENDENCIES
# ------------------------------------------------------------

function Install-JsDependencies {

    Write-Header "INSTALLING JAVASCRIPT DEPENDENCIES"

    Write-Info "Menggunakan package-lock.json."

    npm ci

    if ($LASTEXITCODE -ne 0) {

        Write-Host ""
        Write-Warn "npm ci gagal."

        Write-Host "Kemungkinan penyebab:"
        Write-Host "- File node_modules sedang digunakan."
        Write-Host "- VS Code atau Vite masih berjalan."
        Write-Host "- Antivirus mengunci file."

        throw "Tutup aplikasi yang menggunakan Node.js, kemudian jalankan ulang installer."

    }

    Write-OK "Dependency JavaScript berhasil dipasang."
}

# ------------------------------------------------------------
# ENVIRONMENT SETUP
# ------------------------------------------------------------

function Setup-Environment {

    Write-Header "ENVIRONMENT SETUP"

    if (-not (Test-Path ".\.env")) {

        Copy-Item ".\.env.example" ".\.env"

        Write-OK "File .env berhasil dibuat."

    }
    else {

        Write-OK ".env sudah tersedia. Tidak ditimpa."

    }

    $envContent = Get-Content ".\.env" -Raw

    if ($envContent -match '(?m)^APP_KEY=\s*$') {

        php artisan key:generate --force

        if ($LASTEXITCODE -ne 0) {

            throw "Gagal membuat APP_KEY."

        }

        Write-OK "APP_KEY berhasil dibuat."

    }
    else {

        Write-OK "APP_KEY sudah tersedia."

    }

    php artisan config:clear

    if ($LASTEXITCODE -ne 0) {

        throw "Gagal membersihkan konfigurasi Laravel."

    }

    Write-OK "Konfigurasi Laravel siap."
}

# ------------------------------------------------------------
# DATABASE INFORMATION
# ------------------------------------------------------------

function Show-DatabaseInformation {

    Write-Header "DATABASE CONFIGURATION"

    Write-Host ""
    Write-Host "Periksa file .env:"
    Write-Host ""
    Write-Host "DB_CONNECTION=mysql"
    Write-Host "DB_HOST=127.0.0.1"
    Write-Host "DB_PORT=3306 atau 3307"
    Write-Host "DB_DATABASE=desanur"
    Write-Host "DB_USERNAME=root"
    Write-Host "DB_PASSWORD="
    Write-Host ""

    Write-Warn "Pastikan MySQL/MariaDB sudah berjalan."
    Write-Warn "Pastikan database desanur sudah dibuat."
    Write-Warn "Installer tidak mengubah database atau menjalankan migration otomatis."
}

# ------------------------------------------------------------
# MAIN EXECUTION
# ------------------------------------------------------------

try {

    Clear-Host

    Write-Host @"

============================================================
                 DeSanur Automated Installer
============================================================

  Laravel       : 13
  PHP           : 8.5
  Composer      : Stable
  Node.js       : LTS
  Frontend      : Vue + Livewire + Tailwind CSS
  Map           : Leaflet + OpenStreetMap

============================================================

"@ -ForegroundColor Green

    # Step 1
    Write-Header "STEP 1 - PROJECT VALIDATION"

    Test-ProjectFiles

    # Step 2
    Write-Header "STEP 2 - WINGET VALIDATION"

    if (-not (Test-Command "winget")) {

        throw "WinGet tidak ditemukan. Instal App Installer dari Microsoft Store."

    }

    winget --version

    # Step 3
    Write-Header "STEP 3 - CHECK INSTALLED COMPONENTS"

    $initialStatus = Show-ComponentStatus

    if ($initialStatus) {

        Write-Host ""
        Write-Host "Semua komponen utama sudah terdeteksi." -ForegroundColor Green

    }

    # Step 4
    Install-RequiredComponents

    # Step 5
    Test-PhpEnvironment

    # Step 6
    Update-Composer

    # Step 7
    Install-PhpDependencies

    # Step 8
    Install-JsDependencies

    # Step 9
    Setup-Environment

    # Step 10
    Show-DatabaseInformation

    # Finish
    Write-Header "DESANUR SETUP COMPLETED"

    Write-Host ""
    Write-Host "Semua dependency project berhasil dipersiapkan." -ForegroundColor Green

    Write-Host ""
    Write-Host "Untuk menjalankan DeSanur:"
    Write-Host ""
    Write-Host "Terminal 1: php artisan serve" -ForegroundColor Cyan
    Write-Host "Terminal 2: npm run dev" -ForegroundColor Cyan

    Write-Host ""
    Write-Host "URL: http://127.0.0.1:8000" -ForegroundColor Green

    Write-Host ""
    Write-Host "============================================================"
    Write-Host "        DE SANUR AUTOMATED INSTALLER SUCCESS"
    Write-Host "============================================================" -ForegroundColor Green

}
catch {

    Write-Host ""
    Write-ErrorMessage $_.Exception.Message

    Write-Host ""
    Write-Warn "Setup dihentikan. Perbaiki masalah di atas lalu jalankan kembali."

    exit 1
}
