#Database

DataStory is an abstraction of access to the database by means of PDO with a layer of cache. 

The main feature is transparent caching of query results in cache storage (Redis or Memcached) and transparent cache invalidation on modifying queries.

<br>

### Installation

```
composer require avtomon/data
```

<br>

### Mechanics of work

Cache invalidation is done by means of query tagging: we clear a certain number of cache keys belonging to a certain tag, as tags can be anything, but database table names are used in the DataStory, that is, when you change a certain table to be considered irrelevant cache that belong to this table.

The tagging subsystem allocates the names of the tables used in the query. To do this, initially gather all the tables that can be queried. It's automatic. 

When you query the Database, you define the type of query: read request or data change request. Requests to change data is INSERT, UPDATE or DELETE, respectively, a read request is A select request. 

Complex queries, such as CTE queries to PostgreSQL, where a single query can contain both read and modify statements, are uniquely identified as modifying if they contain at least one INSERT, UPDATE, or DELETE statement.

In addition, there are queries that use stored procedures that are difficult to predict in advance whether the query is read or modify. For such a query, you can pass a modifying flag explicitly:

```

$dataStory->setIsModifying(true);

```

So, if the system has clearly determined that the request is a read request, it first tries to find its result in the cache, the cache key is a hash from the pair: the request text + serialized request parameters, or if there are no query parameters, then just a hash from the request text. 

If there is no query result in the cache or this result is not relevant: the tables from which data is requested have changed after the cache item is saved, the system goes to the database, takes the result from there, returns it to the client and stores it in the cache. As long as the cache is saved in synchronous mode, however, it is planned to make it asynchronous in the future.

If the query is mutating, it is executed, then the query tables are stored as cache items for which the key is the table name and the value is the time of table modification.

The use of the module

Have a table of books (books):

/id/title | ISBN/description |
| --- | --- | --- | --- |
| 1/PHP Web Services/9781449356569 | |
| 2/Architect's Pocket Php Reference | 0973862130 | |
| 3 | Php String Handling Handbook | 186100835X/Book DescriptionThis book will cover all the most important tasks related to dealing with text/strings in PHP... |

and request

```

$query = 'SELECT * FROM books WHERE id = :id';

```

If you run the following code:

```
$dataStory = DataStory:: create($query, ['id' = > 3]);

$data->getValue($prefix)
```

there will be nothing to cache yet - DataStory goes to the database, gives the result and saves it to the cache, but at subsequent requests **from this and other clients** the result will be returned from the cache.

If then run:

```
$query = 'UPDATE books SET id = 4 WHERE id = :id';
$dataStory = DataStory:: create($query, ['id' = > 2]);

$data->getValue($prefix)

```

the cache for the *books *table will be reset and the next read request from the*books* table will go for data in the database.

<br>

In addition, the system contains methods for saving HTML-pages and manipulations with them.

```

$dataStory->getHtml($verifyingFilePath = ");
$dataStory->setHtml($html, $tags = []);
$dataStory->deleteHtml();

```

The layout of saved pages can be disabled according to the same rules as the results of queries, i.e.
you must link the page to the database tables from which the information is loaded (the `$tags`parameter of the `setHtml`method). In addition, you can check for changes to the page template file itself (presumably the HTML file) and reset the page cache if the template has changed.

<br>

[Class documentation](docs_en)
