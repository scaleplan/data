<small> avtomon </small>

CacheHtml
=========

Class of caching HTML pages

Description
-----------

Class CacheHtml

Signature
---------

- **class**.
- It is a subclass of the class [`AbstractCacheItem`](../avtomon/AbstractCacheItem.md).

Constants
---------

class sets the following constants:

  - [`URL_TEMPLATE`](#URL_TEMPLATE) &mdash; Regular expression for verifying the correct format of the transmitted URL

Properties
----------

class sets the following properties:

  - [`$checkFile`](#$checkFile) &mdash; The path to the file on which the cache relevance will be checked

### `$checkFile`<a name="checkFile"> </a>

The path to the file on which the cache relevance will be checked

#### Signature

- **protected** property.
- The value of `string`.

Methods
-------

Class methods class:

  - [`__construct()`](#__construct) &mdash; Constructor
  - [`setCheckFile()`](#setCheckFile) &mdash; Set to the check file
  - [`checkFileTime()`](#checkFileTime) &mdash; Check the cache relevance by file modification time
  - [`get()`](#get) &mdash; Get Cache Item Data
  - [`setTags()`](#setTags) &mdash; Installing Tags

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$url`(`string`) &mdash; - text url
  - `$params`(`array`) &mdash; - query parameters
  - `$cacheConnect`(`Memcached`| `Redis`|`null`) &mdash; - connection to the cache store
  - `$tags`(`array`) &mdash; - array of tags
  - `$settings`(`array`) &mdash; - settings
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)
  - [`avtomon\CacheHtmlException`](../avtomon/CacheHtmlException.md)

### `setCheckFile()`<a name="setCheckFile"> </a>

Set to the check file

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$filePath`(`string`) &mdash; - the path to the file
- Returns nothing.

### `checkFileTime()`<a name="checkFileTime"> </a>

Check the cache relevance by file modification time

#### Signature

- **protected** method.
- It can take the following parameter (s):
  - `$cacheTime`(`int`) &mdash; - cache installation time
- Returns the `bool`value.

### `get()`<a name="get"> </a>

Get Cache Item Data

#### Signature

- **public** method.
- Can return one of the following values:
- array
  - `null`
- Throws one of the following exceptions:
  - [`avtomon\AbstractCacheItemException`](../avtomon/AbstractCacheItemException.md)

### `setTags()`<a name="setTags"> </a>

Installing Tags

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$tags`(`array`) &mdash; - array of tags
- Returns nothing.

