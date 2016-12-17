<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Category Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Category Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Rinvex\Category;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Categorizable
{
    /**
     * Get all of the categories for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Get the category list.
     *
     * @return array
     */
    public function categoriesList(): array
    {
        return $this->categories()->lists('title', 'id')->toArray();
    }

    /**
     * Categorize the given category(ies) to the entity.
     *
     * @param mixed $categories
     *
     * @return self
     */
    public function categorize($categories): self
    {
        // Array of category slugs
        $categories = $this->hydrateIfString($categories);

        // Single category model
        if ($categories instanceof Category) {
            $categories = [$categories->id];
        }

        // Fire the category adding event
        static::$dispatcher->fire('rinvex.fort.category.adding', [$this, $categories]);

        // Assign categories
        $this->categories()->syncWithoutDetaching($categories);

        // Fire the category added event
        static::$dispatcher->fire('rinvex.fort.category.added', [$this, $categories]);

        return $this;
    }

    /**
     * Recategorize the given category(ies) to the entity.
     *
     * @param mixed $categories
     *
     * @return self
     */
    public function recategorize($categories): self
    {
        // Array of category slugs
        $categories = $this->hydrateIfString($categories);

        // Single category model
        if ($categories instanceof Category) {
            $categories = [$categories->id];
        }

        // Fire the category syncing event
        static::$dispatcher->fire('rinvex.fort.category.syncing', [$this, $categories]);

        // Assign categories
        $this->categories()->sync($categories);

        // Fire the category synced event
        static::$dispatcher->fire('rinvex.fort.category.synced', [$this, $categories]);

        return $this;
    }

    /**
     * Uncategorize the given category(ies) from the entity.
     *
     * @param mixed $categories
     *
     * @return self
     */
    public function uncategorize($categories): self
    {
        // Array of category slugs
        $categories = $this->hydrateIfString($categories);

        // Fire the category removing event
        static::$dispatcher->fire('rinvex.fort.category.removing', [$this, $categories]);

        // Detach categories
        $this->categories()->detach($categories);

        // Fire the category removed event
        static::$dispatcher->fire('rinvex.fort.category.removed', [$this, $categories]);

        return $this;
    }

    /**
     * Determine if the entity has (one of) the given categories.
     *
     * @param mixed $categories
     *
     * @return bool
     */
    public function hasCategory($categories): bool
    {
        // Single category slug
        if (is_string($categories)) {
            return $this->categories->contains('slug', $categories);
        }

        // Single category model
        if ($categories instanceof Category) {
            return $this->categories->contains('slug', $categories->slug);
        }

        // Array of category slugs
        if (is_array($categories) && is_string($categories[0])) {
            return $this->categories->pluck('slug')->intersect($categories)->isEmpty();
        }

        // Collection of category models
        if ($categories instanceof Collection) {
            return ! $categories->intersect($this->categories->pluck('slug'))->isEmpty();
        }

        return false;
    }

    /**
     * Alias for `hasCategory` method.
     *
     * @param mixed $categories
     *
     * @return bool
     */
    public function hasAnyCategory($categories): bool
    {
        return $this->hasCategory($categories);
    }

    /**
     * Determine if the entity has all of the given categories.
     *
     * @param mixed $categories
     *
     * @return bool
     */
    public function hasAllCategories($categories): bool
    {
        // Single category slug
        if (is_string($categories)) {
            return $this->categories->contains('slug', $categories);
        }

        // Single category model
        if ($categories instanceof Category) {
            return $this->categories->contains('slug', $categories->slug);
        }

        // Array of category slugs OR Collection of category models
        if ($categories instanceof Collection || (is_array($categories) && is_string($categories[0]))) {
            return $this->categories->pluck('slug')->count() == count($categories)
                   && $this->categories->pluck('slug')->diff($categories)->isEmpty();
        }

        return $this->categories->pluck('id')->count() == count($categories)
               && $this->categories->pluck('id')->diff($categories)->isEmpty();
    }

    /**
     * Hydrate categories if it's string based.
     *
     * @param $categories
     *
     * @return array
     */
    protected function hydrateIfString($categories)
    {
        return is_string($categories) || (is_array($categories) && is_string($categories[0]))
            ? Category::whereIn('slug', (array) $categories)->get() : $categories;
    }
}
