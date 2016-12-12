<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Project routes
$container['route']->addRoute('project/create', 'ProjectController', 'create');
$container['route']->addRoute('project/create/private', 'ProjectController', 'createPrivate');
$container['route']->addRoute('project/store', 'ProjectController', 'store');
$container['route']->addRoute('project/:project_id/edit', 'ProjectController', 'edit');
$container['route']->addRoute('project/:project_id/edit/description', 'ProjectController', 'edit_description');
$container['route']->addRoute('projects', 'ProjectController', 'index');
$container['route']->addRoute('projects/:order/:direction/:page', 'ProjectController', 'index');
$container['route']->addRoute('project/:project_id', 'ProjectController', 'show');
$container['route']->addRoute('project/:project_id/settings', 'ProjectSettingsController', 'show');
$container['route']->addRoute('p/:project_id', 'ProjectSettingsController', 'show');
$container['route']->addRoute('project/:project_id/customer-filters', 'CustomFilterController', 'index');
$container['route']->addRoute('project/:project_id/share', 'ProjectSettingsController', 'share');
$container['route']->addRoute('project/:project_id/notifications', 'ProjectSettingsController', 'notifications');
$container['route']->addRoute('project/:project_id/integrations', 'ProjectSettingsController', 'integrations');
$container['route']->addRoute('project/:project_id/duplicate', 'ProjectSettingsController', 'duplicate');
$container['route']->addRoute('project/:project_id/permissions', 'ProjectPermissionController', 'index');
$container['route']->addRoute('project/:project_id/roles', 'ProjectRoleController', 'show');
$container['route']->addRoute('project/:project_id/activity', 'ActivityController', 'project');
$container['route']->addRoute('project/:project_id/tags', 'ProjectTagController', 'index');

// ProjectUser routes
$container['route']->addRoute('projects/managers/:user_id', 'ProjectUserOverviewController', 'managers');
$container['route']->addRoute('projects/members/:user_id', 'ProjectUserOverviewController', 'members');
$container['route']->addRoute('projects/tasks_opened/:user_id', 'ProjectUserOverviewController', 'opens');
$container['route']->addRoute('projects/tasks_closed/:user_id', 'ProjectUserOverviewController', 'closed');
$container['route']->addRoute('projects/managers', 'ProjectUserOverviewController', 'managers');
$container['route']->addRoute('projects/gantt', 'ProjectGanttController', 'show');

// ProjectFile routes
$container['route']->addRoute('project/:project_id/file/upload', 'ProjectFileController', 'create');
$container['route']->addRoute('project/:project_id/file/:file_id', 'FileViewerController', 'show');

// Action routes
$container['route']->addRoute('project/:project_id/actions', 'ActionController', 'index');

// Column routes
$container['route']->addRoute('project/:project_id/columns', 'ColumnController', 'index');

// Swimlane routes
$container['route']->addRoute('project/:project_id/swimlanes', 'SwimlaneController', 'index');

// Category routes
$container['route']->addRoute('project/:project_id/categories', 'CategoryController', 'index');

// Import routes
$container['route']->addRoute('project/:project_id/import', 'TaskImportController', 'show');

// Task routes
$container['route']->addRoute('project/:project_id/task/:task_id', 'TaskViewController', 'show');
$container['route']->addRoute('t/:task_id', 'TaskViewController', 'show');
$container['route']->addRoute('public/task/:task_id/:token', 'TaskViewController', 'readonly');

$container['route']->addRoute('task/:project_id/create', 'TaskController', 'create');
$container['route']->addRoute('task/:project_id/:column_id/:swimlane_id/create', 'TaskController', 'create');
$container['route']->addRoute('task/:project_id/store', 'TaskController', 'store');
$container['route']->addRoute('project/:project_id/task/:task_id/activity', 'ActivityController', 'task');
$container['route']->addRoute('project/:project_id/task/:task_id/transitions', 'TaskViewController', 'transitions');
$container['route']->addRoute('project/:project_id/task/:task_id/analytics', 'TaskViewController', 'analytics');
$container['route']->addRoute('project/:project_id/task/:task_id/time-tracking', 'TaskViewController', 'timetracking');
