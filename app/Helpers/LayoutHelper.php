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
 * Layout Helper.
 */
class LayoutHelper extends Base
{
    /**
     * Render a template without the layout if Ajax request.
     *
     * @param string $template Template name
     * @param array  $params   Template parameters
     *
     * @return string
     */
    public function app($template, array $params = [])
    {
        $content = $this->template->render($template, $params);

        if ($this->request->isAjax()) {
            return $content;
        }

        return $this->template->render(
            'layouts/master',
            $params + ['content_for_layout' => $content]
        );
    }

    /**
     * Common layout for profile views.
     *
     * @param string $template Template name
     * @param array  $params   Template parameters
     * @param string $subside
     *
     * @return string
     */
    public function profile($template, array $params, $subside = 'profile/_partials/subnav')
    {
        if (isset($params['user'])) {
            $params['title'] = '#'.$params['user']['id'].' '.($params['user']['name'] ?: $params['user']['username']);
        }

        return $this->subLayout('profile/layout', $subside, $template, $params);
    }

    /**
     * Common layout for task views.
     *
     * @param string $template Template name
     * @param array  $params   Template parameters
     *
     * @return string
     */
    public function task($template, array $params)
    {
        $params['page_title'] = $params['task']['project_name'].', #'.$params['task']['id'].' - '.$params['task']['title'];
        $params['title'] = $params['task']['project_name'];

        return $this->subLayout('task/layout', 'task/subside', $template, $params);
    }

    /**
     * Common layout for project views.
     *
     * @param string $template
     * @param array  $params
     * @param string $subside
     *
     * @return string
     */
    public function project($template, array $params, $subside = 'manage/project_settings/subside')
    {
        if (empty($params['title'])) {
            $params['title'] = $params['project']['name'];
        } elseif ($params['project']['name'] !== $params['title']) {
            $params['title'] = $params['project']['name'].' &raquo; '.$params['title'];
        }

        return $this->subLayout('project/layout', $subside, $template, $params);
    }

    /**
     * Common layout for project user views.
     *
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    public function projectUser($template, array $params)
    {
        $params['filter'] = ['user_id' => $params['user_id']];

        return $this->subLayout('manage/project_user_overview/layout', 'manage/project_user_overview/subside', $template, $params);
    }

    /**
     * Common layout for admin views.
     *
     * @param string $template
     * @param array  $params
     * @param string $subside
     *
     * @return string
     */
    public function admin($template, array $params, $subside = 'admin/setting/subside')
    {
        if (!isset($params['values'])) {
            $params['values'] = $this->settingModel->getAll();
        }

        if (!isset($params['errors'])) {
            $params['errors'] = [];
        }

        return $this->subLayout('admin/layout', $subside, $template, $params);
    }

    /**
     * Common layout for dashboard views.
     *
     * @param string $template
     * @param array  $params
     * @param string $subside
     *
     * @return string
     */
    public function dashboard($template, array $params, $subside = 'dashboard/_partials/subnav')
    {
        return $this->subLayout('dashboard/layout', $subside, $template, $params);
    }

    /**
     * Common layout for analytic views.
     *
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    public function analytic($template, array $params, $subside = 'project/analytic/_partials/subside')
    {
        if (isset($params['project']['name'])) {
            $params['title'] = $params['project']['name'].' &raquo; '.$params['title'];
        }

        return $this->subLayout('project/analytic/layout', $subside, $template, $params);
    }

    /**
     * Common method to generate a sub-layout.
     *
     * @param string $sublayout
     * @param string $subside
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    protected function subLayout($sublayout, $subside, $template, array $params = [])
    {
        $content = $this->template->render($template, $params);

        if ($this->request->isAjax()) {
            return $content;
        }

        $params['content_for_sublayout'] = $content;
        $params['subside_template'] = $subside;

        return $this->app($sublayout, $params);
    }
}
