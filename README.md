![header](doc/header.png)

# Telegram logger errors 

Пакет для laravel TLE - Telegram логгер ошибок

[![Latest Stable Version](https://poser.pugx.org/jackmartin/telegram-logger-errors/v/stable)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![Total Downloads](https://poser.pugx.org/jackmartin/telegram-logger-errors/downloads)](https://packagist.org/packages/jackmartin/telegram-logger-errors) [![License](https://poser.pugx.org/jackmartin/telegram-logger-errors/license)](https://packagist.org/packages/jackmartin/telegram-logger-errors)

![bot](doc/bot.png)

> Read this in other language: [English](README.en.md), [Русский](README.md), [Український](README.ua.md)

# Требования
* php 7.0
* composer

# Установка

Установить пакет с помощью composer

```sh
composer require jackmartin/telegram-logger-errors
```

### Laravel Настройка

После установки пакета с помощью composer, зарегистрируйте сервис пакета в файле config/app.php:
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
#### Режим отладки
```php
'debug' => false
```

#### Имя бота

Нужно для Telegram SDK

```php
'botname' => ''
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

#### Отключение записи ошибок Telegram в лог файл
```php
'disable_exception_telegram' => false
``` 

## Использование

#### Отправка простого исключения
```php
use TLE;

try {

    print_r($a);

} catch (\Exception $e) {

    TLE::exp($e)->send();

}
```
#### Отправка простого исключения + информацию
```php
use TLE;

try {

    print_r($a);

} catch (\Exception $e) {

    TLE::exp($e)->info('Field check')->send();

}
```
#### Отправка Guzzle исключения
```php
use TLE;

try {

    print_r($a);

} catch (RequestException $e) {

    TLE::guzzle($e)->send();

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