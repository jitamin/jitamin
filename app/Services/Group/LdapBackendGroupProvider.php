<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Group;

use Jitamin\Core\Base;
use Jitamin\Core\Group\GroupBackendProviderInterface;
use Jitamin\Core\Ldap\Client as LdapClient;
use Jitamin\Core\Ldap\ClientException as LdapException;
use Jitamin\Core\Ldap\Group as LdapGroup;
use LogicException;

/**
 * LDAP Backend Group Provider.
 */
class LdapBackendGroupProvider extends Base implements GroupBackendProviderInterface
{
    /**
     * Find a group from a search query.
     *
     * @param string $input
     *
     * @return LdapGroupProvider[]
     */
    public function find($input)
    {
        try {
            $ldap = LdapClient::connect();

            return LdapGroup::getGroups($ldap, $this->getLdapGroupPattern($input));
        } catch (LdapException $e) {
            $this->logger->error($e->getMessage());

            return [];
        }
    }

    /**
     * Get LDAP group pattern.
     *
     * @param string $input
     *
     * @return string
     */
    public function getLdapGroupPattern($input)
    {
        if (LDAP_GROUP_FILTER === '') {
            throw new LogicException('LDAP group filter empty, check the parameter LDAP_GROUP_FILTER');
        }

        return sprintf(LDAP_GROUP_FILTER, $input);
    }
}
