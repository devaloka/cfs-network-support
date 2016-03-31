<?php
/**
 * CFS Network Support Plugin.
 *
 * @author Whizark <devaloka@whiark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2016 Whizark.
 * @license GPL-2.0
 * @license GPL-3.0
 */

namespace Devaloka\Plugin\CfsNetworkSupport;

use Custom_Field_Suite;

/**
 * Class Plugin
 *
 * @package Devaloka\Plugin\CfsNetworkSupport
 */
class Plugin
{
    /**
     * @var Subscriber An instance of Subscriber.
     */
    protected $subscriber;

    /**
     * @var Api An instance of Api.
     */
    protected $api;

    /**
     * @var FieldGroup An instance of FieldGroup.
     */
    protected $fieldGroup;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $this->api        = new Api();
        $this->fieldGroup = new FieldGroup();
        $this->subscriber = new Subscriber($this);
    }

    /**
     * Gets Api of the current instance.
     *
     * @return Api The instance of Api.
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Gets FieldGroup of the current instance.
     *
     * @return FieldGroup The instance of FieldGroup.
     */
    public function getFieldGroup()
    {
        return $this->fieldGroup;
    }

    /**
     * Gets Subscriber of the current instance.
     *
     * @return Subscriber The instance of Subscriber.
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * Wraps and extends CFS API.
     */
    public function extendApi()
    {
        $this->api->wrap(Custom_Field_Suite::instance()->api);

        Custom_Field_Suite::instance()->api = $this->api;
    }

    /**
     * Restores the original CFS API.
     */
    public function restoreApi()
    {
        Custom_Field_Suite::instance()->api = $this->api->reveal();
    }

    /**
     * Wraps and extends CFS Field Group.
     */
    public function extendFieldGroup()
    {
        $this->fieldGroup->wrap(Custom_Field_Suite::instance()->field_group);

        Custom_Field_Suite::instance()->field_group = $this->fieldGroup;
    }

    /**
     * Restores the original CFS Field Group.
     */
    public function restoreFieldGroup()
    {
        Custom_Field_Suite::instance()->field_group = $this->fieldGroup->reveal();
    }

    /**
     * Boots the plugin.
     */
    public function boot()
    {
        $this->subscriber->subscribe();
    }
}
