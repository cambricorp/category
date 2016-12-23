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

use Spatie\Sluggable\HasSlug;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\SlugOptions;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model implements Sortable
{
    use HasSlug;
    use NodeTrait;
    use SortableTrait;
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The sortable settings.
     *
     * @var array
     */
    public $sortable = ['order_column_name' => 'order'];

    /**
     * Get all attached models of the given class to the category.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'categorizable');
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get category tree.
     *
     * @return array
     */
    public static function tree(): array
    {
        return static::get()->toTree()->toArray();
    }

    /**
     * Find category by name or create if not exists.
     *
     * @param string      $name
     * @param string|null $locale
     *
     * @return static
     */
    protected static function findByNameOrCreate(string $name, string $locale = null): Category
    {
        $locale = $locale ?? app()->getLocale();
        $category = static::findByName($name, $locale);

        if (! $category) {
            $category = static::create([
                'name' => [$locale => $name],
            ]);
        }

        return $category;
    }

    /**
     * Find category by name.
     *
     * @param string      $name
     * @param string|null $locale
     *
     * @return static|null
     */
    public static function findByName(string $name, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return static::query()
                     ->where("name->{$locale}", $name)
                     ->first();
    }
}
