<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Policy;

use Jitamin\Core\Base;
use JsonRPC\Exception\AccessDeniedException;

/**
 * Class MethodPolicy.
 */
class MethodPolicy extends Base
{
    private $userSpecificMethods = [
        'getMe',
        'getMyDashboard',
        'getMyActivity',
        'createMyPrivateProject',
        'getMyProjectsList',
        'getMyProjects',
        'getMyOverdueTasks',
    ];

    /**
     * Determine if the current user has permissions.
     *
     * @param string $method
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    public function check($method)
    {
        if (!$this->userSession->isLogged() && in_array($method, $this->userSpecificMethods)) {
            throw new AccessDeniedException('This method is not available with the API credentials');
        }
    }
}
