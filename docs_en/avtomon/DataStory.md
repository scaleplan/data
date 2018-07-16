the <small>avtomon</small>

Database
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

- ['$settings`](#$settings) &mdash; class Settings
- ['$instances'](#$instances) &mdash; class instances Available
- ['$request`](#$request) &mdash; request Text
- ['$params`](#$params) &mdash; query Parameters
- ['$dbConnect'](#$dbConnect) &mdash; connection to RDB
  - [`$cacheConnect`](#$cacheConnect) &mdash; the connection to the repository caches
- ['$cacheQuery'](#$cacheQuery) &mdash; query cache Object
- ['$cacheHtml'](#$cacheHtml) &mdash; page cache Object
- ['$requestSettings'](#$requestSettings) &mdash; query Properties

### '$settings '<a name= "settings" ></a>

Class settings

#### Signature

- **protected static * * property.
- Value 'array'.

### `$object`<a name="instances"></a>

Available instances of the class

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

### '$dbConnect '<a name= "dbConnect" ></a>

Connection to RBD

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'null`
    `'avtomon\_PDO`

### '$cacheConnect '<a name= "cacheConnect" ></a>

The connection to the repository caches

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'null`
    - 'Redis`
    - 'Memcached`

### '$cacheQuery '<a name= "cacheQuery" ></a>

Query cache object

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'null`
    - ['CacheQuery'](../avtomon/CacheQuery.md)

### '$cacheHtml '<a name= "cacheHtml" ></a>

Page cache object

#### Signature

- **protected * * property.
- Can be one of the following types:
    - 'null`
    - ['CacheHtml'](../avtomon/CacheHtml.md)

### '$requestSettings '<a name= "requestSettings" ></a>

Query properties

#### Signature

- **protected * * property.
- Value 'array'.

Methods
-------

Class methods class:

- ['create()'](#create) &mdash; Create or return instrans class
  - [`__construct (`' ](#__construct) &mdash; Constructor
  - [`setIsModifying()`](#setIsModifying) &mdash; Set request type: modify (true) or reading (false)
- ['setParams (`' ](#setParams) &mdash; set query parameters
- ['setCacheConnect()'](#setCacheConnect) &mdash; set cache connection
- ['setDbConnect()'](#setDbConnect) &mdash; connect to RDB
  - [`getCacheQuery()`](#getCacheQuery) &mdash; Return the object of the query cache
- ['getCacheHtml (`' ](#getCacheHtml) &mdash; Return page cache object
- ['getValue (`' ](#getValue) &mdash; Return the result of a query to the RDB
- ['deleteValue()'](#deleteValue) &mdash; Delete database query cache item
- ['getHtml (`' ](#getHtml) &mdash; Return HTML
- ['setHtml (`' ](#setHtml) &mdash; Save to cache HTML page
- ['deleteHtml()'](#deleteHtml) &mdash; Delete page cache item
- ['execQuery()'](#execQuery) &mdash; Create a query object and execute it

### 'create()' <a name= 'create' ></a>

Create or return an instrans class

#### Signature

- **public static * * method.
- Can take the following parameter (s):
    - '$request ' ('string`) &mdash; - request text
    - '$params ' ('array`) &mdash; - query parameters
    - '$settings ' ('array`) &mdash; - additional settings
- Return [`DataStory`](../avtomon/DataStory.md) value.

### `__construct() '<a name= "__construct " ></a>

Designer

#### Signature

- **protected * * method.
- Can take the following parameter (s):
    - '$request ' ('string`) &mdash; - request text
    - '$params ' ('array`) &mdash; - query parameters
    - '$settings ' ('array`) &mdash; - settings
- It doesn't make it back.
- Throws one of the following exceptions:
    - ['ReflectionException'](http://php.net/class.ReflectionException)

### 'setIsModifying`)' <a name= "setIsModifying" ></a>

Set the query type to changing (true) or reading (false)

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$flag ' ('bool`)
- It doesn't make it back.

### 'setParams()' <a name= "setParams" ></a>

To set the query parameters

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$params ' ('array`) &mdash; - parameters
- It doesn't make it back.

### 'setCacheConnect (`'<a name= "setCacheConnect" ></a>

Install a post-connection to the cache

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$cacheConnect ' ('null`|' Redis`|`Memcached') &mdash; - connect to cache
- It doesn't make it back.

### 'setDbConnect()' <a name= "setDbConnect" ></a>

Establish connection to RBD

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$dbConnect ' ('avtomon\_PDO`|' null`)
- It doesn't make it back.

### 'getCacheQuery`)' <a name= "getCacheQuery" ></a>

Return the query cache object

#### Signature

- **protected * * method.
- Return [`CacheQuery`](../avtomon/CacheQuery.md) value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'getCacheHtml`)' <a name= 'getCacheHtml' ></a>

Return page cache object

#### Signature

- **protected * * method.
- Return [`CacheHtml`](../avtomon/CacheHtml.md) value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
      - [`avtomon\CacheHtmlException'](../avtomon/CacheHtmlException.md)

### 'getValue()' <a name= "getValue" ></a>

Return the result of the query to the RDB

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$prefix ' ('string`) &mdash; - prefix of result field names
- Returns `avtomon\DbResultItem ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

### 'deleteValue()' <a name= 'deleteValue' ></a>

Delete the database query cache item

#### Signature

- **public * * method.
- Returns `bool ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)

### 'getHtml()' <a name= "getHtml" ></a>

Return HTML

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$verifyingFilePath ' ('string`) &mdash; - path to the file on which the cache will be checked
- Returns`avtomon\HTMLResultItem ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
      - [`avtomon\CacheHtmlException'](../avtomon/CacheHtmlException.md)

### 'setHtml()' <a name= 'setHtml' ></a>

Save to cache HTML page

#### Signature

- **public * * method.
- Can take the following parameter (s):
    	- `$html`(`avtomon\HTMLResultItem`) &mdash; - the HTML
    - '$tags '(`array') &mdash; - tags
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
      - [`avtomon\CacheHtmlException'](../avtomon/CacheHtmlException.md)
      - [`avtomon\DataStoryException'](../avtomon/DataStoryException.md)

### 'deleteHtml()' <a name= 'deleteHtml' ></a>

Deleting a page cache item

#### Signature

- **public * * method.
- Returns `bool ' value.
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
      - [`avtomon\CacheHtmlException'](../avtomon/CacheHtmlException.md)

### 'execQuery()' <a name= "execQuery" ></a>

Create a query object and execute it

#### Signature

- **public static * * method.
- Can take the following parameter (s):
    - '$request ' ('string`) &mdash; - request text
    - '$params ' ('array`) &mdash; - query parameters
    - '$settings ' ('array`) &mdash; - settings
- Can return one of the following values:
    - `avtomon\DbResultItem`
    - 'null`
- Throws one of the following exceptions:
      - [`avtomon\AbstractCacheItemException'](../avtomon/AbstractCacheItemException.md)
    - `avtomon\DbResultItemException`

