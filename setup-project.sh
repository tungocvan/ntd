#!/bin/bash
composer update
php artisan db:mysql restore db.sql --force
php artisan optimize:clear
npm i vite
npm run build
php artisan storage:link
