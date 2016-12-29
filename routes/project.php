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
    'project/create'                       => 'Project/ProjectController@create',
    'project/create/private'               => 'Project/ProjectController@createPrivate',
    'project/store'                        => 'Project/ProjectController@store',
    'project/:project_id/edit'             => 'Project/ProjectController@edit',
    'project/:project_id/edit/description' => 'Project/ProjectController@edit_description',

    'projects'                             => 'Project/ProjectController@index',
    'projects/:order/:direction/:page'     => 'Project/ProjectController@index',
    'project/:project_id'                  => 'Project/ProjectController@show',
    'project/:project_id/settings'         => 'Project/ProjectSettingsController@show',
    'p/:project_id'                        => 'Project/ProjectSettingsController@show',
    'project/:project_id/customer-filters' => 'CustomFilterController@index',
    'project/:project_id/share'            => 'Project/ProjectSettingsController@share',
    'project/:project_id/notifications'    => 'Project/ProjectSettingsController@notifications',
    'project/:project_id/integrations'     => 'Project/ProjectSettingsController@integrations',
    'project/:project_id/duplicate'        => 'Project/ProjectSettingsController@duplicate',
    'project/:project_id/permissions'      => 'Project/ProjectPermissionController@index',
    'project/:project_id/roles'            => 'Project/ProjectRoleController@show',
    'project/:project_id/activities'       => 'ActivityController@project',
    'project/:project_id/tags'             => 'Project/ProjectTagController@index',

    // ProjectUser routes
    'projects/managers/:user_id'     => 'Project/ProjectUserOverviewController@managers',
    'projects/members/:user_id'      => 'Project/ProjectUserOverviewController@members',
    'projects/tasks_opened/:user_id' => 'Project/ProjectUserOverviewController@opens',
    'projects/tasks_closed/:user_id' => 'Project/ProjectUserOverviewController@closed',
    'projects/managers'              => 'Project/ProjectUserOverviewController@managers',
    'projects/gantt'                 => 'Project/ProjectGanttController@index',

    // ProjectFile routes
    'project/:project_id/file/upload'      => 'Project/ProjectFileController@create',
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
    'project/:project_id/task/:task_id' => 'Task/TaskController@show',
    't/:task_id'                        => 'Task/TaskController@show',
    'public/task/:task_id/:token'       => 'Task/TaskController@readonly',

    'task/:project_id/create'                          => 'Task/TaskController@create',
    'task/:project_id/:column_id/:swimlane_id/create'  => 'Task/TaskController@create',
    'task/:project_id/store'                           => 'Task/TaskController@store',
    'project/:project_id/task/:task_id/start'          => 'Task/TaskController@start',
    'project/:project_id/task/:task_id/edit'           => 'Task/TaskController@edit',
    'project/:project_id/task/:task_id/update'         => 'Task/TaskController@update',
    'project/:project_id/task/:task_id/remove'         => 'Task/TaskSuppressionController@confirm',
    'project/:project_id/task/:task_id/close'          => 'Task/TaskStatusController@close',
    'project/:project_id/task/:task_id/screenshot'     => 'Task/TaskPopoverController@screenshot',
    'project/:project_id/task/:task_id/activities'     => 'ActivityController@task',
    'project/:project_id/task/:task_id/transitions'    => 'Task/TaskController@transitions',
    'project/:project_id/task/:task_id/analytics'      => 'Task/TaskController@analytics',
    'project/:project_id/task/:task_id/time-tracking'  => 'Task/TaskController@timetracking',
    'project/:project_id/task/:task_id/subtask/create' => 'Task/SubtaskController@create',
    'project/:project_id/task/:task_id/link/create'    => 'Task/TaskInternalLinkController@create',
    'project/:project_id/task/:task_id/comment/create' => 'CommentController@create',
];
