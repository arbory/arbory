[![Packagist](https://img.shields.io/packagist/v/cubesystems/leaf.svg)](https://packagist.org/packages/cubesystems/leaf)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cubesystems/leaf/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cubesystems/leaf/?branch=master)
[![Build Status](https://travis-ci.org/cubesystems/leaf.svg?branch=master)](https://travis-ci.org/cubesystems/leaf)
[![Dependency Status](https://www.versioneye.com/user/projects/58f8b23ec2ef420052a23406/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58f8b23ec2ef420052a23406)
[![Coverage Status](https://coveralls.io/repos/github/cubesystems/leaf/badge.svg?branch=master)](https://coveralls.io/github/cubesystems/leaf?branch=master)

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

#### Add Leaf service providers in your application configuration

config/app.php
```php
'providers' => [
    ...
    CubeSystems\Leaf\Providers\LeafServiceProvider::class,
    CubeSystems\Leaf\Providers\NodeServiceProvider::class,
]
```

#### Run installer and follow instructions
```bash
php artisan leaf:install
```

## Usage

### Registering new pages

```php
Page::register( App\Pages\TextPage::class )
    ->fields( function( FieldSet $fieldSet )
    {
        $fieldSet->add( new CubeSystems\Leaf\Admin\Form\Fields\Richtext( 'text' ) );
    } )
    ->routes( function()
    {
        Route::get( '/', App\Http\Controllers\TextPageController::class . '@index' )->name( 'index' );
    } );
```

### Registering new admin modules

```php
AdminModule::register( App\Http\Controllers\Admin\TextController::class )->routes( function() {
    // ...
} );
```

## Validation

[Validation rules](https://laravel.com/docs/5.4/validation) can be attached to any field, like so

```php
$form->addField( new Text( 'title' ) )->setRules( 'required' );
```

### Validating translations

```php
$form->addField( new Translatable( ( new Text( 'title' ) )->rules( 'required' ) ) );
```

### Custom validators

* leaf_require_one_localized - at least one translation exists for this field
* leaf_file_required - file has been uploaded or is being passed in request

## Generators

### Quick generator

```bash
php artisan leaf:generate {type?} {--T|table=}
```

Generators available for

* Model
* Page
* Controller
* View
* AdminController - appends a new route to `routes/admin.php`

### Verbose Generator

```bash
php artisan leaf:generator
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

## Development

See [TODO.md](TODO.md) for more information.
