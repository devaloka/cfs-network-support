<?php
/*
Plugin Name: CFS Network Support
Description: A Custom Field Suite add-on plugin that adds an ability to support Network (Multisite environment)
Version: 0.3.0
Author: Whizark
Author URI: http://whizark.com
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cfs-network-support
Domain Path: /languages
Network: true
*/

if (!defined('ABSPATH')) {
    exit;
}

use Devaloka\Plugin\CfsNetworkSupport\Plugin;

require_once __DIR__ . '/src/FieldGroup.php';
require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/Subscriber.php';
require_once __DIR__ . '/src/Plugin.php';

$cfs_network_support = new Plugin();

$cfs_network_support->boot();
