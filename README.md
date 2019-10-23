# itmo.loc
## Installation

``` bash
# create .env file based on .env.env-dist and your data
$ cp .env.env-dist .env

# install dependencies
$ composer install

# create database and migrate migrations
$ composer run data:migrate
```

## Additional info
``` bash
# server run
$ composer run server:run --timeout=0

# load fixtures
$ composer run fixtures:load

# recreate database and fill data
$ composer run data:recreate
```
