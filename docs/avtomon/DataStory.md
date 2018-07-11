<small>avtomon</small>

DataStory
=========

Основной класс получения данных

Описание
-----------

Class DataStory

Сигнатура
---------

- **class**.

Свойства
----------

class устанавливает следующие свойства:

- [`$settings`](#$settings) &mdash; Настройки класса
- [`$instances`](#$instances) &mdash; Доступный инстансы класса
- [`$request`](#$request) &mdash; Текст запроса
- [`$params`](#$params) &mdash; Параметры запроса
- [`$dbConnect`](#$dbConnect) &mdash; Подключение к РБД
- [`$cacheConnect`](#$cacheConnect) &mdash; Подключение к хранилищу кэшей
- [`$cacheQuery`](#$cacheQuery) &mdash; Объект кэша запросов
- [`$cacheHtml`](#$cacheHtml) &mdash; Объект кэша страниц
- [`$requestSettings`](#$requestSettings) &mdash; Свойства запроса

### `$settings` <a name="settings"></a>

Настройки класса

#### Сигнатура

- **protected static** property.
- Значение `array`.

### `$instances` <a name="instances"></a>

Доступный инстансы класса

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

### `$dbConnect` <a name="dbConnect"></a>

Подключение к РБД

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `null`
    - `avtomon\_PDO`

### `$cacheConnect` <a name="cacheConnect"></a>

Подключение к хранилищу кэшей

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `null`
    - `Redis`
    - `Memcached`

### `$cacheQuery` <a name="cacheQuery"></a>

Объект кэша запросов

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `null`
    - [`CacheQuery`](../avtomon/CacheQuery.md)

### `$cacheHtml` <a name="cacheHtml"></a>

Объект кэша страниц

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `null`
    - [`CacheHtml`](../avtomon/CacheHtml.md)

### `$requestSettings` <a name="requestSettings"></a>

Свойства запроса

#### Сигнатура

- **protected** property.
- Значение `array`.

Методы
-------

Методы класса class:

- [`create()`](#create) &mdash; Создать или вернуть инстранс класса
- [`__construct()`](#__construct) &mdash; Конструктор
- [`setParams()`](#setParams) &mdash; Установить параметры запроса
- [`setCacheConnect()`](#setCacheConnect) &mdash; Установить посдключение к кэшу
- [`setDbConnect()`](#setDbConnect) &mdash; Установить подключение к РБД
- [`getCacheQuery()`](#getCacheQuery) &mdash; Вернуть объект кэша запросов
- [`getCacheHtml()`](#getCacheHtml) &mdash; Вернуть объект кэша страниц
- [`getValue()`](#getValue) &mdash; Вернуть результат запроса к РБД
- [`deleteValue()`](#deleteValue) &mdash; Удаление элемента кэша запросов к БД
- [`getHtml()`](#getHtml) &mdash; Вернуть HTML
- [`setHtml()`](#setHtml) &mdash; Сохранить к кэше HTML-страницу
- [`deleteHtml()`](#deleteHtml) &mdash; Удаление элемента кэша страниц
- [`execQuery()`](#execQuery) &mdash; Создать объект запроса и выполнить его

### `create()` <a name="create"></a>

Создать или вернуть инстранс класса

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$request` (`string`) &mdash; - текст запроса
    - `$params` (`array`) &mdash; - параметры запроса
    - `$settings` (`array`) &mdash; - дополниетельные настройки
- Возвращает [`DataStory`](../avtomon/DataStory.md) value.

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **protected** method.
- Может принимать следующий параметр(ы):
    - `$request` (`string`) &mdash; - текст запроса
    - `$params` (`array`) &mdash; - параметры запроса
    - `$settings` (`array`) &mdash; - настройки
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setParams()` <a name="setParams"></a>

Установить параметры запроса

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$params` (`array`) &mdash; - параметры
- Ничего не возвращает.

### `setCacheConnect()` <a name="setCacheConnect"></a>

Установить посдключение к кэшу

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$cacheConnect` (`null`|`Redis`|`Memcached`) &mdash; - подключение к кэшу
- Ничего не возвращает.

### `setDbConnect()` <a name="setDbConnect"></a>

Установить подключение к РБД

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$dbConnect` (`avtomon\_PDO`|`null`)
- Ничего не возвращает.

### `getCacheQuery()` <a name="getCacheQuery"></a>

Вернуть объект кэша запросов

#### Сигнатура

- **protected** method.
- Возвращает [`CacheQuery`](../avtomon/CacheQuery.md) value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `getCacheHtml()` <a name="getCacheHtml"></a>

Вернуть объект кэша страниц

#### Сигнатура

- **protected** method.
- Возвращает [`CacheHtml`](../avtomon/CacheHtml.md) value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `getValue()` <a name="getValue"></a>

Вернуть результат запроса к РБД

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$prefix` (`string`) &mdash; - префикс имен результирующих полей
- Возвращает `avtomon\DbResultItem` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

### `deleteValue()` <a name="deleteValue"></a>

Удаление элемента кэша запросов к БД

#### Сигнатура

- **public** method.
- Возвращает `bool` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `getHtml()` <a name="getHtml"></a>

Вернуть HTML

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$verifyingFilePath` (`string`) &mdash; - путь к файлу, по которому будет проверяться акутуальность кэша
- Возвращает `avtomon\HTMLResultItem` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `setHtml()` <a name="setHtml"></a>

Сохранить к кэше HTML-страницу

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$html` (`avtomon\HTMLResultItem`) &mdash; - HTML
    - `$tags` (`array`) &mdash; - теги
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)
    - [`avtomon\DataStoryException`](../avtomon/DataStoryException.md)

### `deleteHtml()` <a name="deleteHtml"></a>

Удаление элемента кэша страниц

#### Сигнатура

- **public** method.
- Возвращает `bool` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `execQuery()` <a name="execQuery"></a>

Создать объект запроса и выполнить его

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$request` (`string`) &mdash; - текст запроса
    - `$params` (`array`) &mdash; - параметры запроса
    - `$settings` (`array`) &mdash; - настройки
- Может возвращать одно из следующих значений:
    - `avtomon\DbResultItem`
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

