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
 * LDAP Entries
 */
class Entries
{
    /**
     * LDAP entries
     *
     * @access protected
     * @var array
     */
    protected $entries = array();

    /**
     * Constructor
     *
     * @access public
     * @param  array $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Get all entries
     *
     * @access public
     * @return Entry[]
     */
    public function getAll()
    {
        $entities = array();

        if (! isset($this->entries['count'])) {
            return $entities;
        }

        for ($i = 0; $i < $this->entries['count']; $i++) {
            $entities[] = new Entry($this->entries[$i]);
        }

        return $entities;
    }

    /**
     * Get first entry
     *
     * @access public
     * @return Entry
     */
    public function getFirstEntry()
    {
        return new Entry(isset($this->entries[0]) ? $this->entries[0] : array());
    }
}
