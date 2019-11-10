<?php

namespace PhpNuts\Database;

use PhpNuts\File\Exception\FileNotFoundException;
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
 * @method $this    setHost(string $value)
 * @method $this    setPort(int $value)
 * @method $this    setDbname(string $value)
 * @method $this    setCharset(string $value)
 * @method $this    setUsername(string $value)
 * @method $this    setPassword(string $value)
 * @method $this    setType(string $value)
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
     * Returns a string which contains a schema reference name for the config.
     * The reference should uniquely identify the database, not the config.
     * This is important so that multiple config settings connecting to the same
     * schema, but with different credentials, each reference the same schema.
     *
     * The components for referencing a schema are:
     * - host
     * - port
     * - dbname
     *
     * @return string
     */
    public function getSchemaReference(): string
    {
        // Note: this is purely for internal use
        $components = $this->getDbname() . '@' . $this->getHost() . ':' . $this->getPort();
        return md5($components);
    }

    /**
     * @param string $path
     * @return Config
     * @throws FileNotFoundException
     */
    public static function loadFromIni(string $path): Config
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }
        $contents = parse_ini_file($path);
        return new static($contents);
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