<?php
/**
 * CFS API wrapper.
 *
 * @author Whizark <devaloka@whizark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2016 Whizark.
 * @license GPL-2.0
 * @license GPL-3.0
 */

namespace Devaloka\Plugin\CfsNetworkSupport;

use cfs_api;
use Custom_Field_Suite;

/**
 * Class Api
 *
 * @package Devaloka\Plugin\CfsNetworkSupport
 */
class Api
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
     * @var cfs_api An instance of cfs_api.
     */
    protected $rawApi;

    /**
     * Api constructor.
     *
     * @param cfs_api|null $rawApi An instance of cfs_api.
     */
    public function __construct(cfs_api $rawApi = null)
    {
        $this->rawApi = $rawApi;
    }

    /**
     * Invokes a method of the original CFS API in a static context.
     *
     * @param string $name The method name.
     * @param mixed[] $arguments The method arguments.
     *
     * @return mixed The return value from the original method.
     */
    public static function __callStatic($name, $arguments)
    {
        return forward_static_call_array(['cfs_api', $name], $arguments);
    }

    /**
     * Invokes a method of the original CFS API in an object context.
     *
     * @param string $name The method name.
     * @param mixed[] $arguments The method arguments.
     *
     * @return mixed The return value from the original method.
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(['cfs_api', $name], $arguments);
    }

    /**
     * Gets the value from a property of the original CFS API.
     *
     * @param $name string The name of the property.
     *
     * @return mixed The value of the property.
     */
    public function __get($name)
    {
        return $this->rawApi->{$name};
    }

    /**
     * Sets a value to a property of the original CFS API.
     *
     * @param $name string The name of the property.
     * @param $value mixed The value to set.
     */
    public function __set($name, $value)
    {
        $this->rawApi->{$name} = $value;
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
        return isset($this->rawApi->{$name});
    }

    /**
     * Is invoked when unset() is used on inaccessible members.
     *
     * @param $name string The name.
     */
    public function __unset($name)
    {
        unset($this->rawApi->{$name});
    }

    /**
     * Sets an instance of cfs_api to be wrapped.
     *
     * @param cfs_api $rawApi An instance of cfs_api.
     */
    public function wrap(cfs_api $rawApi)
    {
        $this->rawApi = $rawApi;
    }

    /**
     * Gets the original CFS API.
     *
     * @return cfs_api|null The original CFS API.
     */
    public function reveal()
    {
        return ($this->rawApi !== null) ? $this->rawApi : Custom_Field_Suite::instance()->api;
    }

    /**
     * Retrieves field value(s).
     *
     * @param string $fieldName A field name.
     * @param int|null $postId The Post ID which the field belongs to.
     * @param mixed[] $options Options for the API.
     *
     * @return mixed|mixed[]|null The value(s) of the field.
     */
    public function get($fieldName, $postId = null, array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $value = $this->rawApi->get($fieldName, $postId, $options);

        $this->restore_blog();

        return $value;
    }

    /**
     * Retrieves a field value.
     *
     * @param string $fieldName A field name.
     * @param int|null $postId The Post ID which the field belongs to.
     * @param mixed[] $options Options for the API.
     *
     * @return mixed|null The value of the field.
     */
    public function get_field($fieldName, $postId = null, array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $field = $this->rawApi->get_field($fieldName, $postId, $options);

        $this->restore_blog();

        return $field;
    }

    /**
     * Retrieves all field values for a specific Post.
     *
     * @param int|null $postId The Post ID.
     * @param mixed[] $options Options for the API.
     *
     * @return mixed[]|null The field values of the Post.
     */
    public function get_fields($postId, array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $fields = $this->rawApi->get_fields($postId, $options);

        $this->restore_blog();

        return $fields;
    }

    /**
     * Retrieves properties for one or more fields.
     *
     * @param string|null $fieldName The field name.
     * @param int|null $postId The Post ID which the field belongs to.
     *
     * @return mixed[] The properties.
     */
    public function get_field_info($fieldName = null, $postId = null)
    {
        return $this->rawApi->get_field_info($fieldName, $postId);
    }

    /**
     * Retrieves referenced field values using relationship fields.
     *
     * @param int|null $postId The Post ID which the field belongs to.
     * @param mixed[] $options Options for the API.
     *
     * @return mixed[] The referenced field values.
     */
    public function get_reverse_related($postId, array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $values = $this->rawApi->get_reverse_related($postId, $options);

        $this->restore_blog();

        return $values;
    }

    /**
     * Saves field values.
     *
     * @param mixed[] $fields The field data to save.
     * @param mixed[] $post The Post data to save the field values.
     * @param mixed[] $options Options for the API.
     *
     * @return int|\WP_Error The Post ID on success, 0 or WP_Error on failure.
     */
    public function save_fields(array $fields = [], array $post = [], array $options = [])
    {
        $siteId = isset($options['site_id']) ? (int) $options['site_id'] : get_current_blog_id();

        $this->switch_to_blog($siteId);

        $postId = $this->rawApi->save_fields($fields, $post, $options);

        $this->restore_blog();

        return $postId;
    }

    /**
     * Finds input fields.
     *
     * @param mixed[] $params Parameters for the query.
     *
     * @return array[] The input fields.
     */
    public function find_input_fields(array $params = [])
    {
        return $this->rawApi->find_input_fields($params);
    }

    /**
     * Retrieves input fields and values for a specific Post ID.
     *
     * @param mixed[] $params Parameters for the query.
     *
     * @return mixed The input fields and values.
     */
    public function get_input_fields(array $params = [])
    {
        return $this->rawApi->get_input_fields($params);
    }

    /**
     * Determines which field groups to use for the current Post.
     *
     * @param mixed[]|int $params Parameters for the rule to determine the field group, or Post ID.
     * @param bool $skipRoles Whether ignore user Roles.
     *
     * @return mixed[] The field groups.
     */
    public function get_matching_groups($params, $skipRoles = false)
    {
        return $this->rawApi->get_matching_groups($params, $skipRoles);
    }

    /**
     * Switches the current site to another site for CFS.
     *
     * @param int $siteId The site ID to switch.
     */
    public function switch_to_blog($siteId)
    {
        $this->cache = $this->rawApi->cache;

        switch_to_blog($siteId);

        $this->rawApi->cache = isset($this->networkWideCache[$siteId]) ? $this->networkWideCache[$siteId] : [];
    }

    /**
     * Restores the current site for CFS.
     */
    public function restore_blog()
    {
        $this->networkWideCache[get_current_blog_id()] = $this->rawApi->cache;

        restore_current_blog();

        $this->rawApi->cache = $this->cache;
    }
}
