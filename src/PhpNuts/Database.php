<?php

namespace PhpNuts;

use PDO;
use PhpNuts\Database\Config;
use PhpNuts\Database\Exception\DuplicateInstanceException;
use PhpNuts\Database\Exception\InstanceNotFoundException;

/**
 * Class Database
 *
 * @package PhpNuts
 */
class Database
{
    /**
     * Represents the default Database connection instance name.
     * @var string
     */
    const DEFAULT = '__default';

    /**
     * The configuration settings for the database connection.
     * @var Config
     */
    private $config;

    /**
     * The active PDO connection.
     * @var PDO|null
     */
    private $connection;

    /**
     * The reference name for the connection instance.
     * @var string
     */
    private $instanceName;

    /**
     * A cache of database instances.
     * An associative array where the key represents the instance name of the database.
     * @var Database[]
     */
    private static $instances = [];

    /**
     * Database constructor.
     * @param Config $config
     * @param string $instanceName
     */
    private function __construct(Config $config, string $instanceName = self::DEFAULT)
    {
        $this->config = $config;
        $this->instanceName = $instanceName;
    }

    /**
     * Destroy the PDO resource on destruct.
     * @return void
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @return bool
     */
    public function connect(): bool
    {
        $config = $this->getConfig();
        $pdo = new PDO($config->toDsn(), $config->getUsername(), $config->getPassword());
        // todo: connection options
        $this->setConnection($pdo);
        return true;
    }

    /**
     * Disconnect the internal PDO connection.
     * @return $this
     */
    public function disconnect(): Database
    {
        return $this->setConnection(null);
    }

    /**
     * Returns the Database connection Config settings for this instance.
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Returns a connected PDO database adapter (hopefully),
     * or NULL if the Database is not connected.
     * @return null|PDO
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    /**
     * @param string $instanceName
     * @return Database
     * @throws InstanceNotFoundException
     */
    public static function getInstance(string $instanceName = self::DEFAULT): Database
    {
        if (!self::hasInstance($instanceName)) {
            throw new InstanceNotFoundException($instanceName);
        }
        return self::$instances[$instanceName];
    }

    /**
     * Returns the name of the Database instance.
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * Returns TRUE if a Database instance exists with $instanceName,
     * or FALSE the instance has not been defined/spawned.
     * @param string $instanceName
     * @return bool
     */
    public static function hasInstance(string $instanceName = self::DEFAULT): bool
    {
        return array_key_exists($instanceName, self::$instances);
    }

    /**
     * Returns TRUE if the connection has been attempted to the Database
     * but cannot determine if a connection has been lost.
     * @return bool
     */
    public function isConnected(): bool
    {
        return ($this->connection instanceof PDO);
    }

    /**
     * Returns TRUE if this instance represents the default database,
     * or FALSE otherwise.
     * @return bool
     */
    public function isDefault(): bool
    {
        return ($this->getInstanceName() === self::DEFAULT);
    }

    /**
     * Create a new named Database instance with associated connection settings.
     * If you are creating your default Database connection instance you can omit
     * the $instanceName parameter.
     *
     * @param Config $config
     * @param string $instanceName [optional] Omit for the default database instance.
     * @return static
     * @throws DuplicateInstanceException
     */
    public static function newInstance(Config $config, string $instanceName = self::DEFAULT): Database
    {
        if (self::hasInstance($instanceName)) {
            throw new DuplicateInstanceException($instanceName);
        }
        self::$instances[$instanceName] = new static($config, $instanceName);
        return self::$instances[$instanceName];
    }

    /**
     * Set the PDO connection for this Database instance.
     * @param PDO|null $pdo
     * @return $this
     */
    public function setConnection(?PDO $pdo): Database
    {
        $this->connection = $pdo;
        return $this;
    }
}