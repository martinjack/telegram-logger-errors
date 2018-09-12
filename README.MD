# Telegram logger errors

Package for laravel TLE - Telegram logger errors

[![Latest Stable Version](https://poser.pugx.org/jackmartin/telegram-logger-errors/v/stable)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![Total Downloads](https://poser.pugx.org/jackmartin/telegram-logger-errors/downloads)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![License](https://poser.pugx.org/jackmartin/telegram-logger-errors/license)](https://packagist.org/packages/jackmartin/telegram-logger-errors)

![Image 1](https://github.com/martinjack/telegram-logger-errors/blob/master/img/bot.png?raw=true)

## Installation

Run composer require command.

```sh
composer require jackmartin/telegram-logger-errors
```

### Laravel Setting

After updating composer, register the service provider in boostrap\app.php
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

#### Limit length error message
```php
'limit_error_message' => 100
```

## Usage

```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::exp($e)->send();

}
```
```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::exp($e)->info('Field check')->send();

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

# Telegram logger errors 

Пакет для laravel TLE - Telegram логгер ошибок

## Установка

Установить пакет с помощью composer

```sh
composer require jackmartin/telegram-logger-errors
```

### Laravel Настройка

После установки пакета с помощью composer, зарегистрируйте сервис пакета в файле bootstrap/app.php:
```php
Telegram\Bot\Laravel\TelegramServiceProvider::class,
TLE\TLEServiceProvider::class
```

Затем для быстрого вызов класса пакета, добавьте псевдоним в этот же файле:
```php
'Telegram' => Telegram\Bot\Laravel\Facades\Telegram::class,
'TLE' => TLE\Facades\TLEFacade::class
```

### Копируем файл настроек telegram.php, tle.php в config папку

##### Telegram SDK
```sh
php artisan vendor:publish
```
Выбираем Provider: Telegram\Bot\Laravel\TelegramServiceProvider


##### TLE 
```sh
php artisan vendor:publish
```
Выбираем Tag: tle-config

или
```sh
php artisan vendor:publish --provider="TLE\TLEServiceProvider" --tag="tle-config"
```

#### Настройка токена и имя бота в config/telegram.php
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

#### Идентификатор чата
```php
'chat_id' => ''
```

#### Сохраняние лога
```php
'save_log' => true
```

#### Путь сохранения временного файла
```php
'path_save' => 'local'
```

#### Лимит длинны ошибки сообщения
```php
'limit_error_message' => 100
```

## Использование

```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::exp($e)->send();

}
```
```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::exp($e)->info('Field check')->send();

}
```

### Добавление класса TLE в глобальный обработчик ошибок в app\Exceptions\Handler.php
```php
public function report(Exception $exception)
{

    \TLE::exp($exception)->send();

    parent::report($exception);

}
```