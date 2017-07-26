[![Packagist](https://img.shields.io/packagist/v/arbory/arbory.svg)](https://packagist.org/packages/arbory/arbory)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/arbory/arbory/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arbory/arbory/?branch=master)
[![Build Status](https://travis-ci.org/arbory/arbory.svg?branch=master)](https://travis-ci.org/arbory/arbory)
[![Dependency Status](https://www.versioneye.com/user/projects/58f8b23ec2ef420052a23406/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58f8b23ec2ef420052a23406)
[![Coverage Status](https://coveralls.io/repos/github/arbory/arbory/badge.svg?branch=master)](https://coveralls.io/github/arbory/arbory?branch=master)

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

#### Require Arbory package
```bash
composer require arbory/arbory dev-master
```
#### Fill in database info
```bash
vi .env
```

#### Add Arbory service providers in your application configuration

config/app.php
```php
'providers' => [
    ...
    Arbory\Base\Providers\ArboryServiceProvider::class,
    Arbory\Base\Providers\NodeServiceProvider::class,
]
```

#### Run installer and follow instructions
```bash
php artisan arbory:install
```

## Usage

### Registering new pages

```php
Page::register( App\Pages\TextPage::class )
    ->fields( function( FieldSet $fieldSet )
    {
        $fieldSet->add( new Arbory\Base\Admin\Form\Fields\Richtext( 'text' ) );
    } )
    ->routes( function()
    {
        Route::get( '/', App\Http\Controllers\TextPageController::class . '@index' )->name( 'index' );
    } );
```

### Registering new admin modules

```php
Admin::modules()->register(  App\Http\Controllers\Admin\TextController::class );
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

* arbory_require_one_localized - at least one translation exists for this field
* arbory_file_required - file has been uploaded or is being passed in request

## Settings

Register a setting (with optional nesting) and retrieve it

```php
return [
    'my_letter' => [
        'to' => 'a friend',
        'subject' => 'Hello!'
    ]
]
```

```php
Settings::has('my_letter.to'); // true
Settings::get('my_letter.to'); // "a friend"
```

### Defining a field type

```php
return [
    'my_setting_key' => [
        'value' => 'My setting value',
        'type' => Arbory\Base\Admin\Form\Fields\CompactRichtext::class
    ],
]
```

### Translatable settings

```php
return [
    'hello' => [
        'type' => Arbory\Base\Admin\Form\Fields\Translatable::class,
        'value' => [
            'type' => Arbory\Base\Admin\Form\Fields\CompactRichtext::class,
            'value' => [
                'en' => 'Hello',
                'lv' => 'Sveiks'
            ]
        ]
    ],
]
```

## Generators

### Quick generator

```bash
php artisan arbory:generate {type?} {--T|table=}
```

Generators available for

* Model
* Page
* Controller
* View
* AdminController - appends a new route to `routes/admin.php`

### Verbose Generator

```bash
php artisan arbory:generator
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
