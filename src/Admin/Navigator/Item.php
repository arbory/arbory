<?php

namespace Arbory\Base\Admin\Navigator;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

class Item implements Renderable, Jsonable, \JsonSerializable, Arrayable
{
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

    /**
     * @param string $title
     * @param string $anchor
     */
    public function __construct(protected NavigableInterface $navigable, protected $title, protected $anchor = null)
    {
        $this->reference = Str::random(16);

        $this->children = collect();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

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
    public function setChildren(\Illuminate\Support\Collection|array $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getNavigable(): NavigableInterface
    {
        return $this->navigable;
    }

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
                $this->getChildren()->map(fn($value) => $value->render())->all()
            )->addClass('children')
        )->addAttributes(
            [
                'data-reference' => $this->getReference(),
            ]
        );
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
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
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }
}
