<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use InvalidArgumentException;
use OutOfBoundsException;
use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlFragment;

/**
 * Class AbstractStatement
 * @package PhpNuts\Database\Sql\SqlStatement
 */
abstract class AbstractStatement
{
    /** @var SqlBlock[] */
    private $sqlBlocks = [];

    /**
     * AbstractStatement constructor.
     *
     * @param SqlBlock[] $sqlBlocks An associative array of blocks
     */
    public function __construct(array $sqlBlocks)
    {
        $this->setBlocks($sqlBlocks);
    }

    /**
     * @param string $block
     * @param SqlFragment $fragment
     * @return $this
     */
    protected function addFragment(string $block, SqlFragment $fragment): AbstractStatement
    {
        $this->getBlock($block)->addFragment($fragment);
        return $this;
    }

    /**
     * @param string $name
     * @return SqlBlock
     */
    protected function getBlock(string $name): SqlBlock
    {
        if (!$this->hasBlock($name)) {
            throw new InvalidArgumentException("SqlBlock with name '{$name}' not found.");
        }
        return $this->sqlBlocks[$name];
    }

    /**
     * Returns an array containing the names of the blocks in
     * order of priority/sequence.
     * @return string[]
     */
    abstract protected function getBlockSequence(): array;

    /**
     * @param int $index
     * @return mixed
     */
    public function getParameterAt(int $index)
    {
        $params = $this->getParameters();
        if (!array_key_exists($index, $params)) {
            throw new OutOfBoundsException("Parameter index {$index} not found");
        }
        return $params[$index];
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        $parameters = [];
        foreach ($this->getBlockSequence() as $blockName) {
            if (
                !$this->hasBlock($blockName) ||
                !$this->getBlock($blockName)->hasFragments()
            ) {
                continue;
            }
            $parameters = array_merge($parameters, $this->getBlock($blockName)->getParameters());
        }
        return $parameters;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        $sql = [];
        foreach ($this->getBlockSequence() as $blockName) {
            if (
                !$this->hasBlock($blockName) ||
                !$this->getBlock($blockName)->hasFragments()
            ) {
                continue;
            }
            $sql[] = $this->getBlock($blockName)->getSql();
        }
        return implode("\n", $sql);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasBlock(string $name): bool
    {
        return (array_key_exists($name, $this->sqlBlocks));
    }

    /**
     * @param string $blockName
     * @return bool
     */
    protected function hasFragments(string $blockName): bool
    {
        return $this->getBlock($blockName)->hasFragments();
    }

    /**
     * @param string $name
     * @param SqlBlock $sqlBlock
     * @return $this
     */
    protected function setBlock(string $name, SqlBlock $sqlBlock): AbstractStatement
    {
        $this->sqlBlocks[$name] = $sqlBlock;
        return $this;
    }

    /**
     * @param array $sqlBlocks
     * @return $this
     */
    protected function setBlocks(array $sqlBlocks): AbstractStatement
    {
        foreach ($sqlBlocks as $name => $sqlBlock) {
            $this->setBlock($name, $sqlBlock);
        }
        return $this;
    }

    /**
     * IMPORTANT: NEVER EVER...
     * This method is purely for debugging purposes and allows you to check whether
     * bound characters are set properly. It should NEVER be used directly with a
     * database query, because it's not properly prepared.
     * @return string
     */
    public function toDebugString(): string
    {
        $fragment = new SqlFragment($this->getSql(), $this->getParameters());
        return $fragment->toDebugString();
    }
}