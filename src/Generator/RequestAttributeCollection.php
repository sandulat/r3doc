<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Illuminate\Support\Str;

final class RequestAttributeCollection
{
    /**
     * Collection items.
     *
     * @var \Sandulat\R3doc\Generator\RequestAttribute[]
     */
    private $items = [];

    /**
     * Add an item to the collection.
     *
     * @param \Sandulat\R3doc\Generator\RequestAttribute $attribute
     * @return self
     */
    public function add(RequestAttribute $attribute): self
    {
        if (! $this->skipItem($attribute)) {
            array_push($this->items, $attribute);
        }

        return $this;
    }

    /**
     * Remove an item by name from collection.
     *
     * @param string $name
     * @return self
     */
    public function removeByName(string $name): self
    {
        $this->items = array_filter($this->items, static function (RequestAttribute $item) use ($name) {
            return $item->getName() !== $name;
        });

        return $this;
    }

    /**
     * Get collection items.
     *
     * @return \Sandulat\R3doc\Generator\RequestAttribute[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Checks whether the item should be skipped.
     *
     * @param RequestAttribute $attribute
     * @return bool
     */
    protected function skipItem(RequestAttribute $attribute): bool
    {
        return preg_match('/.\*$/', $attribute->getName()) > 0;
    }

    /**
     * Nest wildcard attributes.
     *
     * @return self
     */
    public function nestAttributes(): self
    {
        foreach ($this->getItems() as $item) {
            $itemAttributes = $item->getAttributes();

            $this->nestItemAttributes($item);

            $itemAttributes->nestAttributes();
        }

        return $this;
    }

    /**
     * Nest item attributes.
     *
     * @param RequestAttribute $item
     * @return void
     */
    public function nestItemAttributes(RequestAttribute $item): void
    {
        $scopeName = $item->getName().'.*.';

        foreach ($this->getItems() as $comparedItem) {
            $shouldNest = Str::startsWith($comparedItem->getName(), $scopeName);

            if ($shouldNest) {
                $comparedItemName = $comparedItem->getName();

                $this->removeByName($comparedItemName);

                $comparedItem->changeName(str_replace($scopeName, '', $comparedItemName));

                $item->getAttributes()->add($comparedItem);
            }
        }
    }
}
