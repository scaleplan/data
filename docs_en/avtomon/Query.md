the <small>avtomon</small>

Query
=== = = 

Class to query the database

Description
-----------

Class Query

Signature
---------

- **class**.

Properties
----------

class sets the following properties:

- ['$rawSql'](#$rawSql) &mdash; the text of the SQL query template
- ['$sql'](#$sql) &mdash; query Text after processing SqlTemplater:: sql()
  - [`$rawParams`](#$rawParams) &mdash; the parameters of the request before processing SqlTemplater::sql()
- ['$params`](#$params) &mdash; query Parameters
- ['$dbConnect'](#$dbConnect) &mdash; connection to RDB
- ['$result`](#$result) &mdash; query Result

### '$rawSql '<a name= "rawSql" ></a>

The template text of the SQL query

#### Signature

- **protected * * property.
- Value `string'.

### '$sql '<a name= " sql " ></a>

The text of the query after processing SqlTemplater::sql()

#### Signature

- **protected * * property.
- Value `string'.

### '$rawParams '<a name= "rawParams" ></a>

The parameters of the request before processing SqlTemplater::sql()

#### Signature

- **protected * * property.
- Value 'array'.

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

### '$result '<a name= "result" ></a>

Query result

#### Signature

- **protected * * property.
- Can be one of the following types:
    - `avtomon\DbResultItem`
    - 'null`

Methods
-------

Class methods class:

  - [`__construct (`' ](#__construct) &mdash; Constructor
- ['getRawSql()'](#getRawSql) &mdash; Return raw query text
- ['getRawParams()'](#getRawParams) &mdash; Return raw array of query parameters
- ['getSql (`' ](#getSql) &mdash; Return the query text after processing SqlTemplater:: sql()
- ['getParams (`' ](#getParams) &mdash; Return query parameters after processing SqlTemplater:: sql()
- ['setDbConnect()'](#setDbConnect) &mdash; connect to RDB
- ['execute()'](#execute) &mdash; execute request
- ['executeAsync()'](#executeAsync) &mdash; execute request asynchronously
- ['getResult()'](#getResult) &mdash; Return request result

### `__construct() '<a name= "__construct " ></a>

Designer

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$dbConnect ' (`avtomon\_PDO`)
    - '$sql ' ('string`)
    - '$params ' ('array`)
- It doesn't make it back.
- Throws one of the following exceptions:
      - [`avtomon\QueryException'](../avtomon/QueryException.md)

### 'getRawSql()' <a name= "getRawSql" ></a>

To return the raw request body

#### Signature

- **public * * method.
- Returns 'string' value.

### 'getRawParams()' <a name= "getRawParams" ></a>

Return the raw array of query parameters

#### Signature

- **public * * method.
- Returns 'array' value.

### 'getSql()' <a name= "getSql" ></a>

Return the query text after processing SqlTemplater:: sql()

#### Signature

- **public * * method.
- Returns 'string' value.

### 'getParams()' <a name= "getParams" ></a>

Return query parameters after processing SqlTemplater:: sql()

#### Signature

- **public * * method.
- Returns 'array' value.

### 'setDbConnect()' <a name= "setDbConnect" ></a>

Establish connection to RBD

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$dbConnect ' (`avtomon\_PDO`) &mdash; - connection to RDB
- It doesn't make it back.

### 'execute()' <a name= "execute" ></a>

Run the query

#### Signature

- **public * * method.
- Can take the following parameter (s):
    - '$prefix ' ('string`) &mdash; - prefix of keys
- Returns `avtomon\DbResultItem ' value.
- Throws one of the following exceptions:
    - `avtomon\DbResultItemException`
      - [`avtomon\QueryException'](../avtomon/QueryException.md)

### 'executeAsync()' <a name= 'executeAsync' ></a>

Run the query asynchronously

#### Signature

- **public * * method.
- Returns `bool ' value.
- Throws one of the following exceptions:
      - [`avtomon\QueryException'](../avtomon/QueryException.md)

### 'getResult()' <a name= "getResult" ></a>

Return query result

#### Signature

- **public * * method.
- Returns `avtomon\DbResultItem ' value.

