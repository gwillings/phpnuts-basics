<?php

namespace PhpNuts;

use PhpNuts\Database\Config;
use PhpNuts\Database\Exception\DuplicateInstanceException;
use PhpNuts\Database\Exception\InstanceNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseTest
 * @package PhpNuts
 */
class DatabaseTest extends TestCase
{
    /**
     * Essentially all we're testing is that creating a new instance
     * works without causing and exception and that we can check the
     * instance name represent the default instance.
     *
     * 1. Verify the instance name represents default.
     * 2. Verify the instance isDefault() returns TRUE.
     * 3. Verify that the instance is not automatically connected.
     *
     * For this test we can get away with using a blank Database Config.
     * @throws DuplicateInstanceException Should NOT be thrown!
     */
    public function testDatabaseInstance()
    {
        $database = Database::newInstance(new Config());
        // 1. Verify the instance name represents default
        $this->assertEquals(Database::DEFAULT, $database->getReference());
        // 2. Verify the instance isDefault() returns TRUE
        $this->assertTrue($database->isDefault());
        // 3. Verify that the instance is not automatically connected
        $this->assertFalse($database->isConnected());
    }

    /**
     * Test that two instances of a database connection with the same
     * instance name cannot be created. In this event we expect a Duplicate Instance Exception.
     * Again, we can get away with using a blank Database Config.
     * @throws DuplicateInstanceException Due to duplicated instance names.
     */
    public function testDuplicateInstances()
    {
        $this->expectException(DuplicateInstanceException::class);
        $config = new Config();
        Database::newInstance($config);
        Database::newInstance($config);
    }

    /**
     * Test that the default Database instance can be recalled
     * having created an newInstance() using getInstance().
     * @throws DuplicateInstanceException Should NOT be thrown!
     * @throws InstanceNotFoundException Should NOT be thrown!
     */
    public function testInstanceRecall()
    {
        $database1 = Database::newInstance(new Config());
        $database2 = Database::getInstance();
        $this->assertEquals(Database::DEFAULT, $database1->getReference());
        $this->assertEquals($database1->getReference(), $database2->getReference());
    }

    /**
     * Assert that getting an instance with the incorrect name
     * throws an Instance Not Found Exception.
     * @throws DuplicateInstanceException Should NOT be thrown!
     * @throws InstanceNotFoundException Should be thrown due to incorrect name.
     */
    public function testIncorrectInstanceName()
    {
        $this->expectException(InstanceNotFoundException::class);
        Database::newInstance(new Config());
        Database::getInstance('incorrect-name');
    }
}