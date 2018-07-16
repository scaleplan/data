<small> avtomon </small>

CacheQuery
==========

Class of query results caching to the database

Description
-----------

Class CacheQuery

Signature
---------

- **class**.
- It is a subclass of the class [`AbstractCacheItem`](../avtomon/AbstractCacheItem.md).

Properties
----------

class sets the following properties:

  - [`$dbConnect`](#$dbConnect) &mdash; Connection to the RDB
  - [`$isModifying`](#$isModifying) &mdash; Whether the query changes database data

### `$dbConnect`<a name="dbConnect"> </a>

Connection to the RDB

#### Signature

- **protected** property.
- Can be one of the following types:
  - `avtomon\_PDO`
  - `null`

### `$isModifying`<a name="isModifying"> </a>

Whether the query changes database data

#### Signature

- **protected** property.
- The value of `bool`.

Methods
-------

Class methods class:

  - [`__construct()`](#__construct) &mdash; Constructor
  - [`generateTags()`](#generateTags) &mdash; Initializing Tags
  - [`getIsModifying()`](#getIsModifying) &mdash; Whether the request is changing
  - [`setIsModifying()`](#setIsModifying) &mdash; Set the query type: changing (true) or reading (false)
  - [`get()`](#get) &mdash; Get Cache Item Data
  - [`getTags()`](#getTags) &mdash; Reset request tags

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$dbConnect`(`avtomon\_PDO`) &mdash; - connection to the RDB
  - `$request`(`string`) &mdash; - the text of the request
  - `$params`(`array`) &mdash; - query parameters
  - `$cacheConnect`(`Memcached`| `Redis`|`null`) &mdash; - connection to the cache store
  - `$settings`(`array`) &mdash; - settings
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `generateTags()`<a name="generateTags"> </a>

Initializing Tags

#### Signature

- **protected** method.
- Returns nothing.

### `getIsModifying()`<a name="getIsModifying"> </a>

Whether the request is changing

#### Signature

- **public** method.
- Returns the `bool`value.

### `setIsModifying()`<a name="setIsModifying"> </a>

Set the query type: changing (true) or reading (false)

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$flag`(`bool`)
- Returns nothing.

### `get()`<a name="get"> </a>

Get Cache Item Data

#### Signature

- **public** method.
- Can return one of the following values:
- array
  - `null`
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - `avtomon\DbResultItemException`

### `getTags()`<a name="getTags"> </a>

Reset request tags

#### Signature

- **public** method.
Returns the `array`value.

