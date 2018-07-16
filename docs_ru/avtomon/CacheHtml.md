<small>avtomon</small>

CacheHtml
=========

Класс управления кэшированием HTML-страниц

Описание
-----------

Class CacheHtml

Сигнатура
---------

- **class**.
- Является подклассом класса [`AbstractCacheItem`](../avtomon/AbstractCacheItem.md).

Константы
---------

class устанавливает следующие константы:

- [`URL_TEMPLATE`](#URL_TEMPLATE) &mdash; Регулярное выражение для проверки правильности формата передаваемого урла

Свойства
----------

class устанавливает следующие свойства:

- [`$checkFile`](#$checkFile) &mdash; Путь до файла, по которому будет проверяться актуальность кэша

### `$checkFile` <a name="checkFile"></a>

Путь до файла, по которому будет проверяться актуальность кэша

#### Сигнатура

- **protected** property.
- Значение `string`.

Методы
-------

Методы класса class:

- [`__construct()`](#__construct) &mdash; Конструктор
- [`setCheckFile()`](#setCheckFile) &mdash; Установить пусть к файлу проверки
- [`checkFileTime()`](#checkFileTime) &mdash; Проверить актуальность кэша по времени изменения файла
- [`get()`](#get) &mdash; Получить данные элемента кэша
- [`setTags()`](#setTags) &mdash; Установка тегов

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$url` (`string`) &mdash; - текст урла
    - `$params` (`array`) &mdash; - параметры запроса
    - `$cacheConnect` (`Memcached`|`Redis`|`null`) &mdash; - подключение к хранилицу кэшей
    - `$tags` (`array`) &mdash; - массив тегов
    - `$settings` (`array`) &mdash; - настройки
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
    - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `setCheckFile()` <a name="setCheckFile"></a>

Установить пусть к файлу проверки

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$filePath` (`string`) &mdash; - путь к файлу
- Ничего не возвращает.

### `checkFileTime()` <a name="checkFileTime"></a>

Проверить актуальность кэша по времени изменения файла

#### Сигнатура

- **protected** method.
- Может принимать следующий параметр(ы):
    - `$cacheTime` (`int`) &mdash; - время установки кэша
- Возвращает `bool` value.

### `get()` <a name="get"></a>

Получить данные элемента кэша

#### Сигнатура

- **public** method.
- Может возвращать одно из следующих значений:
    - `array`
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `setTags()` <a name="setTags"></a>

Установка тегов

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$tags` (`array`) &mdash; - массив тегов
- Ничего не возвращает.

