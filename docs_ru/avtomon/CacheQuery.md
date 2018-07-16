<small>avtomon</small>

CacheQuery
==========

Класс кэширования результатов запросов к БД

Описание
-----------

Class CacheQuery

Сигнатура
---------

- **class**.
- Является подклассом класса [`AbstractCacheItem`](../avtomon/AbstractCacheItem.md).

Свойства
----------

class устанавливает следующие свойства:

- [`$dbConnect`](#$dbConnect) &mdash; Подключение к РБД
- [`$isModifying`](#$isModifying) &mdash; Изменяет ли запрос данные БД

### `$dbConnect` <a name="dbConnect"></a>

Подключение к РБД

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `avtomon\_PDO`
    - `null`

### `$isModifying` <a name="isModifying"></a>

Изменяет ли запрос данные БД

#### Сигнатура

- **protected** property.
- Значение `bool`.

Методы
-------

Методы класса class:

- [`__construct()`](#__construct) &mdash; Конструктор
- [`generateTags()`](#generateTags) &mdash; Инициализация тегов
- [`getIsModifying()`](#getIsModifying) &mdash; Изменяющий ли запрос
- [`setIsModifying()`](#setIsModifying) &mdash; Установить тип запроса: изменяющий (true) или читающий (false)
- [`get()`](#get) &mdash; Получить данные элемента кэша
- [`getTags()`](#getTags) &mdash; Вернуть теги запроса

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$dbConnect` (`avtomon\_PDO`) &mdash; - подключение к РБД
    - `$request` (`string`) &mdash; - текст запроса
    - `$params` (`array`) &mdash; - параметры запроса
    - `$cacheConnect` (`Memcached`|`Redis`|`null`) &mdash; - подключение к хранилицу кэшей
    - `$settings` (`array`) &mdash; - настройки
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `generateTags()` <a name="generateTags"></a>

Инициализация тегов

#### Сигнатура

- **protected** method.
- Ничего не возвращает.

### `getIsModifying()` <a name="getIsModifying"></a>

Изменяющий ли запрос

#### Сигнатура

- **public** method.
- Возвращает `bool` value.

### `setIsModifying()` <a name="setIsModifying"></a>

Установить тип запроса: изменяющий (true) или читающий (false)

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$flag` (`bool`)
- Ничего не возвращает.

### `get()` <a name="get"></a>

Получить данные элемента кэша

#### Сигнатура

- **public** method.
- Может возвращать одно из следующих значений:
    - `array`
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

### `getTags()` <a name="getTags"></a>

Вернуть теги запроса

#### Сигнатура

- **public** method.
- Возвращает `array` value.

