<?php

namespace Pkscraper;

use Pkscraper\Items\Item;

class Bag
{
    protected $origin;

    /**
     * @var \Pkscraper\Items\Item[]
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function __construct(string $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin): void
    {
        $this->origin = $origin;
    }

    /**
     * @return \Pkscraper\Items\Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param Item ...$items
     */
    public function setItems(Item ...$items)
    {
        $this->items = $items;
    }

    public function build()
    {
        foreach ($this->items as $item) {
            $item->build();
        }
    }

    /**
     * @param string $code
     * @return Item
     */
    public function getItemByCode(string $code)
    {
        foreach ($this->items as $item) {
            if ($item->getCode() === $code) {
                return $item;
            }
        }
    }


}