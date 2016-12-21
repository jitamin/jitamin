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
$container['route']->addRoute('dashboard', 'DashboardController', 'index');
$container['route']->addRoute('dashboard/:user_id', 'DashboardController', 'index');
$container['route']->addRoute('dashboard/:user_id/projects', 'DashboardController', 'projects');
$container['route']->addRoute('dashboard/:user_id/tasks', 'DashboardController', 'tasks');
$container['route']->addRoute('dashboard/:user_id/stars', 'DashboardController', 'stars');
$container['route']->addRoute('dashboard/:user_id/subtasks', 'DashboardController', 'subtasks');
$container['route']->addRoute('dashboard/:user_id/calendar', 'DashboardController', 'calendar');
$container['route']->addRoute('dashboard/:user_id/activities', 'DashboardController', 'activities');
$container['route']->addRoute('dashboard/:user_id/notifications', 'DashboardController', 'notifications');
