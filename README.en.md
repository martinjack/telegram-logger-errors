![header](doc/header.png)

# Telegram logger errors

Package for laravel TLE - Telegram logger errors

[![Latest Stable Version](https://poser.pugx.org/jackmartin/telegram-logger-errors/v/stable)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![Total Downloads](https://poser.pugx.org/jackmartin/telegram-logger-errors/downloads)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![License](https://poser.pugx.org/jackmartin/telegram-logger-errors/license)](https://packagist.org/packages/jackmartin/telegram-logger-errors)

![bot](doc/bot.png)

> Read this in other language: [English](README.en.md), [Русский](README.md), [Український](README.ua.md)

# Requirements
* php 7.0
* composer

# Installation

Run composer require command.

```sh
composer require jackmartin/telegram-logger-errors
```

### Laravel Setting

After updating composer, register the service provider in config\app.php
```php
Telegram\Bot\Laravel\TelegramServiceProvider::class,
TLE\TLEServiceProvider::class
```

Add then alias TLE adding its facade to the aliases array in the same file:
```php
'Telegram' => Telegram\Bot\Laravel\Facades\Telegram::class,
'TLE' => TLE\Facades\TLEFacade::class
```

### Copy file config telegram.php, tle.php in confing folder

##### Telegram SDK
```sh
php artisan vendor:publish
```
Select Provider: Telegram\Bot\Laravel\TelegramServiceProvider

##### TLE 
```sh
php artisan vendor:publish
```
Select Tag: tle-config

or
```sh
php artisan vendor:publish --provider="TLE\TLEServiceProvider" --tag="tle-config"
```

#### Setting token and name bot in config/telegram.php
```php
'bots'  => [
    'common' => [
        'username' => 'Name bot',
        'token'    => 'Token bot',
        'commands' => [],
    ],

],
'default'  => 'common',
```
#### Debug mode
```php
'debug' => false
```

#### Name bot

Need for Telegram SDK

```php
'botname' => ''
```

#### Chat ID

Chat ID you can get via chat bot @RawDataBot

```php
'chat_id' => ''
```

#### Save log
```php
'save_log' => true
```

#### Path temporary save file
```php
'path_save' => 'local'
```

#### Disable write exception Telegram in log file
```php
'disable_exception_telegram' => false
``` 

## Usage

#### Send simple exception
```php
use TLE;

try {

    print_r($a);

} catch (\Exception $e) {

    TLE::exp($e)->send();

}
```
#### Send simple exception + information
```php
use TLE;

try {

    print_r($a);

} catch (\Exception $e) {

    TLE::exp($e)->info('Field check')->send();

}
```
#### Send Guzzle Exception
```php
use TLE;

try {

    print_r($a);

} catch (RequestException $e) {

    TLE::guzzle($e)->send();

}
```

### Add class TLE in global handler exception in app\Exceptions\Handler.php
```php
public function report(Exception $exception)
{

    \TLE::exp($exception)->send();

    parent::report($exception);

}
```