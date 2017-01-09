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

/**
 * LDAP Query.
 */
class Query
{
    /**
     * LDAP client.
     *
     * @var Client
     */
    protected $client = null;

    /**
     * Query result.
     *
     * @var array
     */
    protected $entries = [];

    /**
     * Constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute query.
     *
     * @param string $baseDn
     * @param string $filter
     * @param array  $attributes
     *
     * @return Query
     */
    public function execute($baseDn, $filter, array $attributes)
    {
        if (DEBUG && $this->client->hasLogger()) {
            $this->client->getLogger()->debug('BaseDN='.$baseDn);
            $this->client->getLogger()->debug('Filter='.$filter);
            $this->client->getLogger()->debug('Attributes='.implode(', ', $attributes));
        }

        $sr = ldap_search($this->client->getConnection(), $baseDn, $filter, $attributes);
        if ($sr === false) {
            return $this;
        }

        $entries = ldap_get_entries($this->client->getConnection(), $sr);
        if ($entries === false || count($entries) === 0 || $entries['count'] == 0) {
            return $this;
        }

        $this->entries = $entries;

        if (DEBUG && $this->client->hasLogger()) {
            $this->client->getLogger()->debug('NbEntries='.$entries['count']);
        }

        return $this;
    }

    /**
     * Return true if the query returned a result.
     *
     * @return bool
     */
    public function hasResult()
    {
        return !empty($this->entries);
    }

    /**
     * Get LDAP Entries.
     *
     * @return Entries
     */
    public function getEntries()
    {
        return new Entries($this->entries);
    }
}
