# Casper

Casper is a simple app which allows users to create events and manage guests

## Getting Started

These instructions will get you a copy of the project up and running on your local
machine for development and testing purposes.
See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Requirements:

* Backend:
    * PHP >= 7.1.3
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
    * Ctype PHP Extension
    * JSON PHP Extension

* Frontend
    * Node JS >= 8

### Installing

First install composer dependencies

```bash
composer install
```

Then NPM dependencies

```bash
npm install
```
or, if you prefer yarn
```bash
yarn
```

Then copy **.env.example** to **.env**, and provide your environment details.
You can also create an additional **.env.testing** file which will cover environment for running tests

## Local development

Start build-in PHP server:
```bash
php artisan serve
```

On other terminal tab, run assets build:
```bash
npm run watch
```

## Running the tests

* Backend:
    * prepare **testing** environment - create **.env.testing** file and provide details
    * seed test database:
       ```
          composer seed:test
       ```
    * run the tests:
        ```
          composer test
        ```

## Deployment

[Work in progress]

## Built With

* [Laravel (5.7)](https://laravel.com/) - The web framework used
* [React](https://reactjs.org/) - Frontend library for creating dynamic views
* [Redux](https://redux.js.org/) - Library for managing state of JS apps
* [Redux Saga](https://github.com/redux-saga/redux-saga) - Library for middleware layer (for async calls and effects)
* [Bootstrap 4](https://getbootstrap.com/) - Css framework
