# Rinvex Category

**Rinvex Category** is a polymorphic Laravel package, for category management. You can categorize any eloquent model with ease, and utilize the power of nested sets.

[![Packagist](https://img.shields.io/packagist/v/rinvex/category.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/category)
[![VersionEye Dependencies](https://img.shields.io/versioneye/d/php/rinvex:category.svg?label=Dependencies&style=flat-square)](https://www.versioneye.com/php/rinvex:category/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/category.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/category/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/category.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/category)
[![Travis](https://img.shields.io/travis/rinvex/category.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/category)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/f2dca242-eb65-4bcc-8481-cd27ea16c804.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/f2dca242-eb65-4bcc-8481-cd27ea16c804)
[![StyleCI](https://styleci.io/repos/66037019/shield)](https://styleci.io/repos/66037019)
[![License](https://img.shields.io/packagist/l/rinvex/category.svg?label=License&style=flat-square)](https://github.com/rinvex/category/blob/develop/LICENSE)


## Installation

Install via `composer require rinvex/category`, then include the following service provider to the `'providers'` array inside `app/config/app.php`:
``` php
Rinvex\Category\CategoryServiceProvider::class
```

Finally you'll need to execute migrations via the following command:
```
php artisan migrate
```

## Usage

### Create Your Model

Simply create a new eloquent model, and use `Categorizable` trait:
``` php
<?php

namespace App;

use Rinvex\Category\Categorizable;
use Rinvex\Category\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Categorizable;
}

$post = new Post();
```

### Get Model Category List

The `categoriesList` method gets an array with ids and names of categories (useful for drop-downs):
``` php
$post->categoriesList();
```

### Categorize Your Model

The `categorize` method **attaches** the Post Model to the given Categories:
``` php
$post->categorize(['category-1', 'custom-category-123', 'new-category']);
```

### Uncategorize Your Model

The `uncategorize` method **dettaches** the Post Model from the given Categories:
``` php
$post->uncategorize(Category::find(1));
```

### Synchronize Your Model's Categories

The `recategorize` method **synchronizes** the Post Model's cagtegories:
``` php
$post->recategorize(Category::whereIn('id', [1, 2, 3]));
```

### Check If Your Model Has Any Given Category

The `hasCategory` and it's alias `hasAnyCategory` methods determines if your model has (one of) the given categories:
``` php
$post->hasCategory([1, 2, 3]);
$post->hasAnyCategory(41);
```

### Check If Your Model Has All Given Categories

The `hasAllCategories` method determines if your model has all of the given categories:
``` php
$post->hasAllCategories(['category-1', 'custom-category-123', 'new-category']);
```

### Get All Models In Category

The `entries` method gets all of the entries that are assigned to this category:
``` php
$category = new Category();
$category->entries(\App\Post::class);
```

> **Notes:** 
> - Almost all categorizable methods can accept variety of inputs, such as: 1) single category slug; 2) single category id; 3) single category model; 4) category collection; 5) array of category slugs; 6) array of category ids; **This package is smart enough to recognize and deal with whatever inputs n these methods.**
> - This package is built upon the effecient nested-sets package [lazychaser/laravel-nestedset](https://github.com/lazychaser/laravel-nestedset), check it out for further details on how to create / update / delete / list categories and their attached items (categorizables).


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
