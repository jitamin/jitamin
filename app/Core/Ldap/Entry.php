<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Ldap;

/**
 * LDAP Entry.
 */
class Entry
{
    /**
     * LDAP entry.
     *
     * @var array
     */
    protected $entry = [];

    /**
     * Constructor.
     *
     * @param array $entry
     */
    public function __construct(array $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Get all attribute values.
     *
     * @param string $attribute
     *
     * @return string[]
     */
    public function getAll($attribute)
    {
        $attributes = [];

        if (!isset($this->entry[$attribute]['count'])) {
            return $attributes;
        }

        for ($i = 0; $i < $this->entry[$attribute]['count']; $i++) {
            $attributes[] = $this->entry[$attribute][$i];
        }

        return $attributes;
    }

    /**
     * Get first attribute value.
     *
     * @param string $attribute
     * @param string $default
     *
     * @return string
     */
    public function getFirstValue($attribute, $default = '')
    {
        return isset($this->entry[$attribute][0]) ? $this->entry[$attribute][0] : $default;
    }

    /**
     * Get entry distinguished name.
     *
     * @return string
     */
    public function getDn()
    {
        return isset($this->entry['dn']) ? $this->entry['dn'] : '';
    }

    /**
     * Return true if the given value exists in attribute list.
     *
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    public function hasValue($attribute, $value)
    {
        $attributes = $this->getAll($attribute);

        return in_array($value, $attributes);
    }
}
