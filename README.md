# Cakephp ORM Shell

Currently this is an implementation of [ORM Cache Shell](http://book.cakephp.org/3.0/en/console-and-shells/orm-cache.html)
as a standalone package so can it be used with [cakephp/orm](https://packagist.org/packages/cakephp/orm).

## Installation

```bash
composer require hkt/cakephp-orm-shell
```

> Not yet registered... Thoughts on different names ?

## Usage

For the current time copy and paste the below code to run the shell.
Remember you need to do certain modifications.

```php
<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Hkt\Command\ORMCacheBuild;
use Hkt\Command\ORMCacheClear;
use Symfony\Component\Console\Application;

$path = __DIR__ . DIRECTORY_SEPARATOR;
define('CACHE', __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);

Configure::config('default', new PhpConfig($path));
Configure::load('app', 'default', false);

Cache::config(Configure::consume('Cache'));
ConnectionManager::config($config['Datasources']);

$application = new Application();
$application->add(new ORMCacheBuild());
$application->add(new ORMCacheClear());
$application->run();
```

A few assumptions are currently made.
You have something similar to [app.php](https://github.com/cakephp/app/blob/ffae1545333bf32c2afb2dc8fddbdc4faf627ab1/config/app.default.php#L83-L115) as below.

```php
<?php
return [
    /**
     * Configure basic information about the application.
     *
     * - namespace - The namespace to find app classes under.
     * - encoding - The encoding used for HTML + database connections.     
     */
    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',        
    ],    

    /**
     * Configure the cache adapters.
     */
    'Cache' => [
        'default' => [
            'className' => 'File',
            'path' => CACHE,
        ],

        /**
         * Configure the cache used for general framework caching.
         * Translation cache files are stored with this configuration.
         * Duration will be set to '+1 year' in bootstrap.php when debug = false
         */
        '_cake_core_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_core_',
            'path' => CACHE . 'persistent/',
            'serialize' => true,
            'duration' => '+2 minutes',
        ],

        /**
         * Configure the cache for model and datasource caches. This cache
         * configuration is used to store schema descriptions, and table listings
         * in connections.
         * Duration will be set to '+1 year' in bootstrap.php when debug = false
         */
        '_cake_model_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_model_',
            'path' => CACHE . 'models/',
            'serialize' => true,
            'duration' => '+2 minutes',
        ],
    ],    

    /**
     * Connection information used by the ORM to connect
     * to your application's datastores.
     * Drivers include Mysql Postgres Sqlite Sqlserver
     * See vendor\cakephp\cakephp\src\Database\Driver for complete list
     */
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            /**
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'my_app',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,

            /**
             * Set identifier quoting to true if you are using reserved words or
             * special characters in your table or column names. Enabling this
             * setting will result in queries built using the Query Builder having
             * identifiers quoted when creating SQL. It should be noted that this
             * decreases performance because each query needs to be traversed and
             * manipulated before being executed.
             */
            'quoteIdentifiers' => false,

            /**
             * During development, if using MySQL < 5.6, uncommenting the
             * following line could boost the speed at which schema metadata is
             * fetched from the database. It can also be set directly with the
             * mysql configuration directive 'innodb_stats_on_metadata = 0'
             * which is the recommended value in production environments
             */
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ],

        /**
         * The test connection is used during the test suite.
         */
        'test' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ],
    ],
];
```

The `app.php` is located in the path given to `PhpConfig`.
Feel free to customize to your needs.

In the future probably we have a shell command which can do the work.

## Tests

Oh you like, I too. Please send a PR if you have some time. If not eventually
you will see something.

Thank you

Hari KT
