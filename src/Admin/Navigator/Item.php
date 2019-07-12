<?php

namespace Arbory\Base\Admin\Navigator;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

class Item implements Renderable, Jsonable, \JsonSerializable, Arrayable
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $anchor;

    /**
     * @var NavigableInterface
     */
    protected $navigable;

    /**
     * @var Item[]|Collection
     */
    protected $children;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var int
     */
    protected $order;

    public function __construct(NavigableInterface $navigable, $title, $anchor = null)
    {
        $this->navigable = $navigable;
        $this->title = $title;
        $this->anchor = $anchor;
        $this->reference = str_random(16);

        $this->children = collect();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     *
     * @return Item
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnchor(): ?string
    {
        return $this->anchor;
    }

    /**
     * @param  string  $anchor
     *
     * @return Item
     */
    public function setAnchor(string $anchor): self
    {
        $this->anchor = $anchor;

        return $this;
    }

    /**
     * @return Item[]|Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param  Collection|Item[]  $children
     */
    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return NavigableInterface
     */
    public function getNavigable(): NavigableInterface
    {
        return $this->navigable;
    }

    /**
     * @param  NavigableInterface  $navigable
     *
     * @return Item
     */
    public function setNavigable(NavigableInterface $navigable): self
    {
        $this->navigable = $navigable;

        return $this;
    }

    public function addChild(self $item)
    {
        $this->children->push($item);

        return $this;
    }

    public function render()
    {
        return Html::li(
            Html::link($this->title)->addAttributes(
                [
                    'data-anchor' => $this->getAnchor(),
                ]
            )
        )->append(
            Html::ul()->append(
                $this->getChildren()->map(function ($value) {
                    return $value->render();
                })->all()
            )->addClass('children')
        )->addAttributes(
            [
                'data-reference' => $this->getReference(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title'     => $this->getTitle(),
            'anchor'    => $this->getAnchor(),
            'order'     => $this->getOrder(),
            'reference' => $this->getReference(),
            'children'  => $this->children->toArray(),
        ];
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param  int  $order
     *
     * @return Item
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }
}
