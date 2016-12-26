<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    // Dashboard routes
    'dashboard'               => 'DashboardController@index',
    'dashboard/projects'      => 'DashboardController@projects',
    'dashboard/tasks'         => 'DashboardController@tasks',
    'dashboard/stars'         => 'DashboardController@stars',
    'dashboard/subtasks'      => 'DashboardController@subtasks',
    'dashboard/calendar'      => 'DashboardController@calendar',
    'dashboard/activities'    => 'DashboardController@activities',
    'dashboard/notifications' => 'DashboardController@notifications',
];
