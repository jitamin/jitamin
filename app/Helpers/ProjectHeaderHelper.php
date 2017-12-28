<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Foundation\Base;

/**
 * Project Header Helper.
 */
class ProjectHeaderHelper extends Base
{
    /**
     * Get current query.
     *
     * @param array $project
     *
     * @return string
     */
    public function getSearchQuery(array $project)
    {
        $query = $this->request->getStringParam('q', $this->userSession->getFilters($project['id']));
        $this->userSession->setFilters($project['id'], $query);

        return urldecode($query);
    }

    /**
     * Render project header (views switcher and search box).
     *
     * @param array $project
     * @param bool  $boardView
     *
     * @return string
     */
    public function render(array $project, $boardView = false)
    {
        return $this->template->render('project/_header/header', [
            'project'    => $project,
            'q'          => $this->getSearchQuery($project),
            'board_view' => $boardView,
        ]);
    }

    /**
     * Get project description.
     *
     * @param array $project
     *
     * @return string
     */
    public function getDescription(array &$project)
    {
        if ($project['owner_id'] > 0) {
            $description = t('Project owner: ').'**'.$this->helper->text->e($project['owner_name'] ?: $project['owner_username']).'**'.PHP_EOL.PHP_EOL;

            if (!empty($project['description'])) {
                $description .= '***'.PHP_EOL.PHP_EOL;
                $description .= $project['description'];
            }
        } else {
            $description = $project['description'];
        }

        return $description;
    }
}
