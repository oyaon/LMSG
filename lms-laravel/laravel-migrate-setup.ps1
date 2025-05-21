# Laravel Migration and Setup Automation Script
# Run this script in PowerShell from the lms-laravel directory

function Check-Command($cmd) {
    $exists = Get-Command $cmd -ErrorAction SilentlyContinue
    if (-not $exists) {
        Write-Host "ERROR: Required command '$cmd' is not available. Please install it and ensure it's in your PATH." -ForegroundColor Red
        exit 1
    }
}

# Check for required tools
Check-Command composer
Check-Command npm
Check-Command php

# Step 1: Install PHP dependencies
if (Test-Path composer.json) {
    Write-Host 'Installing PHP dependencies...'
    composer install
    if ($LASTEXITCODE -ne 0) { Write-Host 'composer install failed.' -ForegroundColor Red; exit 1 }
}

# Step 2: Install frontend dependencies
if (Test-Path package.json) {
    Write-Host 'Installing frontend dependencies...'
    npm install
    if ($LASTEXITCODE -ne 0) { Write-Host 'npm install failed.' -ForegroundColor Red; exit 1 }
    npm run dev
    if ($LASTEXITCODE -ne 0) { Write-Host 'npm run dev failed.' -ForegroundColor Red; exit 1 }
}

# Step 3: Copy .env.example to .env if .env does not exist
if (!(Test-Path .env) -and (Test-Path .env.example)) {
    Write-Host 'Copying .env.example to .env...'
    Copy-Item .env.example .env
    Write-Host 'Please edit .env to set your database credentials before continuing.' -ForegroundColor Yellow
    pause
}

# Step 4: Generate Laravel app key
Write-Host 'Generating Laravel app key...'
php artisan key:generate
if ($LASTEXITCODE -ne 0) { Write-Host 'php artisan key:generate failed.' -ForegroundColor Red; exit 1 }

# Step 5: Run database migrations
Write-Host 'Running database migrations...'
php artisan migrate
if ($LASTEXITCODE -ne 0) { Write-Host 'php artisan migrate failed.' -ForegroundColor Red; exit 1 }

# Step 6: Run database seeders
Write-Host 'Running database seeders...'
php artisan db:seed
if ($LASTEXITCODE -ne 0) { Write-Host 'php artisan db:seed failed.' -ForegroundColor Red; exit 1 }

# Step 7: Create storage symbolic link
Write-Host 'Creating storage symbolic link...'
php artisan storage:link
if ($LASTEXITCODE -ne 0) { Write-Host 'php artisan storage:link failed.' -ForegroundColor Red; exit 1 }

# Step 8: Run tests
Write-Host 'Running PHPUnit tests...'
php artisan test
if ($LASTEXITCODE -ne 0) { Write-Host 'php artisan test failed.' -ForegroundColor Red; exit 1 }

# Step 9: Build assets for production
Write-Host 'Building frontend assets for production...'
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host 'npm run build failed.' -ForegroundColor Red; exit 1 }

Write-Host 'All automation steps complete!' -ForegroundColor Green
