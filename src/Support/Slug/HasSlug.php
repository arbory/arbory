<?php

namespace Arbory\Base\Support\Slug;

use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use \Spatie\Sluggable\HasSlug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getSluggableFieldName(): string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $fillable = $this->fillable;
        $fields = ['title', 'name'];

        foreach ($fields as $field) {
            if (in_array($field, $fillable)) {
                return $field;
            }
        }

        return null;
    }

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSluggableFieldName())
            ->saveSlugsTo('slug');
    }
}
