# Rinvex Category

**Rinvex Category** is a polymorphic Laravel package, for category management. You can categorize any eloquent model with ease, and utilize the power of **[Nested Sets](https://github.com/lazychaser/laravel-nestedset)**, and the awesomeness of **[Sluggable](https://github.com/spatie/laravel-sluggable)**, and **[Translatable](https://github.com/spatie/laravel-translatable)** models out of the box.

[![Packagist](https://img.shields.io/packagist/v/rinvex/category.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/category)
[![VersionEye Dependencies](https://img.shields.io/versioneye/d/php/rinvex:category.svg?label=Dependencies&style=flat-square)](https://www.versioneye.com/php/rinvex:category/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/category.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/category/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/category.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/category)
[![Travis](https://img.shields.io/travis/rinvex/category.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/category)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/f2dca242-eb65-4bcc-8481-cd27ea16c804.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/f2dca242-eb65-4bcc-8481-cd27ea16c804)
[![StyleCI](https://styleci.io/repos/66037019/shield)](https://styleci.io/repos/66037019)
[![License](https://img.shields.io/packagist/l/rinvex/category.svg?label=License&style=flat-square)](https://github.com/rinvex/category/blob/develop/LICENSE)


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/category
    ```

2. Execute migrations via the following command:
    ```
    php artisan migrate --path="vendor/rinvex/category/database/migrations"
    ```

3. **Optionally** add the following service provider to the `'providers'` array inside `app/config/app.php`:
    ```php
    Rinvex\Category\CategoryServiceProvider::class
    ```
    
   And then you can publish the migrations by running the following command:
    ```shell
    php artisan vendor:publish --tag="migrations"
    ```

4. Done!


## Usage

### Create Your Model

Simply create a new eloquent model, and use `Categorizable` trait:
``` php
<?php

namespace App;

use Rinvex\Category\Category;
use Rinvex\Category\Categorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    use Categorizable;

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
```

### Manage Your Categories

```php
use Rinvex\Category\Category;

// Create a new category by name                            // Create a new category by translation
Category::createByName('My New Category');                  Category::createByName('تصنيف جديد', 'ar');

// Get existing category by name                            // Get existing category by translation
Category::findByName('My New Category');                    Category::findByName('تصنيف جديد', 'ar');

// Find category by name or create if not exists
Category::findByNameOrCreate('My Brand New Category');

// Find many categories by name or create if not exists
Category::findManyByNameOrCreate(['My Brand New Category 2', 'My Brand New Category 3']);
```

> **Notes:** since **Rinvex Category** extends and utilizes other awesome packages, checkout the following documentations for further details:
> - Powerful Nested Sets using [`kalnoy/nestedset`](https://github.com/lazychaser/laravel-nestedset)
> - Automatic Slugging using [`spatie/laravel-sluggable`](https://github.com/spatie/laravel-sluggable)
> - Translatable out of the box using [`spatie/laravel-translatable`](https://github.com/spatie/laravel-translatable)

### Manage Your Categorizable Model

The API is intutive and very straightfarwad, so let's give it a quick look:
```php
// Instantiate your model
$post = new \App\Post();

// Attach given categories to the model
$post->categorize(['my-new-category', 'my-brand-new-category']);

// Detach given categories from the model
$post->uncategorize(['my-new-category']);

// Sync given categories with the model (remove attached categories and reattach given ones)
$post->recategorize(['my-new-category', 'my-brand-new-category']);

// Remove all attached categories
$post->recategorize(null);

// Get attached categories collection
$post->categories;

// Get attached categories array with slugs and names 
$post->categoryList();

// Check model if has any given categories
$post->hasCategory(['my-new-category', 'my-brand-new-category']);

// Check model if has any given categories
$post->hasAllCategories(['my-new-category', 'my-brand-new-category']);
```

### Advanced Usage

- **Rinvex Category** auto generates slugs and auto detect and insert default translation for you, but you still can pass it explicitly through normal eloquent `create` method, as follows:
    ```php
    Category::create(['name' => ['en' => 'My New Category'], 'slug' => 'custom-category-slug']);
    ```

- All categorizable methods that accept list of tags are smart enough to handle almost all kind of inputs, for example you can pass single category slug, single category id, single category model, an array of category slugs, an array of category ids, or a collection of category models. It will check input type and behave accordingly. Example:
    ```php
    $post->hasCategory(1);
    $post->hasCategory([1,2,4]);
    $post->hasCategory('my-new-category');
    $post->hasCategory(['my-new-category', 'my-brand-new-category']);
    $post->hasCategory(Category::where('slug', 'my-new-category')->first());
    $post->hasCategory(Category::whereIn('id', [5,6,7)->get());
    ```
    **Rinvex Category** can understand any of the above parameter syntax and interpret it correctly, same for other methods in this package.

- It's very easy to get all models attached to certain category as follows:
    ```php
    $category = Category::find(1);
    $category->entries(\App\Post::class);
    ```

- Since **Rinvex Category** is built on top of the effecient nested-sets package [`kalnoy/nestedset`](https://github.com/lazychaser/laravel-nestedset), you can list, create, update, and delete categories smoothly without any hassle, and sure it manage all the nested-set stuff automatically for you.


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](http://chat.rinvex.com)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

We want to ensure that this package is secure for everyone. If you've discovered a security vulnerability in this package, we appreciate your help in disclosing it to us in a [responsible manner](https://en.wikipedia.org/wiki/Responsible_disclosure).

Publicly disclosing a vulnerability can put the entire community at risk. If you've discovered a security concern, please email us at [security@rinvex.com](mailto:security@rinvex.com). We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. We consider correspondence sent to [security@rinvex.com](mailto:security@rinvex.com) our highest priority, and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a security hotfix release will be deployed as soon as possible.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2017 Rinvex LLC, Some rights reserved.
