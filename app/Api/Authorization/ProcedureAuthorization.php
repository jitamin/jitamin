<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Authorization;

use Jitamin\Core\Base;
use JsonRPC\Exception\AccessDeniedException;

/**
 * Class ProcedureAuthorization.
 */
class ProcedureAuthorization extends Base
{
    private $userSpecificProcedures = [
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
     * @param string $procedure
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    public function check($procedure)
    {
        if (!$this->userSession->isLogged() && in_array($procedure, $this->userSpecificProcedures)) {
            throw new AccessDeniedException('This procedure is not available with the API credentials');
        }
    }
}
