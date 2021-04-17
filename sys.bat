@echo off
if %1%==deploy (
    GOTO DEPLOY
)
if %1%==start (
    GOTO START
)
:DONE
echo system is on :)
EXIT

:DEPLOY
start /wait py auto/storage_fresh.py
start /wait py auto/clear_archive.py
start /wait php artisan cache:clear
start /wait php artisan route:clear
start /wait php artisan migrate:fresh --seed
GOTO START

:START
start php artisan serve
GOTO DONE
