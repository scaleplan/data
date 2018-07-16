the <small>avtomon</small>

AbstractCacheItem
=================

Cache base class

Description
-----------

Class AbstractCacheItem

Signature
---------

- **abstract class**.

Constants
---------

the abstract class sets the following constants:

- ['CACHE_STRUCTURE'](#CACHE_STRUCTURE) &mdash; the default cache element Structure

Properties
----------

the abstract class sets the following properties:

- ['$settings'](#$settings) &mdash; cache item Settings
- ['$request`](#$request) &mdash; request Text
- ['$params`](#$params) &mdash; query Parameters
- ['$data'](#$data) &mdash; the data from the query result or the value to be cached
- ['$value'](#$value) &mdash; value stored in cache
  - [`$cacheConnect`](#$cacheConnect) &mdash; the Connection to granilith caches
- ['$tagTtl'](#$tagTtl) &mdash; tag lifetime
- ['$ttl'](#$ttl) &mdash; cache item lifetime
  - [`$lockValue`](#$lockValue) &mdash; the value of the item cache indicating a lock
- ['$tryCount'](#$tryCount) &mdash; number of times to get the value of the cache item
  - [`$>`](#$solt) &mdash; the Salt of the hash of the cache key
- ['$hashFunc'](#$hashFunc)
  - [`$paramSerializeFunc`](#$paramSerializeFunc) &mdash; a Function to serialize query parameters
- ['$key'](#$key) &mdash; cache Key
- ['$tags'](#$tags) &mdash; cache item Tags
- ['$tryDelay'](#$tryDelay) &mdash; the time interval between two consecutive attempts to retrieve the value of a cache item

### '$settings '<a name= "settings" ></a>

Cache item settings

#### Signature

- **protected static * * property.
- Value 'array'.

### '$request ' <a name="request" ></a>

Query text

#### Signature

- **protected * * property.
- Value `string'.

### '$params '<a name= "params" ></a>

Query parameter

#### Signature

- **protected * * property.
- Value 'array'.

### '$data '<a name= "data" ></a>

Data from the query result or value to be cached

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'null`
    - `avtomon\AbstractResult`

### '$value '<a name= "value" ></a>

The value stored in the cache

#### Signature

- **protected * * property.
- Value 'array'.

### '$cacheConnect '<a name= "cacheConnect" ></a>

Connecting to cache storage

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'Memcached`
    - 'Redis`
    - 'null`

### '$tagTtl '<a name= "tagTtl" ></a>

Tag life time

#### Signature

- **protected * * property.
- The value `int`.

### '$ttl '<a name= " ttl " ></a>

The life time of the cache element

#### Signature

- **protected * * property.
- The value `int`.

### '$lockValue '<a name= "lockValue"></a>

The value of the cache element indicates a lock

#### Signature

- **protected * * property.
- Value `string'.

### '$tryCount '<a name= "tryCount" ></a>

Number of times to get cache item value

#### Signature

- **protected * * property.
- The value `int`.

### '$solt '<a name= "solt" ></a>

The salt of the hash of the cache key

#### Signature

- **protected * * property.
- Value `string'.

### '$hashFunc '<a name= "hashFunc" ></a>

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'string`
    - `callable`

### '$paramSerializeFunc '<a name= "paramSerializeFunc" ></a>

Function serializing query parameters

#### Signature

- **protected * * property.
- Null.`

### '$key '<a name= " key " ></a>

The cache key

#### Signature

- **protected * * property.
- Value `string'.

### '$tags '<a name= "tags" ></a>

Cache item tags

#### Signature

- **protected * * property.
- Value 'array'.

### '$tryDelay '<a name= "tryDelay"></a>

The time interval between two consecutive attempts to retrieve the value of a cache item

#### Signature

- **protected * * property.
- The value `int`.

Methods
-------

Methods of the abstract class:

  - [`__construct (`' ](#__construct) &mdash; Constructor
- ['setSettings()'](#setSettings) &mdash; set object settings
  - [`setCacheConnect()`](#setCacheConnect) &mdash; to establish a connection to the caching storage
- ['initTags()'](#initTags) &mdash; Initialization of a given tag array
- ['setTags()'](#setTags) &mdash; tagging
  - [`setHashFunc()`](#setHashFunc) &mdash; Set the hash key
  - [`setParamSerializeFunc()`](#setParamSerializeFunc) &mdash; Set the serialization of query parameters
  - [`getKey()`](#getKey) &mdash; a Function of receiving the key for the cache item
  - [`getTagsTimes()`](#getTagsTimes) &mdash; Returns an array of times, the relevance of tags assotsiirovannyh request
- ['get()'](#get) &mdash; Get cache item value
- ['set()'](#set) &mdash; Saving value in cache
- ['delete()'](#delete) &mdash; Asynchronous cache item deletion
- ['setLock()'](#setLock) &mdash; set key lock

### `__construct() '<a name= "__construct " ></a>

Designer

#### Signature

- **protected * * method.
- Can take the following parameter (s):
    - '$request ' ('string`) &mdash; - request text
    - '$params ' ('array`) &mdash; - query parameters
    - '$cacheConnect ' ('Memcached' | 'Redis`|`null') &mdash; - connect to cache storage
    - '$settings ' ('array`) &mdash; - settings
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
    - ['ReflectionException'](http://php.net/class.ReflectionException)

### 'setSettings()' <a name= "setSettings" ></a>

Set object settings

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$settings ' ('array`) &mdash; - array of settings
- It doesn't make it back.
- Throws one of the following exceptions:
    - ['ReflectionException'](http://php.net/class.ReflectionException)

### 'setCacheConnect (`'<a name= "setCacheConnect" ></a>

Connect to the cache storage

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$cacheConnect ' ('Memcached' | 'Redis') &mdash; - connection object
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'initTags()' <a name= "initTags" ></a>

Initialize a given array of tags

#### Signature

- **public * * method.
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### `setTags() '<a name= "setTags" ></a>

Tagging

#### Signature

- **protected * * method.
- Can take the following parameter (s):
    - '$tags ' ('array`) &mdash; - array of tags
- It doesn't make it back.

### 'setHashFunc()' <a name= "setHashFunc" ></a>

Set the hash key

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$hashFunc ' ('string`| 'callable') &mdash; - hash function
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'setParamSerializeFunc (`'<a name= "setParamSerializeFunc" ></a>

Set the serialization of query parameters

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$serializeFunc ' ('callable`| 'string') &mdash; - serialization function
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'getKey()' <a name= 'getKey' ></a>

A function of receiving the key for the cache item

#### Signature

- **protected * * method.
- Returns 'string' value.

### `getTagsTimes() '<a name= "getTagsTimes" ></a>

Returns an array of the time-to-date tags associated with the query

#### Signature

- **protected * * method.
- Returns 'array' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'get()' <a name= ' get ' ></a>

To get the value of a cache item

#### Signature

- **public * * method.
- Can return one of the following values:
    - 'array`
    - 'null`
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'set()' <a name= " set " ></a>

Save the value in the cache

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$data ' ('avtomon\AbstractResult') &mdash; - value to save
- Returns `bool ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'delete()' <a name= 'delete' ></a>

Asynchronous deletion of a cache item

#### Signature

- **public * * method.
- Returns `bool ' value.

### 'setLock()' <a name= "setLock" ></a>

Set lock by key

#### Signature

- **public * * method.
- Returns `bool ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

