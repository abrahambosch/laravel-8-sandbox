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


## Testing

phpunit tests have been writted to test the Excel library. 

to test the Excel Exports, run this
```
ddev ssh   # make sure you are loged into the web server. 
php ./vendor/bin/phpunit tests/Unit/Excel/Exports/
```
