<?php

namespace PhpNuts\Database;

use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package PhpNuts\Database
 */
class ConfigTest extends TestCase
{
    /**
     * Test that the config settings can be loaded from an INI file.
     */
    public function testIniLoading()
    {
        $config = Config::loadFromIni(__DIR__ . '/testConfig.ini');
        $this->assertEquals('datahost', $config->getHost());
    }

    /**
     * Test that the schema reference is the same for
     * connection configs with different username and password credentials.
     * This important for schema management.
     *
     * We'll try some pretend credentials:
     * 1. Pretend default credentials for general web use
     * 2. Pretend credentials for managing database updates
     */
    public function testSchemaReference()
    {
        // 1. Pretend default credentials for general web use
        $config1 = new Config([
            'host' => 'datahost',
            'dbname' => 'evance',
            'username' => 'evance-production',
            'password' => '12345678'
        ]);
        $config1Reference = $config1->getSchemaReference();

        // 2. Pretend credentials for managing database updates
        $config2 = new Config([
            'host' => 'datahost',
            'dbname' => 'evance',
            'username' => 'evance-updates',
            'password' => '12345678'
        ]);
        $config2Reference = $config2->getSchemaReference();
        $this->assertEquals($config1Reference, $config2Reference);
    }
}