<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Authorization;

use Hiject\Core\Base;
use JsonRPC\Exception\AccessDeniedException;

/**
 * Class UserAuthorization.
 */
class UserAuthorization extends Base
{
    public function check($class, $method)
    {
        if ($this->userSession->isLogged() && !$this->apiAuthorization->isAllowed($class, $method, $this->userSession->getRole())) {
            throw new AccessDeniedException('You are not allowed to access to this resource');
        }
    }
}
