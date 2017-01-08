<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Formatter\BoardFormatter;
use Jitamin\Policy\ProjectPolicy;

/**
 * Board API controller.
 */
class BoardController extends Controller
{
    /**
     * Get a board by the project id.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getBoard($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getBoard', $project_id);

        return BoardFormatter::getInstance($this->container)
            ->withProjectId($project_id)
            ->withQuery($this->taskFinderModel->getExtendedQuery())
            ->format();
    }
}
