# CFS Network Support

[![Latest Stable Version][stable-image]][stable-url]
[![Latest Unstable Version][unstable-image]][unstable-url]
[![License][license-image]][license-url]
[![Build Status][travis-image]][travis-url]

A [Custom Field Suite][custom-field-suite] add-on plugin that adds an ability to
support Network (Multisite environment).

## Manual Installation

1.  Just copy all files into `<ABSPATH>wp-content/plugins/cfs-network-support/`.

## Manual Installation (as a Must-Use plugin)

1.  Just copy all files into
    `<ABSPATH>wp-content/mu-plugins/cfs-network-support/`.

2.  Move `cfs-network-support/loader/50-cfs-network-support-loader.php`
    into `<ABSPATH>wp-content/mu-plugins/`.

## Installation via Composer

1.  Install via Composer.

    ```sh
    composer require devaloka/cfs-network-support
    ```

## Installation via Composer (as a Must-Use plugin)

1.  Install via Composer.

    ```sh
    composer require devaloka/cfs-network-support
    ```

2.  Move `cfs-network-support` directory into
    `<ABSPATH>wp-content/mu-plugins/`.

3.  Move `cfs-network-support/loader/50-cfs-network-support-loader.php`
    into `<ABSPATH>wp-content/mu-plugins/`.

[custom-field-suite]: https://ja.wordpress.org/plugins/custom-field-suite/

[stable-image]: https://poser.pugx.org/devaloka/cfs-network-support/v/stable
[stable-url]: https://packagist.org/packages/devaloka/cfs-network-support

[unstable-image]: https://poser.pugx.org/devaloka/cfs-network-support/v/unstable
[unstable-url]: https://packagist.org/packages/devaloka/cfs-network-support

[license-image]: https://poser.pugx.org/devaloka/cfs-network-support/license
[license-url]: https://packagist.org/packages/devaloka/cfs-network-support

[travis-image]: https://travis-ci.org/devaloka/cfs-network-support.svg?branch=master
[travis-url]: https://travis-ci.org/devaloka/cfs-network-support
