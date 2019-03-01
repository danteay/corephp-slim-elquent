<?php
/**
 * Eloquent connection manager
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

use \Illuminate\Database\Capsule\Manager;

/**
 * Eloquent
 *
 * @category  Class
 * @package   CorePHP_Slim_Eloquent
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2019 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-slim-eloquent/LICENSE Apache-2.0
 * @link      https://github.com/danteay/corephp-slim-eloquent
 */
class Eloquent
{
    /**
     * Manager configuration
     *
     * @var array
     */
    private $config;

    /**
     * Connection manager
     *
     * @var Manager
     */
    private $manager;

    /**
     * Eloquent Constructor
     */
    public function __construct()
    {
        $this->config = [];
        $this->manager = new Manager();
    }

    /**
     * Add new configuration options to the manager
     *
     * @param array  $options Eloquent Connection options
     * @param string $name    Connection name
     * @param string $type    Type of connection (r=read, w=write, rw=read-write)
     *
     * @return void
     */
    public function addConnection($options, $name='default', $type='rw')
    {
        if (isset($this->config[$name]) && !empty($this->config[$name])) {
            switch ($type) {
                case 'r':
                    $this->config[$name]['read'] = $options;
                    return;

                case 'w':
                    $this->config[$name]['write'] = $options;
                    return;

                case 'rw':
                default:
                    $this->config[$name] = [
                        'read' => $options,
                        'write' => $options
                    ];
                    return;
            }
        }

        $this->config[$name] = [
            'read' => $options,
            'write' => $options
        ];
    }

    /**
     * Get Connection manager
     *
     * @return Manager
     */
    public function getManager()
    {
        foreach ($this->config as $name => $options) {
            $this->manager->addConnection($options, $name);
        }

        $this->manager->setAsGlobal();
        $this->manager->bootEloquent();

        return $this->manager;
    }
}