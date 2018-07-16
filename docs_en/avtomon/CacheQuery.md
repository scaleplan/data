the <small>avtomon</small>

CacheQuery
==========

Class for caching the results of database queries

Description
-----------

Class CacheQuery

Signature
---------

- **class**.
- Is a subclass of the class ['AbstractCacheItem'](../avtomon/AbstractCacheItem.md).

Properties
----------

class sets the following properties:

- ['$dbConnect'](#$dbConnect) &mdash; connection to RDB
  - [`$isModifying`](#$isModifying) &mdash; Modifies if the query data database

### '$dbConnect '<a name= "dbConnect" ></a>

Connection to RBD

#### Signature

- **protected * * property.
- Can be one of the following types:
    `'avtomon\_PDO`
    - 'null`

### '$isModifying '<a name= "isModifying" ></a>

If the request modifies data in your database

#### Signature

- **protected * * property.
- `Bool ' value.

Methods
-------

Class methods class:

  - [`__construct (`' ](#__construct) &mdash; Constructor
- ['generateTags()'](#generateTags) &mdash; tag Initialization
- ['getIsModifying()'](#getIsModifying) &mdash; Changes whether the query
  - [`setIsModifying()`](#setIsModifying) &mdash; Set request type: modify (true) or reading (false)
- ['get()'](#get) &mdash; Get cache item data
- ['getTags()'](#getTags) &mdash; Return query tags

### `__construct() '<a name= "__construct " ></a>

Designer

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$dbConnect ' (`avtomon\_PDO`) &mdash; - connection to RDB
    - '$request ' ('string`) &mdash; - request text
    - '$params ' ('array`) &mdash; - query parameters
    - '$cacheConnect ' ('Memcached' | 'Redis`|`null') &mdash; - connect to cache storage
    - '$settings ' ('array`) &mdash; - settings
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'generateTags()' <a name= "generateTags" ></a>

Tag initialization

#### Signature

- **protected * * method.
- It doesn't make it back.

### 'getIsModifying()' <a name= "getIsModifying" ></a>

Whether the request changes

#### Signature

- **public * * method.
- Returns `bool ' value.

### 'setIsModifying`)' <a name= "setIsModifying" ></a>

Set the query type to changing (true) or reading (false)

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$flag ' ('bool`)
- It doesn't make it back.

### 'get()' <a name= ' get ' ></a>

Get cache item data

#### Signature

- **public * * method.
- Can return one of the following values:
    - 'array`
    - 'null`
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

### `getTags() '<a name= "getTags" ></a>

Return the tags of the request

#### Signature

- **public * * method.
- Returns 'array' value.

