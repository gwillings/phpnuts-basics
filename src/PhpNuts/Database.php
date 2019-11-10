<?php

namespace PhpNuts;

use PDO;
use PDOStatement;
use PhpNuts\Database\Config;
use PhpNuts\Database\Exception\DuplicateInstanceException;
use PhpNuts\Database\Exception\InstanceNotFoundException;
use PhpNuts\Database\Sql\SqlParam;

/**
 * Class Database
 *
 * @package PhpNuts
 */
class Database
{
    /**
     * Represents the default Database connection instance name/reference.
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
     * The default fetch mode for the database connection.
     * @var int
     */
    private $fetchMode = PDO::FETCH_OBJ;

    /**
     * The reference name for the connection instance.
     * @var string
     */
    private $reference;

    /**
     * A cache of database instances.
     * An associative array where the key represents the instance reference of the database.
     * @var Database[]
     */
    private static $instances = [];

    /**
     * Database constructor.
     * @param Config $config
     * @param string $reference The instance reference name.
     */
    private function __construct(Config $config, string $reference = self::DEFAULT)
    {
        $this->config = $config;
        $this->reference = $reference;
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
     * Creates a prepared statement and binds parameters onto it.
     * @param string $sql
     * @param array $parameters
     * @return PDOStatement
     */
    public function createStatement(string $sql, array $parameters = []): PDOStatement
    {
        $statement = $this->getConnection()->prepare($sql);
        foreach ($parameters as $index => $parameter) {
            // Assume named parameter if the index is a string
            $key = is_string($index) ? $index : $index + 1;
            $type = SqlParam::getPdoType($parameter);
            $statement->bindValue($key, $parameter, $type);
        }
        $statement->setFetchMode($this->fetchMode);
        return $statement;
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
     * Returns the default fetch mode for the Database instance.
     * @return int
     */
    public function getFetchMode(): int
    {
        return $this->fetchMode;
    }

    /**
     * Returns a Database instance identified by its reference.
     * If the instance reference does not exist an Instance Not Found exception is thrown.
     *
     * @param string $reference [optional] The database reference. Omit for the default Database instance.
     * @return Database
     * @throws InstanceNotFoundException
     */
    public static function getInstance(string $reference = self::DEFAULT): Database
    {
        if (!self::hasInstance($reference)) {
            throw new InstanceNotFoundException($reference);
        }
        return self::$instances[$reference];
    }

    /**
     * Returns the referential name identifying the Database instance.
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Returns TRUE if a Database instance exists with $instanceName,
     * or FALSE the instance has not been defined/spawned.
     * @param string $reference [optional] The database instance reference. Omit for the default instance.
     * @return bool
     */
    public static function hasInstance(string $reference = self::DEFAULT): bool
    {
        return array_key_exists($reference, self::$instances);
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
        return ($this->getReference() === self::DEFAULT);
    }

    /**
     * Create a new named Database instance with associated connection settings.
     * If you are creating your default Database connection instance you can omit
     * the $reference parameter.
     *
     * If a duplicate $reference is encountered with an existing Database instance
     * a Duplicate Instance exception is thrown.
     *
     * @param Config $config
     * @param string $reference [optional] Identifies the database instance. Omit if the default database instance.
     * @return static
     * @throws DuplicateInstanceException
     */
    public static function newInstance(Config $config, string $reference = self::DEFAULT): Database
    {
        if (self::hasInstance($reference)) {
            throw new DuplicateInstanceException($reference);
        }
        self::$instances[$reference] = new static($config, $reference);
        return self::$instances[$reference];
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

    /**
     * Set the default fetch mode for the database instance.
     * @param int $fetchMode
     * @return $this
     */
    public function setFetchMode(int $fetchMode): Database
    {
        $this->fetchMode = $fetchMode;
        return $this;
    }
}