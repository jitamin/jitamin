<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\ProjectAuthorization;
use Jitamin\Formatter\BoardFormatter;

/**
 * Board API controller.
 */
class BoardProcedure extends BaseProcedure
{
    public function getBoard($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getBoard', $project_id);

        return BoardFormatter::getInstance($this->container)
            ->withProjectId($project_id)
            ->withQuery($this->taskFinderModel->getExtendedQuery())
            ->format();
    }
}
