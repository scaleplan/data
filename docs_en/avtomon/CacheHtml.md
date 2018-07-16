the <small>avtomon</small>

CacheHtml
=========

Class control the caching of HTML pages

Description
-----------

Class CacheHtml

Signature
---------

- **class**.
- Is a subclass of the class ['AbstractCacheItem'](../avtomon/AbstractCacheItem.md).

Constants
---------

class sets the following constants:

  - [`URL_TEMPLATE`](#URL_TEMPLATE) &mdash; a Regular expression to validate the format of the transmitted url

Properties
----------

class sets the following properties:

- ['$checkFile'](#$checkFile) &mdash; the Path to the file where the cache will be checked

### '$checkFile '<a name= "checkFile" ></a>

The path to the file that will be used to check whether the cache is up-to-date

#### Signature

- **protected * * property.
- Value `string'.

Methods
-------

Class methods class:

  - [`__construct (`' ](#__construct) &mdash; Constructor
  - [`setCheckFile()`](#setCheckFile) &mdash; Set the file check
  - [`checkFileTime()`](#checkFileTime) &mdash; to Check the relevance of the cache file changes
- ['get()'](#get) &mdash; Get cache item data
- ['setTags()'](#setTags) &mdash; tagging

### `__construct() '<a name= "__construct " ></a>

Designer

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$url ' ('string`) &mdash; - url text
    - '$params ' ('array`) &mdash; - query parameters
    - '$cacheConnect ' ('Memcached' | 'Redis`|`null') &mdash; - connect to cache storage
    - '$tags ' ('array`) &mdash; - array of tags
    - '$settings ' ('array`) &mdash; - settings
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
      - [`avtomon\CacheHtmlException'](../avtomon/CacheHtmlException.md)

### 'setCheckFile()' <a name= 'setCheckFile' ></a>

To install the file check

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$filePath ' ('string`) &mdash; - file path
- It doesn't make it back.

### 'checkFileTime()' <a name= 'checkFileTime' ></a>

To check the relevance of the cache file changes

#### Signature

- **protected * * method.
- Can take the following parameter (s):
    - '$cacheTime ' ('int`) &mdash; - cache installation time
- Returns `bool ' value.

### 'get()' <a name= ' get ' ></a>

Get cache item data

#### Signature

- **public * * method.
- Can return one of the following values:
    - 'array`
    - 'null`
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### `setTags() '<a name= "setTags" ></a>

Tagging

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$tags ' ('array`) &mdash; - array of tags
- It doesn't make it back.

