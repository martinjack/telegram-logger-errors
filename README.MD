# Telegram logger errors

Package for laravel TLE - Telegram logger errors

[![Latest Stable Version](https://poser.pugx.org/jackmartin/telegram-logger-errors/v/stable)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![Total Downloads](https://poser.pugx.org/jackmartin/telegram-logger-errors/downloads)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![License](https://poser.pugx.org/jackmartin/telegram-logger-errors/license)](https://packagist.org/packages/jackmartin/telegram-logger-errors)

## Installation

Run composer require command.

```sh
composer require jackmartin/telegram-logger-errors
```

### Laravel Setting

After updating composer, register the service provider in boostrap\app.php
```php
TLE\TLEServiceProvider::class
```

Add then alias TLE adding its facade to the aliases array in the same file:
```php
'TLE' => TLE\Facades\TLEFacade::class
```

### Copy file config tle.php in confing folder
```sh
php artisan vendor:publish
```
Select Tag: tle-config

or
```sh
php artisan vendor:publish --provider="TLE\TLEServiceProvider" --tag="tle-config"
```

#### Chat ID
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

## Usage

```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::send($e);

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
TLE\TLEServiceProvider::class
```

Затем для быстрого вызов класса пакета, добавьте псевдоним в этот же файле:
```php
'TLE' => TLE\Facades\TLEFacade::class
```

### Копируем файл настроек tle.php в config папку

```sh
php artisan vendor:publish
```
Выбираем Tag: tle-config

или
```sh
php artisan vendor:publish --provider="TLE\TLEServiceProvider" --tag="tle-config"
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

## Использование

```php
use TLE;

try {

print_r($a);

} catch (\Exception $e) {

TLE::send($e);

}
```