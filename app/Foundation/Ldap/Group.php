<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Ldap;

use Jitamin\Group\LdapGroupProvider;
use LogicException;

/**
 * LDAP Group Finder.
 */
class Group
{
    /**
     * Query.
     *
     * @var Query
     */
    protected $query;

    /**
     * Constructor.
     *
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Get groups.
     *
     * @static
     *
     * @param Client $client
     * @param string $query
     *
     * @return LdapGroupProvider[]
     */
    public static function getGroups(Client $client, $query)
    {
        $self = new static(new Query($client));

        return $self->find($query);
    }

    /**
     * Find groups.
     *
     * @param string $query
     *
     * @return array
     */
    public function find($query)
    {
        $this->query->execute($this->getBasDn(), $query, $this->getAttributes());
        $groups = [];

        if ($this->query->hasResult()) {
            $groups = $this->build();
        }

        return $groups;
    }

    /**
     * Build groups list.
     *
     * @return array
     */
    protected function build()
    {
        $groups = [];

        foreach ($this->query->getEntries()->getAll() as $entry) {
            $groups[] = new LdapGroupProvider($entry->getDn(), $entry->getFirstValue($this->getAttributeName()));
        }

        return $groups;
    }

    /**
     * Ge the list of attributes to fetch when reading the LDAP group entry.
     *
     * Must returns array with index that start at 0 otherwise ldap_search returns a warning "Array initialization wrong"
     *
     * @return array
     */
    public function getAttributes()
    {
        return array_values(array_filter([
            $this->getAttributeName(),
        ]));
    }

    /**
     * Get LDAP group name attribute.
     *
     * @return string
     */
    public function getAttributeName()
    {
        if (!LDAP_GROUP_ATTRIBUTE_NAME) {
            throw new LogicException('LDAP full name attribute empty, check the parameter LDAP_GROUP_ATTRIBUTE_NAME');
        }

        return strtolower(LDAP_GROUP_ATTRIBUTE_NAME);
    }

    /**
     * Get LDAP group base DN.
     *
     * @return string
     */
    public function getBasDn()
    {
        if (!LDAP_GROUP_BASE_DN) {
            throw new LogicException('LDAP group base DN empty, check the parameter LDAP_GROUP_BASE_DN');
        }

        return LDAP_GROUP_BASE_DN;
    }
}
