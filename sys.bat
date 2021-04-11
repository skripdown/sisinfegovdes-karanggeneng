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
start /wait php artisan migrate:fresh --seed
GOTO START

:START
start php artisan serve
GOTO DONE
