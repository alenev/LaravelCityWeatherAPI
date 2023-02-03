
# XYZ 

XYZ is Laravel 8 application with REST-API for getting weather data from openweathermap.org with Google SSO and Redis data caching. The frontend is written in native JavaScript with Google login. SOLID practices are applied in backend controllers. There is a set of PHPUnit test suites for testing controller methods that are used in REST-API points. In config .github/workflows/sshdeploy.yml CI/CD is configured from localhost to VPS  

### [demo](https://xyz.alenev.name/)

- Introduction
- Installation
- REST-API
- PHPUnit

## Introduction
 
 - by default the Laravel Passport auth token has an expiration time of 3 minutes
 - openweathermap.org data for a certain city is stored in the database for 1 minute without repeated requests for 1 minute to openweathermap.org. If the time of the previous request to openweathermap.org for a certain city is more than 1 minute, a request is made to receive updated data from openweathermap.org
 - if data for a certain city is in the Redis cache, then the data is returned from the Redis cache
 - the data for a particular city is updated in the Redis cache after each new receipt of data for the city from openweathermap.org (at least 1 minute after the previous request to openweathermap.org for data for a particular city)


## Installation

```shell
composer install
```

```shell
php artisan migrate
```

```shell
php artisan passport:install
```

```shell
In the /google_api_config.json file, change the Google oAuth API credential data
```

For cashing by Redis (no require)
```shell
redis-server --port 6379 #run Redis server for caching
or
redis-server --port 6379 --daemonize yes #run Redis server in background
```

## REST-API

API points documentation in Postman:
https://documenter.getpostman.com/view/11745573/2s935ms5YH

API points collection in Postman for manual testing:
https://elements.getpostman.com/redirect?entityId=11745573-48690b4c-5492-4ba9-a1cd-c63c257664ac&entityType=collection

## PHPUnit

- to test Google oAuth (test_getGoogleLoginUrl) the GOOGLE_OAUTH_TEST parameter in /phpunit.xml must be set to 'true'. The console will display the authorization URL for Google oAuth in the browser
- after authorization in the browser and redirect from the address bar of the browser, you need to copy the value of the 'code' parameter and paste it into the GOOGLE_OAUTH_TEST_CODE parameter in /phpunit.xml After that, the test of exchanging the Google oAuth authorization code for the Passport access token (test_login_google) will display the token in the console
- to skip Google oAuth authorization tests, the GOOGLE_OAUTH_TEST parameter in /phpunit.xml must be set to 'false'

