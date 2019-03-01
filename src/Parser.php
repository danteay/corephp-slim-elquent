<?php
/**
 * Parse library to generate connection options
 *
 * PHP Version 7.1
 *
 * @category  Class
 * @package   CorePHP_Slim_Eloquent
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2019 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-slim-eloquent/LICENSE Apache-2.0
 * @link      https://github.com/danteay/corephp-slim-eloquent
 */

namespace CorePHP\Slim\Dependency\Database;

/**
 * Parser
 *
 * @category  Class
 * @package   CorePHP_Slim_Eloquent
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2019 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-slim-eloquent/LICENSE Apache-2.0
 * @link      https://github.com/danteay/corephp-slim-eloquent
 */
class Parser
{
    /**
     * general parse URL for MySQL and Postgres connections
     *
     * @param string $url Connection string
     *
     * @return array
     */
    public static function parseGeneral($url)
    {
        $options = [
            'driver'    => null,
            'host'      => null,
            'port'      => null,
            'database'  => null,
            'username'  => null,
            'password'  => null,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        $aux = explode('://', $url);

        switch ($aux[0]) {
            case 'postgres':
                $options['driver'] = 'pgsql';
                $options['port'] = 5432;
                break;
            case 'mysql':
                $options['driver'] = 'mysql';
                $options['port'] = 3306;
                break;
            default:
                throw new \Exception('Invalid MySQL or Postgres database url');
        }

        $aux = explode('@', $aux[1]);
        $auth = $aux[0];
        $host = $aux[1];

        $auth = explode(':', $auth);

        if (count($auth) == 0) {
            throw new \Exception('Invalid username for database url');
        }

        $options['username'] = $auth[0];

        if (count($auth) == 2) {
            $options['password'] = $auth[1];
        }

        $host = explode('/', $host);

        if (count($host) < 2) {
            throw new \Exception('Malformed database url');
        }

        $options['database'] = $host[1];
        $host = explode(':', $host[0]);

        $options['host'] = $host[0];

        if (count($host) == 2) {
            $options['port'] = intval($host[1]);
        }

        return $options;
    }

    /**
     * Parse for a SQLite connection string
     *
     * @param string $url Connection string
     *
     * @return array
     */
    public static function parseSqlite($url)
    {
        $options = [
            'driver' => 'sqlite',
            'database' => null,
            'foreign_key_constraints' => true,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        $path = str_replace('sqlite://', '', $url);

        $options['database'] = '/' . $path;

        return $options;
    }

    /**
     * Parse general connections
     *
     * @param string $url Connection string
     *
     * @throws \Exception On invalid connection string
     *
     * @return array
     */
    public static function parseConnection($url)
    {
        if (preg_match('/postgres:\/\//', $url)) {
            return self::parseGeneral($url);
        } else if (preg_match('/mysql:\/\//', $url)) {
            return self::parseGeneral($url);
        } else if (preg_match('/sqlite:\/\//', $url)) {
            return self::parseSqlite($url);
        } else {
            throw new \Exception("Invalid connection type");
        }
    }
}