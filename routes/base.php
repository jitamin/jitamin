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
// WebNotification routes
'notification/:user_id/:notification_id' => 'WebNotificationController@redirect',

// Search routes
'search'          => 'SearchController@index',
'search/activity' => 'SearchController@activity',

// Exports
'export/tasks/:project_id'       => 'ExportController@tasks',
'export/subtasks/:project_id'    => 'ExportController@subtasks',
'export/transitions/:project_id' => 'ExportController@transitions',
'export/summary/:project_id'     => 'ExportController@summary',

// Analytics routes
'analytics/tasks/:project_id'                => 'AnalyticController@taskDistribution',
'analytics/users/:project_id'                => 'AnalyticController@userDistribution',
'analytics/cfd/:project_id'                  => 'AnalyticController@cfd',
'analytics/burndown/:project_id'             => 'AnalyticController@burndown',
'analytics/average-time-column/:project_id'  => 'AnalyticController@averageTimeByColumn',
'analytics/lead-cycle-time/:project_id'      => 'AnalyticController@leadAndCycleTime',
'analytics/estimated-spent-time/:project_id' => 'AnalyticController@compareHours',

// Board routes
'board/:project_id'   => 'BoardController@show',
'b/:project_id'       => 'BoardController@show',
'public/board/:token' => 'BoardController@readonly',

// Calendar routes
'calendar/:project_id' => 'CalendarController@show',
'c/:project_id'        => 'CalendarController@show',

// Listing routes
'list/:project_id' => 'TaskController@index',
'l/:project_id'    => 'TaskListController@show',

// Gantt routes
'gantt/:project_id'               => 'TaskGanttController@show',
'gantt/:project_id/sort/:sorting' => 'TaskGanttController@show',
'gantt/:project_id/create'        => 'TaskGanttController@create',

// Feed routes
'feed/project/:token' => 'FeedController@project',
'feed/user/:token'    => 'FeedController@user',

// Ical routes
'ical/project/:token' => 'ICalendarController@project',
'ical/user/:token'    => 'ICalendarController@user',

// Doc
'help/:file' => 'DocumentationController@show',
'help'       => 'DocumentationController@show',
];
