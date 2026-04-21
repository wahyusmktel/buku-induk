@echo off
setlocal
SET ROOT=%~dp0..
SET INSTALLER=%~dp0
SET ISCC=""

echo ============================================
echo   Build Installer - Buku Induk
echo ============================================
echo.

REM --- Check PHP portable ---
if not exist "%INSTALLER%php\php.exe" (
    echo [ERROR] PHP portable tidak ditemukan di installer\php\
    echo.
    echo Langkah:
    echo 1. Download PHP 8.3 NTS dari: https://windows.php.net/download/
    echo    Pilih: PHP 8.3 Non Thread Safe - Zip
    echo 2. Ekstrak ke folder: installer\php\
    echo.
    pause
    exit /b 1
)
echo [OK] PHP portable ditemukan.

REM --- Check vendor ---
if not exist "%ROOT%\vendor\autoload.php" (
    echo [INFO] Menjalankan composer install...
    cd /d "%ROOT%"
    composer install --no-dev --optimize-autoloader
    if errorlevel 1 (
        echo [ERROR] Composer install gagal.
        pause
        exit /b 1
    )
)
echo [OK] Vendor sudah ada.

REM --- Check npm build ---
if not exist "%ROOT%\public\build\manifest.json" (
    echo [INFO] Menjalankan npm run build...
    cd /d "%ROOT%"
    npm install
    npm run build
    if errorlevel 1 (
        echo [ERROR] npm build gagal.
        pause
        exit /b 1
    )
)
echo [OK] Asset sudah di-build.

REM --- Copy php.ini ke folder php ---
echo [INFO] Menyalin php.ini...
copy /y "%INSTALLER%php.ini" "%INSTALLER%php\php.ini" >nul
echo [OK] php.ini disalin.

REM --- Find Inno Setup ---
if exist "C:\Program Files (x86)\Inno Setup 6\ISCC.exe" SET ISCC="C:\Program Files (x86)\Inno Setup 6\ISCC.exe"
if exist "C:\Program Files\Inno Setup 6\ISCC.exe" SET ISCC="C:\Program Files\Inno Setup 6\ISCC.exe"

if %ISCC%=="" (
    echo [ERROR] Inno Setup 6 tidak ditemukan.
    echo Download dari: https://jrsoftware.org/isdl.php
    pause
    exit /b 1
)
echo [OK] Inno Setup ditemukan: %ISCC%

REM --- Create output dir ---
if not exist "%INSTALLER%dist" mkdir "%INSTALLER%dist"

REM --- Build installer ---
echo.
echo [BUILD] Membangun installer .exe...
%ISCC% "%INSTALLER%setup.iss"
if errorlevel 1 (
    echo [ERROR] Build gagal!
    pause
    exit /b 1
)

echo.
echo ============================================
echo  BERHASIL! File installer ada di:
echo  installer\dist\BukuInduk-Setup.exe
echo ============================================
pause
endlocal
