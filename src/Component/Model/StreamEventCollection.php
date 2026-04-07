<?php

namespace Ustal\StreamHub\Component\Model;

class StreamEventCollection implements \IteratorAggregate, \Countable
{
    /** @var StreamEvent[] */
    private array $items = [];

    public function __construct(StreamEvent ...$items)
    {
        $this->items = $items;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
