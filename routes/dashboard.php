<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Dashboard routes

return [
    'dashboard'                        => 'DashboardController@index',
    'dashboard/:user_id'               => 'DashboardController@index',
    'dashboard/:user_id/projects'      => 'DashboardController@projects',
    'dashboard/:user_id/tasks'         => 'DashboardController@tasks',
    'dashboard/:user_id/stars'         => 'DashboardController@stars',
    'dashboard/:user_id/subtasks'      => 'DashboardController@subtasks',
    'dashboard/:user_id/calendar'      => 'DashboardController@calendar',
    'dashboard/:user_id/activities'    => 'DashboardController@activities',
    'dashboard/:user_id/notifications' => 'DashboardController@notifications',
];
