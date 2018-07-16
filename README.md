#DataStory

DataStory is an abstraction of access to the DBMS through a PDO with a cache layer.

The main feature is the transparent caching of query results in the cache store (Redis or Memcached) and transparent cache invalidation on changing queries.

<br>

### Installation

```
composer require avtomon/datastory
```

<br>

### Mechanics of operation

Cache disinfection is performed by tagging requests: we clear a certain number of cache keys belonging to a certain tag, as tags can be anything, but in DataStory database table names are used, that is, if a certain table is changed, consider the caches that are relevant to this table .

The tagging subsystem selects the names of the tables used in the query. To do this, all the tables that can be queried are initially collected. This happens automatically.

When querying the DataStory, the type of request is determined: a read request or a data change request. Requests for data changes are INSERT, UPDATE, or DELETE, respectively, the read request is a SELECT query.

Complex queries, for example, requests from CTE to PostgreSQL, when one query can contain simultaneously reading and modifying operators are uniquely defined by changing if they contain at least one INSERT, UPDATE or DELETE statement.

In addition, there are queries that use stored procedures, for which it is difficult to predict in advance whether the request is read or modified. For such a query, you can explicitly change the flag:

```

$dataStory-> setIsModifying (true);

```

And so, if the system uniquely determined that the request is a read request, first it tries to find its result in the cache, the cache key is a hash from the pair: the query text + the serialized request parameters, or if there are no query parameters, then just the hash from the query text.

If there was no query result in the cache, or the result is not relevant: the tables from which the data was requested changed after the cache entry was saved, the system goes to the database, takes the result, returns it to the client, and saves it in the cache. While the cache saving occurs in synchronous mode, however, in the future it is planned to make it asynchronous.

If the request is changing, it is executed, then the query tables are saved as cache entries, for which the key is the table name, and the value is the table change time.

Using the module

We have a table of books (books):

| | id | title | ISBN | description |
| | --- | --- | --- | --- |
| | 1 | PHP Web Services | 9781449356569 | | |
| | 2 | Architect's Pocket Php Reference | 0973862130 | | |
| | 3 | Php String Handling Handbook | 186100835X | Book DescriptionThis book will cover all the most important tasks related to dealing with text/strings in PHP ... |

and request

```

$query = 'SELECT * FROM books WHERE id =: id';

```

If you execute the following code:

```
$dataStory = DataStory :: create ($query, ['id' => 3]);

$dataStory-> getValue ($prefix)
```

then nothing will happen to the cache - DataStory will go to the database, give the result and save it to the cache, but on subsequent requests **from this and other clients** the result will be returned from the cache.

If you then run:

```
$query = 'UPDATE books SET id = 4 WHERE id =: id';
$dataStory = DataStory :: create ($query, ['id' => 2]);

$dataStory-> getValue ($prefix)

```

then the cache for the *books * table is reset and the next read request from the * books* table will follow the data in the database.

<br>

In addition, the system contains methods for saving HTML pages and manipulating them.

```

$dataStory-> getHtml ($verifyingFilePath = '');
$dataStory-> setHtml ($html, $tags = []);
$dataStory-> deleteHtml();

```

The layout of the saved pages can be disregarded by the same rules as the results of queries, i.e.,
it is necessary to link the page to the database tables from which the information is loaded (parameter `$tags`of the`setHtml`method). In addition, for disability, you can check for changes to the page template file itself (presumably an HTML file) and to reset the page cache if the template has changed.

<br>

[Class documentation](docs_en)
