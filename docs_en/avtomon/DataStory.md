<small> avtomon </small>

DataStory
=========

Primary data acquisition class

Description
-----------

Class DataStory

Signature
---------

- **class**.

Properties
----------

class sets the following properties:

  - [`$settings`](#$settings) &mdash; Class settings
  - [`$instances`](#$instances) &mdash; Available instances of the class
  - [`$request`](#$request) &mdash; Request text
  - [`$params`](#$params) &mdash; Query Parameters
  - [`$dbConnect`](#$dbConnect) &mdash; Connection to the RDB
  - [`$cacheConnect`](#$cacheConnect) &mdash; Connecting to the cache store
  - [`$cacheQuery`](#$cacheQuery) &mdash; Query Cache Object
  - [`$cacheHtml`](#$cacheHtml) &mdash; Page cache object
  - [`$requestSettings`](#$requestSettings) &mdash; Request Properties

### `$settings`<a name="settings"> </a>

Class settings

#### Signature

**protected static** property.
- The value of `array`.

### `$instances`<a name="instances"> </a>

Available instances of the class

#### Signature

**protected static** property.
- The value of `array`.

### `$request`<a name="request"> </a>

Request text

#### Signature

- **protected** property.
- The value of `string`.

### `$params`<a name="params"> </a>

Query Parameters

#### Signature

- **protected** property.
- The value of `array`.

### `$dbConnect`<a name="dbConnect"> </a>

Connection to the RDB

#### Signature

- **protected** property.
- Can be one of the following types:
  - `null`
  - `avtomon\CachePDO`

### `$cacheConnect`<a name="cacheConnect"> </a>

Connecting to the cache store

#### Signature

- **protected** property.
- Can be one of the following types:
  - `null`
  - `Redis`
  - `Memcached`

### `$cacheQuery`<a name="cacheQuery"> </a>

Query Cache Object

#### Signature

- **protected** property.
- Can be one of the following types:
  - `null`
  - [`CacheQuery`](../avtomon/CacheQuery.md)

### `$cacheHtml`<a name="cacheHtml"> </a>

Page cache object

#### Signature

- **protected** property.
- Can be one of the following types:
  - `null`
  - [`CacheHtml`](../avtomon/CacheHtml.md)

### `$requestSettings`<a name="requestSettings"> </a>

Request Properties

#### Signature

- **protected** property.
- The value of `array`.

Methods
-------

Class methods class:

  - [`create()`](#create) &mdash; Create or return class intstrans
  - [`__construct()`](#__construct) &mdash; Constructor
  - [`setIsModifying()`](#setIsModifying) &mdash; Set the query type: changing (true) or reading (false)
  - [`setParams()`](#setParams) &mdash; Set query parameters
  - [`setCacheConnect()`](#setCacheConnect) &mdash; Set cache connection
  - [`setDbConnect()`](#setDbConnect) &mdash; Establish connection to the RDB
  - [`getCacheQuery()`](#getCacheQuery) &mdash; Return query cache object
  - [`getCacheHtml()`](#getCacheHtml) &mdash; Return page cache object
  - [`getValue()`](#getValue) &mdash; Return the query result to the RDB
  - [`deleteValue()`](#deleteValue) &mdash; Removing the query cache entry for the database
  - [`getHtml()`](#getHtml) &mdash; Return HTML
  - [`setHtml()`](#setHtml) &mdash; Cache HTML page
  - [`deleteHtml()`](#deleteHtml) &mdash; Deleting a page cache element
  - [`execQuery()`](#execQuery) &mdash; Create a query object and execute it

### `create()`<a name="create"> </a>

Create or return class intstrans

#### Signature

- **public static** method.
- It can take the following parameter (s):
  - `$request`(`string`) - the text of the request
  - `$params`(`array`) - query parameters
  - `$settings`(`array`) -additional settings
- Returns [`DataStory`](../avtomon/DataStory.md) value.

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **protected** method.
- It can take the following parameter (s):
  - `$request`(`string`) - the text of the request
  - `$params`(`array`) - query parameters
  - `$settings`(`array`) - settings
- Returns nothing.
- Throws one of the following exceptions:
  - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setIsModifying()`<a name="setIsModifying"> </a>

Set the query type: changing (true) or reading (false)

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$flag`(`bool`)
- Returns nothing.

### `setParams()`<a name="setParams"> </a>

Set query parameters

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$params`(`array`) - parameters
- Returns nothing.

### `setCacheConnect()`<a name="setCacheConnect"> </a>

Set cache connection

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$cacheConnect`(`null`| `Redis`|`Memcached`) - connection to the cache
- Returns nothing.

### `setDbConnect()`<a name="setDbConnect"> </a>

Establish connection to the RDB

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$dbConnect`(`avtomon\CachePDO`| `null`)
- Returns nothing.

### `getCacheQuery()`<a name="getCacheQuery"> </a>

Return query cache object

#### Signature

- **protected** method.
- Returns [`CacheQuery`](../avtomon/CacheQuery.md) value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `getCacheHtml()`<a name="getCacheHtml"> </a>

Return page cache object

#### Signature

- **protected** method.
- Returns [`CacheHtml`](../avtomon/CacheHtml.md) value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `getValue()`<a name="getValue"> </a>

Return the query result to the RDB

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$prefix`(`string`) - the prefix of the names of the resulting fields
- Returns the `avtomon\DbResultItem`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - `avtomon\DbResultItemException`

### `deleteValue()`<a name="deleteValue"> </a>

Removing the query cache entry for the database

#### Signature

- **public** method.
- Returns the `bool`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `getHtml()`<a name="getHtml"> </a>

Return HTML

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$verifyingFilePath`(`string`) - the path to the file that will be used to check the cache's caching
- Returns the `avtomon\HTMLResultItem`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `setHtml()`<a name="setHtml"> </a>

Cache HTML page

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$html`(`avtomon\HTMLResultItem`) - HTML
  - `$tags`(`array`) - tags
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)
  - [`avtomon\DataStoryException`](../avtomon/DataStoryException.md)

### `deleteHtml()`<a name="deleteHtml"> </a>

Deleting a page cache element

#### Signature

- **public** method.
- Returns the `bool`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `execQuery()`<a name="execQuery"> </a>

Create a query object and execute it

#### Signature

- **public static** method.
- It can take the following parameter (s):
  - `$request`(`string`) - the text of the request
  - `$params`(`array`) - query parameters
  - `$settings`(`array`) - settings
- Can return one of the following values:
  - `avtomon\DbResultItem`
  - `null`
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - `avtomon\DbResultItemException`

