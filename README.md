## Installation
#### Create new Laravel project
```bash
laravel new my-project
```
or
```bash
composer create-project --prefer-dist laravel/laravel my-project
```
#### Go to project root
```bash
cd my-project
```

#### Require Leaf package
```bash
composer require cubesystems/leaf dev-master
```
#### Fill in database info
```bash
vi .env
```

#### Add Leaf service provider in your application configuration

config/app.php
```php
'providers' => [
    ...
    CubeSystems\Leaf\Providers\LeafServiceProvider::class,
]
```

#### Run installer and follow instructions
```bash
php artisan leaf:install
```

## Coding style

### JS

We use `airbnb` coding style for both JS and SASS (links below).

To install the built-in inspections for PHPStorm, follow these instructions:
[https://www.themarketingtechnologist.co/how-to-get-airbnbs-javascript-code-style-working-in-webstorm/](https://www.themarketingtechnologist.co/how-to-get-airbnbs-javascript-code-style-working-in-webstorm/) 

#### Note!

When specifying JSCS package in the configuration window, it has to be installed locally (within the project).
Global installation will not work (PHPStorm installs packages globally).

#### Customization

Rules can be modified either in separate files (`.jscsrc` or `.jscs.json` in project's root directory)
or project's `package.json` file (`jscsConfig` section).

#### Links:

* JS - [https://github.com/airbnb/javascript](https://github.com/airbnb/javascript)
* CSS / SASS - [https://github.com/airbnb/css](https://github.com/airbnb/css)
