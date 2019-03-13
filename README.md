# Eloquent dependency wrapper for Slim 3

This is a simple wrapper of Eloquent ORM for Slim 3. It provides a Parser class to
generate connections option from a standard database URL of PostgresSQL, MySQL or SQLite databases.

## Dependencies

* [illuminate/database](https://packagist.org/packages/illuminate/database)

## Installation

You can use the [slimcmd](https://packagist.org/packages/corephp/slim-cmd) tool to install this dependency:

```bash
slimcmd dependency:add eloquent
```

Or you can setup manually:

```bash
composer require corephp/slim-eloquent
```

## Allowed Database URLs

Te allowed formats of database connections are:

### MySQL

```bash
mysql://user:password@host:post/database
```

### PostgreSQL

```bash
postgres://user:password@host:post/database
```

### SQLite

```bash
sqlite://pato/to.database
```

## Using Parser class

The Parser class has 3 available methods to generate conection opctions for Eloquent:

* **parseConnection**: Parse connection strings for MySQL, PostgreSQL and SQLite connections.
* **parseGeneral**: Parse connections for MySQL and PostgreSQL only.
* **parseSqlite**: Parse connections for SQLite only.

The `parseConnection` and the `parseGeneral` function generates a configuration array as it shown below when pass a PostgreSQL or MySQL connection:

```php
$options = [
    'driver'    => 'mysql|pgsql|sqlite',
    'host'      => 'host',
    'port'      => 3306,
    'database'  => 'database',
    'username'  => 'user',
    'password'  => 'pass',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
```

The `parseConnection` and the `parseSqlite` function generates a configuration array as shown below when pass a SQLite connection.

```php
$options = [
    'driver' => 'sqlite',
    'database' => '/path/to.database',
    'foreign_key_constraints' => true,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
```

### Examples

```php
use CorePHP\Slim\Dependency\Database\Parser;

$settings = [
    // ...
    "database" => Parser::parseConnection(
        getenv('DATABASE_URL')
    )
    // ...
];
```

## Eloquent Wrapper

This class allows you to create the main connection to embed as dependency on Slim.
You can generate a `database.php` file your dependencies section with the code below:

```php
use CorePHP\Slim\Dependency\Database\Eloquent;

return function ($container) {
    $settings = $container->get('eloquent');
    
    $eloquent = new Eloquent();
    $eloquent->addConnection($settings);

    return $eloquent->getManager();
};
```

This generates the main connection and boot Eloquent to use the models. Also you
can setup a read and write configuration with diferent databases.

```php
use CorePHP\Slim\Dependency\Database\Eloquent;

return function ($container) {
    $eloquent = new Eloquent();
    $eloquent->addConnection($container['database'], 'default', 'r'); // read
    $eloquent->addConnection($container['database2'], 'default', 'w'); // write

    return $eloquent->getManager();
}
```

## Base Model class

This packages provides a predefined base model class that brings you the most
basic configuration of Models that you need and you can use it as shown below:

```php
use CorePHP\Slim\Dependency\Database\Model;

class User extends Model
{
    protected $table = 'users';
}
```

The Model class has 2 preconfigured functions:

* **getDateFormat**: Has the date format definition as 'Y-m-d H:i:s'
* **scopePagination**: Scope that can be aded at the end of a chain of query
  filters to generate paginated sections of a single query. This scope has 3
  parameters: $limit, $page and $links in that order.
  * `$limit`: Is the number of elements by page that will be retrived by the
    query.
  * `$page`: Page that will be retrived. I you set a number lower than 1, you
    will always have the page 1, and if you set a number greater than the total
    of pages, you will always have the last page posible.
  * `$links`: this is a boolean parameter, if you set as true, this generates an
    extra key on the resultant array named *links* that will have the pagination sections in an array like this: `[1, 2, 3, 4, 5, 6, '...', 100]`. this can Helpyou to generate a custom pagination for your datatables.

### Pagination example

```php
$items = User::where('name', 'like', '%Jhon%')
    ->pagination(10, 1, true);

// The content of items variable will be:
//
// [
//    "data" => [ ... ]
//    "pages" => [
//      "limit" => 10,
//      "pages" => 100    /* depending of the total elemtns */
//      "total" => 1000   /* total elemtns of the query */
//      "page" => 1,
//      "links" => [ 1, 2, 3, 4, 5, 6, '...', 100 ]
//    ]
// ]
```
