<?php
/**
 * CFS Network Support Subscriber.
 *
 * @author Whizark <devaloka@whiark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2016 Whizark.
 * @license GPL-2.0
 * @license GPL-3.0
 */

namespace Devaloka\Plugin\CfsNetworkSupport;

/**
 * Class Subscriber
 *
 * @package Devaloka\Plugin\CfsNetworkSupport
 */
class Subscriber
{
    /**
     * @var Plugin An instance of CFS Network Support plugin.
     */
    protected $plugin;

    /**
     * Subscriber constructor.
     *
     * @param Plugin|null $plugin An instance of CFS Network Support plugin.
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Subscribes action(s).
     */
    public function subscribe()
    {
        add_action('cfs_init', [$this, 'onCfsInit']);
    }

    /**
     * Unsubscribes action(s).
     */
    public function unsubscribe()
    {
        remove_action('cfs_init', [$this, 'onCfsInit']);
    }

    /**
     * The `cfs_init` action handler for CFS Network Support plugin.
     */
    public function onCfsInit()
    {
        $this->plugin->extendFieldGroup();
        $this->plugin->extendApi();
    }
}
