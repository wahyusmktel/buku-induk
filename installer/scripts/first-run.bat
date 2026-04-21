@echo off
setlocal enabledelayedexpansion

SET APP_DIR=%~dp0
SET APP_DIR=%APP_DIR:~0,-1%
SET PHP=%APP_DIR%\php\php.exe
SET WWW=%APP_DIR%\www

REM Skip if already installed
if exist "%APP_DIR%\.installed" goto :EOF

echo [1/6] Membuat direktori storage...
for %%D in (
    "%WWW%\storage\app"
    "%WWW%\storage\app\public"
    "%WWW%\storage\framework\sessions"
    "%WWW%\storage\framework\views"
    "%WWW%\storage\framework\cache"
    "%WWW%\storage\framework\cache\data"
    "%WWW%\storage\logs"
    "%WWW%\database"
    "%WWW%\bootstrap\cache"
) do if not exist %%D mkdir %%D

echo [2/6] Membuat database SQLite...
if not exist "%WWW%\database\database.sqlite" type nul > "%WWW%\database\database.sqlite"

REM Convert path separators for .env (backslash to forward slash)
SET DB_PATH=%WWW%\database\database.sqlite
SET DB_PATH=%DB_PATH:\=/%

echo [3/6] Membuat file konfigurasi...
(
echo APP_NAME="Buku Induk"
echo APP_ENV=production
echo APP_KEY=
echo APP_DEBUG=false
echo APP_URL=http://localhost:5779
echo(
echo LOG_CHANNEL=daily
echo LOG_LEVEL=error
echo(
echo DB_CONNECTION=sqlite
echo DB_DATABASE=%DB_PATH%
echo(
echo BROADCAST_DRIVER=log
echo CACHE_STORE=file
echo FILESYSTEM_DISK=local
echo QUEUE_CONNECTION=sync
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=120
echo(
echo MAIL_MAILER=log
) > "%WWW%\.env"

echo [4/6] Generate application key...
"%PHP%" "%WWW%\artisan" key:generate --force

echo [5/6] Menjalankan migrasi database...
"%PHP%" "%WWW%\artisan" migrate --force

echo [6/6] Optimasi...
"%PHP%" "%WWW%\artisan" config:cache
"%PHP%" "%WWW%\artisan" route:cache
"%PHP%" "%WWW%\artisan" view:cache

REM Mark as installed
echo installed > "%APP_DIR%\.installed"

echo.
echo Setup selesai!
endlocal
exit /b 0
