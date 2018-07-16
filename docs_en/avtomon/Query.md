<small> avtomon </small>

Query
=====

Class of organization of database queries

Description
-----------

Class Query

Signature
---------

- **class**.

Properties
----------

class sets the following properties:

  - [`$rawSql`](#$rawSql) &mdash; The text of the SQL query template
  - [`$sql`](#$sql) &mdash; The request text after processing SqlTemplater :: sql()
  - [`$rawParams`](#$rawParams) &mdash; Query parameters before processing SqlTemplater :: sql()
  - [`$params`](#$params) &mdash; Query Parameters
  - [`$dbConnect`](#$dbConnect) &mdash; Connection to the RDB
  - [`$result`](#$result) &mdash; Query result

### `$rawSql`<a name="rawSql"> </a>

The text of the SQL query template

#### Signature

- **protected** property.
- The value of `string`.

### `$sql`<a name="sql"> </a>

The request text after processing SqlTemplater :: sql()

#### Signature

- **protected** property.
- The value of `string`.

### `$rawParams`<a name="rawParams"> </a>

Query parameters before processing SqlTemplater :: sql()

#### Signature

- **protected** property.
- The value of `array`.

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

### `$result`<a name="result"> </a>

Query result

#### Signature

- **protected** property.
- Can be one of the following types:
  - `avtomon\DbResultItem`
  - `null`

Methods
-------

Class methods class:

  - [`__construct()`](#__construct) &mdash; Constructor
  - [`getRawSql()`](#getRawSql) &mdash; Return unprocessed query text
  - [`getRawParams()`](#getRawParams) &mdash; Return an unhandled array of query parameters
  - [`getSql()`](#getSql) &mdash; Return the text of the request after processing SqlTemplater :: sql()
  - [`getParams()`](#getParams) &mdash; Return query parameters after processing SqlTemplater :: sql()
  - [`setDbConnect()`](#setDbConnect) &mdash; Establish connection to the RDB
  - [`execute()`](#execute) &mdash; Run Query
  - [`executeAsync()`](#executeAsync) &mdash; Query asynchronously
  - [`getResult()`](#getResult) &mdash; Return result of request

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$dbConnect`(`avtomon\CachePDO`)
  - `$sql`(`string`)
  - `$params`(`array`)
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `getRawSql()`<a name="getRawSql"> </a>

Return unprocessed query text

#### Signature

- **public** method.
Returns `string`value.

### `getRawParams()`<a name="getRawParams"> </a>

Return an unhandled array of query parameters

#### Signature

- **public** method.
Returns the `array`value.

### `getSql()`<a name="getSql"> </a>

Return the text of the request after processing SqlTemplater :: sql()

#### Signature

- **public** method.
Returns `string`value.

### `getParams()`<a name="getParams"> </a>

Return query parameters after processing SqlTemplater :: sql()

#### Signature

- **public** method.
Returns the `array`value.

### `setDbConnect()`<a name="setDbConnect"> </a>

Establish connection to the RDB

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$dbConnect`(`avtomon\CachePDO`) - connection to the RDB
- Returns nothing.

### `execute()`<a name="execute"> </a>

Run Query

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$prefix`(`string`) - the prefix of the keys
- Returns the `avtomon\DbResultItem`value.
- Throws one of the following exceptions:
  - `avtomon\DbResultItemException`
  - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `executeAsync()`<a name="executeAsync"> </a>

Query asynchronously

#### Signature

- **public** method.
- Returns the `bool`value.
- Throws one of the following exceptions:
  - [`avtomon\QueryException`](../avtomon/QueryException.md)

### `getResult()`<a name="getResult"> </a>

Return result of request

#### Signature

- **public** method.
- Returns the `avtomon\DbResultItem`value.

