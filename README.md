RXYZ

# Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [API](#api)

# Introduction

 RXYZ is Laravel application with API for getting weather data from openweathermap.org with Google SSO
 
 - by default the Laravel Passport auth token has an expiration time of 3 minutes
 - openweathermap.org data for a certain city is stored in the database for 1 minute without repeated requests for 1 minute to openweathermap.org. If the time of the previous request to openweathermap.org for a certain city is more than 1 minute, a request is made to receive updated data from openweathermap.org
 - if data for a certain city is in the Redis cache, then the data is returned from the Redis cache
 - the data for a particular city is updated in the Redis cache after each new receipt of data for the city from openweathermap.org (at least 1 minute after the previous request to openweathermap.org for data for a particular city)

# Requirements

This app requires:
- PHP __8.x__+
- Redis server on port 6379

# Installation

Setting on the example domain http://example.com:

```shell
In /.env file change 'APP_URL' parameter to http://example.com
```

```shell
In the /.env file, change the parameters for connecting to the mysql database in the DB_CONNECTION=mysql block
```

```shell
In the file /config/app.php change the 'url' parameter to http://example.com
```

```shell
In the /google_api_config.json file, change the Google oAuth API credential data to be relevant for the http://example.com domain. In parameters 'redirect_uris' and 'javascript_origins' domain http://example.com
```

```shell
composer install
```

```shell
php artisan migrate
```
```shell
php artisan passport:install
```

# API



