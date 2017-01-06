<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

use Jitamin\Filter\TaskProjectsFilter;

/**
 * Search Controller.
 */
class SearchController extends BaseController
{
    /**
     * Shows the search view.
     */
    public function index()
    {
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        $query = urldecode($this->request->getStringParam('q'));
        $nb_tasks = 0;

        $paginator = $this->paginator
                ->setUrl('SearchController', 'index', ['q' => $query])
                ->setMax(30)
                ->setOrder('tasks.id')
                ->setDirection('DESC');

        if ($query !== '' && !empty($projects)) {
            $paginator
                ->setQuery($this->taskLexer
                    ->build($query)
                    ->withFilter(new TaskProjectsFilter(array_keys($projects)))
                    ->getQuery()
                )
                ->calculate();

            $nb_tasks = $paginator->getTotal();
        }

        $this->response->html($this->helper->layout->dashboard('search/index', [
            'values' => [
                'q'          => $query,
                'controller' => 'SearchController',
                'action'     => 'index',
            ],
            'paginator' => $paginator,
            'title'     => t('Search tasks').($nb_tasks > 0 ? ' ('.$nb_tasks.')' : ''),
        ], 'search/_partials/nav'));
    }

    /**
     * Shows the search view of activity.
     */
    public function activity()
    {
        $query = urldecode($this->request->getStringParam('q'));
        $events = $this->helper->projectActivity->searchEvents($query);
        $nb_events = count($events);

        $this->response->html($this->helper->layout->dashboard('search/activity', [
            'values' => [
                'q'          => $query,
                'controller' => 'SearchController',
                'action'     => 'activity',
            ],
            'title'     => t('Search in activities').($nb_events > 0 ? ' ('.$nb_events.')' : ''),
            'nb_events' => $nb_events,
            'events'    => $events,
        ], 'search/_partials/nav'));
    }
}
