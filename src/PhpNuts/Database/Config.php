<?php

namespace PhpNuts\Database;

use PhpNuts\Literal\BasicObject;

/**
 * Class Config
 *
 * @method string   getHost()
 * @method int      getPort()
 * @method string   getDbname()
 * @method string   getCharset()
 * @method string   getUsername()
 * @method string   getPassword()
 * @method string   getType()
 *
 * @package PhpNuts\Database
 */
class Config extends BasicObject
{
    /**
     * Config constructor.
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct([
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbname' => '',
            'charset' => 'utf8mb4',
            'username' => '',
            'password' => '',
            'type' => 'mysql'
        ]);
        $this->merge($properties);
    }

    /**
     * @return string
     */
    public function toDsn(): string
    {
        return "{$this->getType()}:"
            . "host={$this->getHost()};"
            . "dbname={$this->getDbname()};"
            . "charset={$this->getCharset()};"
            . "port={$this->getPort()}";
    }
}