<?php

namespace PhpNuts\Database\Sql\SqlModifier;

use PHPUnit\Framework\TestCase;

/**
 * Class SqlParameterModifierTest
 * @package PhpNuts\Database\Sql\SqlModifier
 */
class SqlParameterModifierTest extends TestCase
{
    /**
     * As a basic test a single anonymous parameter should remain
     * unchanged by the SqlParameterModifier.
     */
    public function testSingleParameter()
    {
        $originalParams = ['Geoff'];
        $expectedParams = ['Geoff'];
        $originalSql = 'name = ?';
        $expectedSql = 'name = ?';
        $modifier = new SqlParameterModifier($originalSql, $originalParams);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParams, $modifier->getParameters());
    }

    /**
     * A single use named parameter should be converted to a named parameter.
     * This is important to avoid naming conflicts when multiple micro-statements
     * are merged into a single statement.
     */
    public function testNamedParameter()
    {
        $originalParams = ['name' => 'Geoff'];
        $expectedParams = ['Geoff'];
        $originalSql = 'name = :name';
        $expectedSql = 'name = ?';
        $modifier = new SqlParameterModifier($originalSql, $originalParams);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParams, $modifier->getParameters());
    }

    /**
     * Tests that a named parameter may be re-used within the same micro-statement.
     * The Parameter modifier should then duplicate the parameter as many times as
     * is needed for the statement to then use the '?' parameter.
     */
    public function testDuplicateNamedParameter()
    {
        $originalParameters = ['name' => 'Geoff'];
        $expectedParameters = ['Geoff', 'Geoff'];
        $originalSql = 'name = :name OR lastName = :name';
        $expectedSql = 'name = ? OR lastName = ?';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }

    /**
     * Test that we're able to mix use of names and anonymous parameters.
     * We should be able to supply a re-usable named parameter and any number
     * of anonymous parameters in the same micro-statement.
     */
    public function testMixedNamedAndAnonymousParameters()
    {
        $originalParameters = ['accountId' => 7, 'Geoff'];
        $expectedParameters = [7, 7, 'Geoff'];
        $originalSql = '(accountId = :accountId OR merchantId = :accountId) AND name = ?';
        $expectedSql = '(accountId = ? OR merchantId = ?) AND name = ?';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }

    /**
     * Test that we're able to use named parameters in conjunction with
     * anonymous parameters in a mixed sequence.
     */
    public function testMixedSequenceNamedAndAnonymousParameters()
    {
        $originalParameters = ['accountId' => 7, 'Geoff', 'Ting'];
        $expectedParameters = [7, 'Geoff', 7, 'Ting'];
        $originalSql = '(accountId = :accountId AND name = ?) OR (accountId = :accountId AND name = ?)';
        $expectedSql = '(accountId = ? AND name = ?) OR (accountId = ? AND name = ?)';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }

    /**
     * Test that we can supply a named parameter as an array which is to be
     * converted into a sequence of anonymous parameters.
     */
    public function testNamedArrayParameters()
    {
        $originalParameters = ['ids' => [1, 2, 3, 4]];
        $expectedParameters = [1, 2, 3, 4];
        $originalSql = 'productId in :ids';
        $expectedSql = 'productId in (?, ?, ?, ?)';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }

    /**
     * Test that anonymous parameters supplied as an array may also be converted
     * into a sequence.
     */
    public function testAnonymousArrayParameters()
    {
        $originalParameters = [[1, 2, 3, 4]];
        $expectedParameters = [1, 2, 3, 4];
        $originalSql = 'productId in ?';
        $expectedSql = 'productId in (?, ?, ?, ?)';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }

    /**
     * A test of both named, anonymous and array parameters all mixed together.
     */
    public function testArrayParametersMixed()
    {
        $originalParameters = [
            'accountId' => 7,
            'cartIds' => [1, 2, 3, 4],
            'N',
            ['new', 'pending', 'partial']
        ];
        $expectedParameters = [7, 7, 1, 2, 3, 4, 'N', 'new', 'pending', 'partial'];
        $originalSql = '(accountId = :accountId OR merchantId = :accountId) AND cartId IN :cartIds AND deleted = ? AND status IN ?';
        $expectedSql = '(accountId = ? OR merchantId = ?) AND cartId IN (?, ?, ?, ?) AND deleted = ? AND status IN (?, ?, ?)';
        $modifier = new SqlParameterModifier($originalSql, $originalParameters);
        $this->assertEquals($expectedSql, $modifier->getSql());
        $this->assertEquals($expectedParameters, $modifier->getParameters());
    }
}