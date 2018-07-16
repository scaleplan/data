<small> avtomon </small>

AbstractCacheItem
=================

Base class of caching

Description
-----------

Class AbstractCacheItem

Signature
---------

- **abstract class**.

Constants
---------

The abstract class sets the following constants:

  - [`CACHE_STRUCTURE`](#CACHE_STRUCTURE) &mdash; Default cache structure

Properties
----------

The abstract class sets the following properties:

  - [`$settings`](#$settings) &mdash; Cache item settings
  - [`$request`](#$request) &mdash; Request text
  - [`$params`](#$params) &mdash; Query Parameters
  - [`$data`](#$data) &mdash; Data from the result of the query or value to be stored in the cache
  - [`$value`](#$value) &mdash; Value stored in the cache
  - [`$cacheConnect`](#$cacheConnect) &mdash; Connecting to the cache store
  - [`$tagTtl`](#$tagTtl) &mdash; TTL lifetime
  - [`$ttl`](#$ttl) &mdash; Cache element lifetime
  - [`$lockValue`](#$lockValue) &mdash; The value of the cache entry denoting a lock
  - [`$tryCount`](#$tryCount) &mdash; The number of attempts to get the value of the cache entry
  - [`$solt`](#$solt) &mdash; Hash key hash cache salt
  - [`$hashFunc`](#$hashFunc)
  - [`$paramSerializeFunc`](#$paramSerializeFunc) &mdash; Function serialization query parameters
  - [`$key`](#$key) &mdash; Cache key
  - [`$tags`](#$tags) &mdash; Cache element tags
  - [`$tryDelay`](#$tryDelay) &mdash; The time interval between two consecutive attempts to get the value of the cache entry

### `$settings`<a name="settings"> </a>

Cache item settings

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

### `$data`<a name="data"> </a>

Data from the result of the query or value to be stored in the cache

#### Signature

- **protected** property.
- Can be one of the following types:
- `null`
- `avtomon\AbstractResult`

### `$value`<a name="value"> </a>

Value stored in the cache

#### Signature

- **protected** property.
- The value of `array`.

### `$cacheConnect`<a name="cacheConnect"> </a>

Connecting to the cache store

#### Signature

- **protected** property.
- Can be one of the following types:
- `Memcached`
- `Redis`
- `null`

### `$tagTtl`<a name="tagTtl"> </a>

TTL lifetime

#### Signature

- **protected** property.
- The value of `int`.

### `$ttl`<a name="ttl"> </a>

Cache element lifetime

#### Signature

- **protected** property.
- The value of `int`.

### `$lockValue`<a name="lockValue"> </a>

The value of the cache entry denoting a lock

#### Signature

- **protected** property.
- The value of `string`.

### `$tryCount`<a name="tryCount"> </a>

The number of attempts to get the value of the cache entry

#### Signature

- **protected** property.
- The value of `int`.

### `$solt`<a name="solt"> </a>

Hash key hash cache salt

#### Signature

- **protected** property.
- The value of `string`.

### `$hashFunc`<a name="hashFunc"> </a>

#### Signature

- **protected** property.
- Can be one of the following types:
- `string`
- `callable`

### `$paramSerializeFunc`<a name="paramSerializeFunc"> </a>

Function serialization query parameters

#### Signature

- **protected** property.
- The value of `null`.

### `$key`<a name="key"> </a>

Cache key

#### Signature

- **protected** property.
- The value of `string`.

### `$tags`<a name="tags"> </a>

Cache element tags

#### Signature

- **protected** property.
- The value of `array`.

### `$tryDelay`<a name="tryDelay"> </a>

The time interval between two consecutive attempts to get the value of the cache entry

#### Signature

- **protected** property.
- The value of `int`.

Methods
-------

Abstract class methods:

  - [`__construct()`](#__construct) &mdash; Constructor
  - [`setSettings()`](#setSettings) &mdash; Set object settings
  - [`setCacheConnect()`](#setCacheConnect) &mdash; Establish a connection to the caching repository
  - [`initTags()`](#initTags) &mdash; Initialize the specified array of tags
  - [`setTags()`](#setTags) &mdash; Installing Tags
  - [`setHashFunc()`](#setHashFunc) &mdash; Set key hashing function
  - [`setParamSerializeFunc()`](#setParamSerializeFunc) &mdash; Set the query parameter's serialization function
  - [`getKey()`](#getKey) &mdash; The function to retrieve the cache entry key
  - [`getTagsTimes()`](#getTagsTimes) &mdash; Returns an array of validity times for tags associated with a query
  - [`get()`](#get) &mdash; Get the value of the cache entry
  - [`set()`](#set) &mdash; Saving the value in the cache
  - [`delete()`](#delete) &mdash; Asynchronous deletion of a cache entry
  - [`setLock()`](#setLock) &mdash; Set lock by key

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **protected** method.
- It can take the following parameter (s):
	- `$request`(`string`) &mdash; - the text of the request
	- `$params`(`array`) &mdash; - query parameters
	- `$cacheConnect`(`Memcached`| `Redis`|`null`) &mdash; - connection to the cache store
	- `$settings`(`array`) &mdash; - settings
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)
  - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setSettings()`<a name="setSettings"> </a>

Set object settings

#### Signature

- **public** method.
- It can take the following parameter (s):
	- `$settings`(`array`) &mdash; - array of settings
- Returns nothing.
- Throws one of the following exceptions:
  - [`ReflectionException`](http://php.net/class.ReflectionException)

### `setCacheConnect()`<a name="setCacheConnect"> </a>

Establish a connection to the caching repository

#### Signature

- **public** method.
- It can take the following parameter (s):
	- `$cacheConnect`(`Memcached`| `Redis`) &mdash; - connection object
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `initTags()`<a name="initTags"> </a>

Initialize the specified array of tags

#### Signature

- **public** method.
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `setTags()`<a name="setTags"> </a>

Installing Tags

#### Signature

- **protected** method.
- It can take the following parameter (s):
	- `$tags`(`array`) &mdash; - array of tags
- Returns nothing.

### `setHashFunc()`<a name="setHashFunc"> </a>

Set key hashing function

#### Signature

- **public** method.
- It can take the following parameter (s):
	- `$hashFunc`(`string`| `callable`) &mdash; - hash function
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `setParamSerializeFunc()`<a name="setParamSerializeFunc"> </a>

Set the query parameter's serialization function

#### Signature

- **public** method.
- It can take the following parameter (s):
	- `$serializeFunc`(`callable`| `string`) &mdash; - serialization function
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `getKey()`<a name="getKey"> </a>

The function to retrieve the cache entry key

#### Signature

- **protected** method.
Returns `string`value.

### `getTagsTimes()`<a name="getTagsTimes"> </a>

Returns an array of validity times for tags associated with a query

#### Signature

- **protected** method.
Returns the `array`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `get()`<a name="get"> </a>

Get the value of the cache entry

#### Signature

- **public** method.
- Can return one of the following values:
- array
- `null`
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `set()`<a name="set"> </a>

Saving the value in the cache

#### Signature

- **public** method.
- It can take the following parameter (s):
	- `$data`(`avtomon\AbstractResult`) &mdash; - value to save
- Returns the `bool`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

### `delete()`<a name="delete"> </a>

Asynchronous deletion of a cache entry

#### Signature

- **public** method.
- Returns the `bool`value.

### `setLock()`<a name="setLock"> </a>

Set lock by key

#### Signature

- **public** method.
- Returns the `bool`value.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../ avtomon/AbstractCacheItemException.md)

