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
    // Project routes
    'project/create'                       => 'ProjectController@create',
    'project/create/private'               => 'ProjectController@createPrivate',
    'project/store'                        => 'ProjectController@store',
    'project/:project_id/edit'             => 'ProjectController@edit',
    'project/:project_id/edit/description' => 'ProjectController@edit_description',

    'projects'                             => 'ProjectController@index',
    'projects/:order/:direction/:page'     => 'ProjectController@index',
    'project/:project_id'                  => 'ProjectController@show',
    'project/:project_id/settings'         => 'ProjectSettingsController@show',
    'p/:project_id'                        => 'ProjectSettingsController@show',
    'project/:project_id/customer-filters' => 'CustomFilterController@index',
    'project/:project_id/share'            => 'ProjectSettingsController@share',
    'project/:project_id/notifications'    => 'ProjectSettingsController@notifications',
    'project/:project_id/integrations'     => 'ProjectSettingsController@integrations',
    'project/:project_id/duplicate'        => 'ProjectSettingsController@duplicate',
    'project/:project_id/permissions'      => 'ProjectPermissionController@index',
    'project/:project_id/roles'            => 'ProjectRoleController@show',
    'project/:project_id/activities'       => 'ActivityController@project',
    'project/:project_id/tags'             => 'ProjectTagController@index',

    // ProjectUser routes
    'projects/managers/:user_id'     => 'ProjectUserOverviewController@managers',
    'projects/members/:user_id'      => 'ProjectUserOverviewController@members',
    'projects/tasks_opened/:user_id' => 'ProjectUserOverviewController@opens',
    'projects/tasks_closed/:user_id' => 'ProjectUserOverviewController@closed',
    'projects/managers'              => 'ProjectUserOverviewController@managers',
    'projects/gantt'                 => 'ProjectGanttController@index',

    // ProjectFile routes
    'project/:project_id/file/upload'      => 'ProjectFileController@create',
    'project/:project_id/file/:file_id'    => 'FileViewerController@show',
    'project/:project_id/browser/:file_id' => 'FileViewerController@browser',

    // Action routes
    'project/:project_id/actions' => 'ActionController@index',

    // Column routes
    'project/:project_id/columns' => 'ColumnController@index',

    // Swimlane routes
    'project/:project_id/swimlanes' => 'SwimlaneController@index',

    // Category routes
    'project/:project_id/categories' => 'CategoryController@index',

    // Import routes
    'project/:project_id/import' => 'TaskImportController@show',

    // Task routes
    'project/:project_id/task/:task_id' => 'TaskViewController@show',
    't/:task_id'                        => 'TaskViewController@show',
    'public/task/:task_id/:token'       => 'TaskViewController@readonly',

    'task/:project_id/create'                          => 'TaskController@create',
    'task/:project_id/:column_id/:swimlane_id/create'  => 'TaskController@create',
    'task/:project_id/store'                           => 'TaskController@store',
    'project/:project_id/task/:task_id/start'          => 'TaskController@start',
    'project/:project_id/task/:task_id/edit'           => 'TaskController@edit',
    'project/:project_id/task/:task_id/update'         => 'TaskController@update',
    'project/:project_id/task/:task_id/remove'         => 'TaskSuppressionController@confirm',
    'project/:project_id/task/:task_id/close'          => 'TaskStatusController@close',
    'project/:project_id/task/:task_id/screenshot'     => 'TaskPopoverController@screenshot',
    'project/:project_id/task/:task_id/activities'     => 'ActivityController@task',
    'project/:project_id/task/:task_id/transitions'    => 'TaskViewController@transitions',
    'project/:project_id/task/:task_id/analytics'      => 'TaskViewController@analytics',
    'project/:project_id/task/:task_id/time-tracking'  => 'TaskViewController@timetracking',
    'project/:project_id/task/:task_id/subtask/create' => 'SubtaskController@create',
    'project/:project_id/task/:task_id/link/create'    => 'TaskInternalLinkController@create',
    'project/:project_id/task/:task_id/comment/create' => 'CommentController@create',
];
