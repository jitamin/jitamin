<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Ldap;

/**
 * LDAP Entries.
 */
class Entries
{
    /**
     * LDAP entries.
     *
     * @var array
     */
    protected $entries = [];

    /**
     * Constructor.
     *
     * @param array $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Get all entries.
     *
     * @return Entry[]
     */
    public function getAll()
    {
        $entities = [];

        if (!isset($this->entries['count'])) {
            return $entities;
        }

        for ($i = 0; $i < $this->entries['count']; $i++) {
            $entities[] = new Entry($this->entries[$i]);
        }

        return $entities;
    }

    /**
     * Get first entry.
     *
     * @return Entry
     */
    public function getFirstEntry()
    {
        return new Entry(isset($this->entries[0]) ? $this->entries[0] : []);
    }
}
