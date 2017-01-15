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
 * Navbar Search Helper.
 */
class NavbarSearchHelper extends Base
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
        $project_id = !empty($project) ? $project['id'] : 0;
        $query = $this->request->getStringParam('q', $this->userSession->getFilters($project_id));
        $this->userSession->setFilters($project_id, $query);

        return urldecode($query);
    }

    /**
     * Render project header (views switcher and search box).
     *
     * @param array  $project
     *
     * @return string
     */
    public function render(array $project)
    {
        if (empty($project)) {
            return null;
        }
        $controller = $this->helper->app->getRouterController();
        $action = $this->helper->app->getRouterAction();

        if (!$this->canShowNavbarSearch($controller.'@'.$action)) {
            return null;
        }

        $filters = [
            'controller' => $controller,
            'action'     => $action,
            'project_id' => $project['id'],
            'q'          => $this->getSearchQuery($project),
        ];

        return $this->template->render('_partials/navbar_search', [
            'project'             => $project,
            'filters'             => $filters,
            'categories_list'     => $this->categoryModel->getList($project['id'], false),
            'users_list'          => $this->projectUserRoleModel->getAssignableUsersList($project['id'], false),
            'custom_filters_list' => $this->customFilterModel->getAll($project['id'], $this->userSession->getId()),
        ]);
    }

    /**
     * Determin if shows header search bar.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function canShowNavbarSearch($path = '')
    {
        $whiteList = ['Project/ProjectController@overview', 'Project/ProjectController@show', 'Project/Board/BoardController@show', 'CalendarController@show', 'Task/TaskController@index', 'Task/TaskController@gantt'];

        return in_array($path, $whiteList);
    }
}
