<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// WebNotification routes
$container['route']->addRoute('notification/:user_id/:notification_id', 'WebNotificationController', 'redirect');

// Search routes
$container['route']->addRoute('search', 'SearchController', 'index');
$container['route']->addRoute('search/activity', 'SearchController', 'activity');

// Exports
$container['route']->addRoute('export/tasks/:project_id', 'ExportController', 'tasks');
$container['route']->addRoute('export/subtasks/:project_id', 'ExportController', 'subtasks');
$container['route']->addRoute('export/transitions/:project_id', 'ExportController', 'transitions');
$container['route']->addRoute('export/summary/:project_id', 'ExportController', 'summary');

// Analytics routes
$container['route']->addRoute('analytics/tasks/:project_id', 'AnalyticController', 'taskDistribution');
$container['route']->addRoute('analytics/users/:project_id', 'AnalyticController', 'userDistribution');
$container['route']->addRoute('analytics/cfd/:project_id', 'AnalyticController', 'cfd');
$container['route']->addRoute('analytics/burndown/:project_id', 'AnalyticController', 'burndown');
$container['route']->addRoute('analytics/average-time-column/:project_id', 'AnalyticController', 'averageTimeByColumn');
$container['route']->addRoute('analytics/lead-cycle-time/:project_id', 'AnalyticController', 'leadAndCycleTime');
$container['route']->addRoute('analytics/estimated-spent-time/:project_id', 'AnalyticController', 'compareHours');

// Board routes
$container['route']->addRoute('board/:project_id', 'BoardController', 'show');
$container['route']->addRoute('b/:project_id', 'BoardController', 'show');
$container['route']->addRoute('public/board/:token', 'BoardController', 'readonly');

// Calendar routes
$container['route']->addRoute('calendar/:project_id', 'CalendarController', 'show');
$container['route']->addRoute('c/:project_id', 'CalendarController', 'show');

// Listing routes
$container['route']->addRoute('list/:project_id', 'TaskController', 'index');
$container['route']->addRoute('l/:project_id', 'TaskListController', 'show');

// Gantt routes
$container['route']->addRoute('gantt/:project_id', 'TaskGanttController', 'show');
$container['route']->addRoute('gantt/:project_id/sort/:sorting', 'TaskGanttController', 'show');
$container['route']->addRoute('gantt/:project_id/create', 'TaskGanttController', 'create');

// Feed routes
$container['route']->addRoute('feed/project/:token', 'FeedController', 'project');
$container['route']->addRoute('feed/user/:token', 'FeedController', 'user');

// Ical routes
$container['route']->addRoute('ical/project/:token', 'ICalendarController', 'project');
$container['route']->addRoute('ical/user/:token', 'ICalendarController', 'user');

// Doc
$container['route']->addRoute('help/:file', 'DocumentationController', 'show');
$container['route']->addRoute('help', 'DocumentationController', 'show');
