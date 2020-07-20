# REST API IN SLIM PHP

Example of REST API with [Slim PHP micro framework](https://www.slimframework.com).

![alt text](extras/img/slim-logo.png "Slim PHP micro framework")

Main technologies used: `PHP 7, Slim 3, MySQL, Redis, dotenv, PHPUnit and JSON Web Tokens.`

Also, I use other aditional tools like: `Docker & Docker Compose, Travis CI, Swagger, Code Climate, Scrutinizer, Sonar Cloud, PHPStan, PHP Insights, Heroku and CORS.`


## :gear: QUICK INSTALL:

### Requirements:

- Git.
- Composer.
- PHP 7.3+.
- MySQL/MariaDB.
- Redis (Optional).
- or Docker.


### With Git:

In your terminal execute this commands:

```bash
$ git clone https://github.com/supimpa/marveldextra.git && cd marveldextra
$ cp .env.example .env
$ composer install
$ composer restart-db
$ composer test
$ composer start
```

## :package: DEPENDENCIES:

### LIST OF REQUIRE DEPENDENCIES:

- [slim/slim](https://github.com/slimphp/Slim): Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.
- [respect/validation](https://github.com/Respect/Validation): The most awesome validation engine ever created for PHP.
- [palanik/corsslim](https://github.com/palanik/CorsSlim): Cross-origin resource sharing (CORS) middleware for PHP Slim.
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv): Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically.
- [predis/predis](https://github.com/nrk/predis/): Flexible and feature-complete Redis client for PHP and HHVM.
- [firebase/php-jwt](https://github.com/firebase/php-jwt): A simple library to encode and decode JSON Web Tokens (JWT) in PHP.

### LIST OF DEVELOPMENT DEPENDENCIES:

- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit): The PHP Unit Testing framework.
- [phpstan/phpstan](https://github.com/phpstan/phpstan): PHPStan - PHP Static Analysis Tool.
- [pestphp/pest](https://github.com/pestphp/pest): Pest is an elegant PHP Testing Framework with a focus on simplicity.
- [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights): Instant PHP quality checks from your console.
- [rector/rector](https://github.com/rectorphp/rector): Instant Upgrades and Instant Refactoring of any PHP 5.3+ code.


## :traffic_light: TESTING:

Run all PHPUnit tests with `composer test`.

```bash
$ composer test
> phpunit
PHPUnit 9.2.3 by Sebastian Bergmann and contributors.

...............................................................   63 / 63 (100%)

Time: 00:00.165, Memory: 18.00 MB

OK (63 tests, 388 assertions)
```


## :books: DOCUMENTATION:

### ENDPOINTS:

#### INFO:

- Help: `GET /`

- Status: `GET /status`


#### USERS:

- Login User: `POST /login`

- Create User: `POST /api/v1/users`

- Update User: `PUT /api/v1/users/{id}`

- Delete User: `DELETE /api/v1/users/{id}`


#### CHARACTERS:

- Get All Character: `GET /api/v1/public/characters`

- Get One Character: `GET /api/v1/public/characters/{id}`

- Create Character: `POST /api/v1/public/characters`

- Update Character: `PUT /api/v1/public/characters/{id}`

- Delete Character: `DELETE /api/v1/public/characters/{id}`

- Get Character Comics: `GET /api/v1/public/characters/{id}/comics`

- Get Character Series: `GET /api/v1/public/characters/{id}/series`

- Get Character Stories: `GET /api/v1/public/characters/{id}/stories`

- Get Character Events: `GET /api/v1/public/characters/{id}/events`


Also, you can see the API documentation with Postman [full list of endpoints](extras/docs/Marvel.postman_collection.json).

https://documenter.getpostman.com/view/988183/T1DmCy76?version=latest


### HELP AND DOCS:

For more information on how to use the REST API, see the following documentation available on [Postman Documenter](https://documenter.getpostman.com/view/1915278/RztfwByr).


### IMPORT WITH POSTMAN:

All the information of the API, prepared to download and use as postman collection: [Import Collection](https://www.getpostman.com/collections/707bde2aa3c39f2a8c1a).

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/707bde2aa3c39f2a8c1a)


## :rocket: DEPLOY:

You can deploy this API with Heroku Free ( TO DO ).


## :page_facing_up: LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
