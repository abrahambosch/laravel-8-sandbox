# Laravel 8 Test Project

## DDEV 
- We are usingDDEV for the local development environment. 
- https://ddev.readthedocs.io/en/stable/

To start ddev run this command:
```
ddev start
```

to see configurations, database connections, etc
```
ddev describe
```

to stop ddev
```
ddev stop
```

to ssh into the web server
```
ddev ssh
```


## Laravel Excel
- we are using the Laravel Excel 3.1 Library
- https://docs.laravel-excel.com/3.1/getting-started/

## Testing

phpunit tests have been writted to test the Excel library. 

to test the Excel Exports, run this
```
ddev ssh   # make sure you are loged into the web server. 
php ./vendor/bin/phpunit tests/Unit/Excel/Exports/
```


## Instructions for testing Excel issue. 
git clone git@github.com:abrahambosch/laravel-8-sandbox.git
cd laravel-8-sandbox
git fetch 
git checkout feature/add-excel
composer install
cp .env.example .env
touch ./database/database.sqlite
php vendor/bin/phpunit

