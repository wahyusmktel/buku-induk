@echo off
SET PHP=%~dp0php\php.exe
SET WWW=%~dp0www

cd /d "%WWW%"
SET PHP_CLI_SERVER_WORKERS=4
"%PHP%" artisan serve --host=localhost --port=5779 >> "%~dp0server.log" 2>&1
