# CFS Network Support

[![Latest Stable Version][stable-image]][stable-url]
[![Latest Unstable Version][unstable-image]][unstable-url]
[![License][license-image]][license-url]
[![Build Status][travis-image]][travis-url]

A [Custom Field Suite][custom-field-suite] add-on plugin that adds an ability to
support Network (Multisite environment).

## Installation

### Manual Installation

1.  Just copy all files into `<ABSPATH>wp-content/plugins/cfs-network-support/`.

### Manual Installation (as a Must-Use plugin)

1.  Just copy all files into
    `<ABSPATH>wp-content/mu-plugins/cfs-network-support/`.

2.  Move `cfs-network-support/loader/50-cfs-network-support-loader.php`
    into `<ABSPATH>wp-content/mu-plugins/`.

### Installation via Composer

1.  Install via Composer.

    ```sh
    composer require devaloka/cfs-network-support
    ```

### Installation via Composer (as a Must-Use plugin)

1.  Install via Composer.

    ```sh
    composer require devaloka/cfs-network-support
    ```

2.  Move `cfs-network-support` directory into
    `<ABSPATH>wp-content/mu-plugins/`.

3.  Move `cfs-network-support/loader/50-cfs-network-support-loader.php`
    into `<ABSPATH>wp-content/mu-plugins/`.

## Usage

Just pass Site ID with `site_id` key to CFS API options.

### Get field value(s)

```php
echo CFS()->get('first_name', false, ['site_id' => 1]);
```

See also [get - Custom Field Suite](http://docs.customfieldsuite.com/api/get.html).

```php
$related_ids = CFS()->get_reverse_related($post->ID, [
    'field_name' => 'related_events',
    'post_type'  => 'news',
    'site_id'    => 1,    
]);
```

### Save field value(s)

```php
$field_data = ['first_name' => 'Matt'];
$post_data  = ['ID' => 678];

CFS()->save($field_data, $post_data, ['site_id' => 1]);
```

See also [save - Custom Field Suite](http://docs.customfieldsuite.com/api/save.html).

### Get reverse-related Posts

```php
$related_ids = CFS()->get_reverse_related($post->ID, [
    'field_name' => 'related_events',
    'post_type'  => 'news',
    'site_id'    => 1,    
]);
```

See also [get\_reverse\_related - Custom Field Suite](http://docs.customfieldsuite.com/api/get_reverse_related.html).

[custom-field-suite]: https://ja.wordpress.org/plugins/custom-field-suite/

[stable-image]: https://poser.pugx.org/devaloka/cfs-network-support/v/stable
[stable-url]: https://packagist.org/packages/devaloka/cfs-network-support

[unstable-image]: https://poser.pugx.org/devaloka/cfs-network-support/v/unstable
[unstable-url]: https://packagist.org/packages/devaloka/cfs-network-support

[license-image]: https://poser.pugx.org/devaloka/cfs-network-support/license
[license-url]: https://packagist.org/packages/devaloka/cfs-network-support

[travis-image]: https://travis-ci.org/devaloka/cfs-network-support.svg?branch=master
[travis-url]: https://travis-ci.org/devaloka/cfs-network-support
