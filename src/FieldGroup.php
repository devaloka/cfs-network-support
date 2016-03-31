<?php
/**
 * CFS Field Group wrapper.
 *
 * @author Whizark <devaloka@whizark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2016 Whizark.
 * @license GPL-2.0
 * @license GPL-3.0
 */

namespace Devaloka\Plugin\CfsNetworkSupport;

use cfs_field_group;
use Custom_Field_Suite;

/**
 * Class FieldGroup
 *
 * @package Devaloka\Plugin\CfsNetworkSupport
 */
class FieldGroup
{
    /**
     * @var mixed[] The cache to store the original CFS cache.
     */
    public $cache = [];

    /**
     * @var mixed[] The Network specific cache (Multisite environment).
     */
    protected $networkWideCache = [];

    /**
     * @var cfs_field_group An instance of cfs_field_group.
     */
    protected $rawFieldGroup;

    /**
     * FieldGroup constructor.
     *
     * @param cfs_field_group|null $rawFieldGroup An instance of cfs_field_group.
     */
    public function __construct(cfs_field_group $rawFieldGroup = null)
    {
        $this->rawFieldGroup = $rawFieldGroup;
    }

    /**
     * Invokes a method of the original CFS Field Group in a static context.
     *
     * @param string $name The method name.
     * @param mixed[] $arguments The method arguments.
     *
     * @return mixed The return value from the original method.
     */
    public static function __callStatic($name, $arguments)
    {
        return forward_static_call_array(['cfs_field_group', $name], $arguments);
    }

    /**
     * Invokes a method of the original CFS Field Group in an object context.
     *
     * @param string $name The method name.
     * @param mixed[] $arguments The method arguments.
     *
     * @return mixed The return value from the original method.
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(['cfs_field_group', $name], $arguments);
    }

    /**
     * Gets the value from a property of the original CFS Field Group.
     *
     * @param $name string The name of the property.
     *
     * @return mixed The value of the property.
     */
    public function __get($name)
    {
        return $this->rawFieldGroup->{$name};
    }

    /**
     * Sets a value to a property of the original CFS Field Group.
     *
     * @param $name string The name of the property.
     * @param $value mixed The value to set.
     */
    public function __set($name, $value)
    {
        $this->rawFieldGroup->{$name} = $value;
    }

    /**
     * Is triggered by calling isset() or empty() on inaccessible members.
     *
     * @param $name string The name.
     *
     * @return bool True if accessible, false otherwise.
     */
    public function __isset($name)
    {
        return isset($this->rawFieldGroup->{$name});
    }

    /**
     * Is invoked when unset() is used on inaccessible members.
     *
     * @param $name string The name.
     */
    public function __unset($name)
    {
        unset($this->rawFieldGroup->{$name});
    }

    /**
     * Sets an instance of cfs_field_group to be wrapped.
     *
     * @param cfs_field_group $rawFieldGroup An instance of cfs_field_group.
     */
    public function wrap(cfs_field_group $rawFieldGroup)
    {
        $this->rawFieldGroup = $rawFieldGroup;
    }

    /**
     * Gets the original CFS Field Group.
     *
     * @return cfs_field_group|null The original CFS Field Group.
     */
    public function reveal()
    {
        return ($this->rawFieldGroup !== null) ? $this->rawFieldGroup : Custom_Field_Suite::instance()->field_group;
    }

    /**
     * Queries/Loads field group(s) from database/cache.
     *
     * @param mixed[] $options The options for the query.
     *
     * @return mixed[] The array of the result set.
     */
    public function load_field_groups(array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $fieldGroups = $this->rawFieldGroup->load_field_groups();

        $this->restore_blog();

        return $fieldGroups;
    }

    /**
     * Imports field group(s) from import code.
     *
     * @param mixed[] $options The options for the import.
     *
     * @return string The response HTML as the result of the import.
     */
    public function import(array $options)
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        switch_to_blog($siteId);

        $html = $this->rawFieldGroup->import($options);

        restore_current_blog();

        return $html;
    }

    /**
     * Exports field group(s) as an array.
     *
     * @param mixed[] $options The options for the export.
     *
     * @return mixed[] The array of the exported field group(s).
     */
    public function export(array $options)
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        switch_to_blog($siteId);

        $fieldGroups = $this->rawFieldGroup->export($options);

        restore_current_blog();

        return $fieldGroups;
    }

    /**
     * Stores field group settings to database.
     *
     * @param mixed[] $params The parameters for the settings to save.
     * @param mixed[] $options The options for the query.
     */
    public function save(array $params = [], array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        switch_to_blog($siteId);

        $this->rawFieldGroup->save($params);

        restore_current_blog();
    }

    /**
     * Switches the current site to another site for CFS.
     *
     * @param int $siteId The site ID to switch.
     */
    public function switch_to_blog($siteId)
    {
        $this->cache = $this->rawFieldGroup->cache;

        switch_to_blog($siteId);

        $this->rawFieldGroup->cache = isset($this->networkWideCache[$siteId]) ? $this->networkWideCache[$siteId] : [];
    }

    /**
     * Restores the current site for CFS.
     */
    public function restore_blog()
    {
        $this->networkWideCache[get_current_blog_id()] = $this->rawFieldGroup->cache;

        restore_current_blog();

        $this->rawFieldGroup->cache = $this->cache;
    }
}
