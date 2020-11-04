# Laravel 8 Sandbox

## Instructions for testing Excel issue. 
git clone git@github.com:abrahambosch/laravel-8-sandbox.git
cd laravel-8-sandbox
git fetch 
git checkout feature/add-excel
composer install
cp .env.example .env
touch ./database/database.sqlite
php vendor/bin/phpunit
