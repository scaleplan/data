<small>avtomon</small>

Query
=====

Класс организации запросов к БД

Описание
-----------

Class Query

Сигнатура
---------

- **class**.

Свойства
----------

class устанавливает следующие свойства:

- [`$rawSql`](#$rawSql) &mdash; Текст шаблона SQL-запроса
- [`$sql`](#$sql) &mdash; Текст запроса после обработки SqlTemplater::sql()
- [`$rawParams`](#$rawParams) &mdash; Параметры запроса до обработки SqlTemplater::sql()
- [`$params`](#$params) &mdash; Параметры запроса
- [`$dbConnect`](#$dbConnect) &mdash; Подключение к РБД
- [`$result`](#$result) &mdash; Результат запроса

### `$rawSql` <a name="rawSql"></a>

Текст шаблона SQL-запроса

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$sql` <a name="sql"></a>

Текст запроса после обработки SqlTemplater::sql()

#### Сигнатура

- **protected** property.
- Значение `string`.

### `$rawParams` <a name="rawParams"></a>

Параметры запроса до обработки SqlTemplater::sql()

#### Сигнатура

- **protected** property.
- Значение `array`.

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
    - `avtomon\CachePDO`

### `$result` <a name="result"></a>

Результат запроса

#### Сигнатура

- **protected** property.
- Может быть одного из следующих типов:
    - `avtomon\DbResultItem`
    - `null`

Методы
-------

Методы класса class:

- [`__construct()`](#__construct) &mdash; Конструктор
- [`getRawSql()`](#getRawSql) &mdash; Вернуть необработанный текст запроса
- [`getRawParams()`](#getRawParams) &mdash; Вернуть необработанный массив параметров запроса
- [`getSql()`](#getSql) &mdash; Вернуть текст запроса после обработки SqlTemplater::sql()
- [`getParams()`](#getParams) &mdash; Вернуть параметры запроса после обработки SqlTemplater::sql()
- [`setDbConnect()`](#setDbConnect) &mdash; Установить подключение к РБД
- [`execute()`](#execute) &mdash; Выполнить запрос
- [`executeAsync()`](#executeAsync) &mdash; Выполнить запрос асинхронно
- [`getResult()`](#getResult) &mdash; Вернуть результат запроса

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$dbConnect` (`avtomon\CachePDO`)
    - `$sql` (`string`)
    - `$params` (`array`)
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `getRawSql()` <a name="getRawSql"></a>

Вернуть необработанный текст запроса

#### Сигнатура

- **public** method.
- Возвращает `string` value.

### `getRawParams()` <a name="getRawParams"></a>

Вернуть необработанный массив параметров запроса

#### Сигнатура

- **public** method.
- Возвращает `array` value.

### `getSql()` <a name="getSql"></a>

Вернуть текст запроса после обработки SqlTemplater::sql()

#### Сигнатура

- **public** method.
- Возвращает `string` value.

### `getParams()` <a name="getParams"></a>

Вернуть параметры запроса после обработки SqlTemplater::sql()

#### Сигнатура

- **public** method.
- Возвращает `array` value.

### `setDbConnect()` <a name="setDbConnect"></a>

Установить подключение к РБД

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$dbConnect` (`avtomon\CachePDO`) - подключение к РБД
- Ничего не возвращает.

### `execute()` <a name="execute"></a>

Выполнить запрос

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$prefix` (`string`) - префикс ключей
- Возвращает `avtomon\DbResultItem` value.
- Выбрасывает одно из следующих исключений:
    - `avtomon\DbResultItemException`
    - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `executeAsync()` <a name="executeAsync"></a>

Выполнить запрос асинхронно

#### Сигнатура

- **public** method.
- Возвращает `bool` value.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `getResult()` <a name="getResult"></a>

Вернуть результат запроса

#### Сигнатура

- **public** method.
- Возвращает `avtomon\DbResultItem` value.

