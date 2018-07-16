<small>avtomon</small>

AbstractCacheItem
=================

Базовый класс кэширования

Описание
-----------

Class AbstractCacheItem

Сигнатура
---------

- **abstract class**.

Константы
---------

abstract class устанавливает следующие константы:

- [`CACHE_STRUCTURE`](#CACHE_STRUCTURE) &mdash; Структура элемента кэша по умолчанию

Свойства
----------

abstract class устанавливает следующие свойства:

- [`$settings`](#$settings) &mdash; Настройки элемента кэша
- [`$request`](#$request) &mdash; Текст запроса
- [`$params`](#$params) &mdash; Параметры запроса
- [`$data`](#$data) &mdash; Данные из езультата запроса или значение для сохранения в кэше
- [`$value`](#$value) &mdash; Значение сохраненное в кэше
- [`$cacheConnect`](#$cacheConnect) &mdash; Подключение к хранилицу кэшей
- [`$tagTtl`](#$tagTtl) &mdash; Время жизни тега
- [`$ttl`](#$ttl) &mdash; Время жизни элемента кэша
- [`$lockValue`](#$lockValue) &mdash; Значение элемента кэша обозначающее блокировку
- [`$tryCount`](#$tryCount) &mdash; Количество поппыток получения значения элемента кэша
- [`$solt`](#$solt) &mdash; Соль хэширования ключа кэша
- [`$hashFunc`](#$hashFunc)
- [`$paramSerializeFunc`](#$paramSerializeFunc) &mdash; Функция сериализация параметров запроса
- [`$key`](#$key) &mdash; Ключ кэша
- [`$tags`](#$tags) &mdash; Теги элемента кэша
- [`$tryDelay`](#$tryDelay) &mdash; Временной интервал между двумя последовательными попытками получить значение элемента кэша

### `$settings` <a name="settings"></a>

Настройки элемента кэша

#### Сигнатура

- **protected static** property.
- Значение `array`.

### `$request` <a name="request"></a>

Текст запроса

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$params` <a name="params"></a>

Параметры запроса

#### Сигнатура

- **protected** property.
- Значение `array`.

### `$data` <a name="data"></a>

Данные из езультата запроса или значение для сохранения в кэше

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `null`
    - `avtomon\AbstractResult`

### `$value` <a name="value"></a>

Значение сохраненное в кэше

#### Сигнатура

- **protected** property.
- Значение `array`.

### `$cacheConnect` <a name="cacheConnect"></a>

Подключение к хранилицу кэшей

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `Memcached`
    - `Redis`
    - `null`

### `$tagTtl` <a name="tagTtl"></a>

Время жизни тега

#### Сигнатура

- **protected** property.
- Значение `int`.

### `$ttl` <a name="ttl"></a>

Время жизни элемента кэша

#### Сигнатура

- **protected** property.
- Значение `int`.

### `$lockValue` <a name="lockValue"></a>

Значение элемента кэша обозначающее блокировку

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$tryCount` <a name="tryCount"></a>

Количество поппыток получения значения элемента кэша

#### Сигнатура

- **protected** property.
- Значение `int`.

### `$solt` <a name="solt"></a>

Соль хэширования ключа кэша

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$hashFunc` <a name="hashFunc"></a>

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `string`
    - `callable`

### `$paramSerializeFunc` <a name="paramSerializeFunc"></a>

Функция сериализация параметров запроса

#### Сигнатура

- **protected** property.
- Значение `null`.

### `$key` <a name="key"></a>

Ключ кэша

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$tags` <a name="tags"></a>

Теги элемента кэша

#### Сигнатура

- **protected** property.
- Значение `array`.

### `$tryDelay` <a name="tryDelay"></a>

Временной интервал между двумя последовательными попытками получить значение элемента кэша

#### Сигнатура

- **protected** property.
- Значение `int`.

Методы
-------

Методы класса abstract class:

- [`__construct()`](#__construct) &mdash; Конструктор
- [`setSettings()`](#setSettings) &mdash; Установить настройки объекта
- [`setCacheConnect()`](#setCacheConnect) &mdash; Установить подключение к кэширующему хранилищу
- [`initTags()`](#initTags) &mdash; Инициализация заданного массива тегов
- [`setTags()`](#setTags) &mdash; Установка тегов
- [`setHashFunc()`](#setHashFunc) &mdash; Установить функцию хэширования ключа
- [`setParamSerializeFunc()`](#setParamSerializeFunc) &mdash; Установить функцию сериализации параметров запроса
- [`getKey()`](#getKey) &mdash; Функция получения ключа элемента кэша
- [`getTagsTimes()`](#getTagsTimes) &mdash; Возвращает массив времен актуальности тегов асоциированных с запросом
- [`get()`](#get) &mdash; Получить значение элемента кэша
- [`set()`](#set) &mdash; Cохранение значение в кэше
- [`delete()`](#delete) &mdash; Асинхронное удаление элемента кэша
- [`setLock()`](#setLock) &mdash; Установить блокировку по ключу

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **protected** method.
- Может принимать следующий параметр(ы):
    - `$request` (`string`) - текст запроса
    - `$params` (`array`) - параметры запроса
    - `$cacheConnect` (`Memcached`|`Redis`|`null`) - подключение к хранилицу кэшей
    - `$settings` (`array`) - настройки
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setSettings()` <a name="setSettings"></a>

Установить настройки объекта

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$settings` (`array`) - массив настроек
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setCacheConnect()` <a name="setCacheConnect"></a>

Установить подключение к кэширующему хранилищу

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$cacheConnect` (`Memcached`|`Redis`) - объект подключения
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `initTags()` <a name="initTags"></a>

Инициализация заданного массива тегов

#### Сигнатура

- **public** method.
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `setTags()` <a name="setTags"></a>

Установка тегов

#### Сигнатура

- **protected** method.
- Может принимать следующий параметр(ы):
    - `$tags` (`array`) - массив тегов
- Ничего не возвращает.

### `setHashFunc()` <a name="setHashFunc"></a>

Установить функцию хэширования ключа

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$hashFunc` (`string`|`callable`) - функция хэширования
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `setParamSerializeFunc()` <a name="setParamSerializeFunc"></a>

Установить функцию сериализации параметров запроса

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$serializeFunc` (`callable`|`string`) - функция сериализации
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `getKey()` <a name="getKey"></a>

Функция получения ключа элемента кэша

#### Сигнатура

- **protected** method.
- Возвращает `string` value.

### `getTagsTimes()` <a name="getTagsTimes"></a>

Возвращает массив времен актуальности тегов асоциированных с запросом

#### Сигнатура

- **protected** method.
- Возвращает `array` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `get()` <a name="get"></a>

Получить значение элемента кэша

#### Сигнатура

- **public** method.
- Может возвращать одно из следующих значений:
    - `array`
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `set()` <a name="set"></a>

Cохранение значение в кэше

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$data` (`avtomon\AbstractResult`) - значение для сохрания
- Возвращает `bool` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `delete()` <a name="delete"></a>

Асинхронное удаление элемента кэша

#### Сигнатура

- **public** method.
- Возвращает `bool` value.

### `setLock()` <a name="setLock"></a>

Установить блокировку по ключу

#### Сигнатура

- **public** method.
- Возвращает `bool` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

