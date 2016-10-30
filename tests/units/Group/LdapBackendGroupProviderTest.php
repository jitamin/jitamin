<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Group\LdapBackendGroupProvider;

class LdapBackendGroupProviderTest extends Base
{
    public function testGetLdapGroupPattern()
    {
        $this->setExpectedException('LogicException', 'LDAP group filter empty, check the parameter LDAP_GROUP_FILTER');

        $backend = new LdapBackendGroupProvider($this->container);
        $backend->getLdapGroupPattern('test');
    }
}
