<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container['route']->enable();

// Dashboard
$container['route']->addRoute('dashboard', 'DashboardController', 'show');
$container['route']->addRoute('dashboard/:user_id', 'DashboardController', 'show');
$container['route']->addRoute('dashboard/:user_id/projects', 'DashboardController', 'projects');
$container['route']->addRoute('dashboard/:user_id/tasks', 'DashboardController', 'tasks');
$container['route']->addRoute('dashboard/:user_id/subtasks', 'DashboardController', 'subtasks');
$container['route']->addRoute('dashboard/:user_id/calendar', 'DashboardController', 'calendar');
$container['route']->addRoute('dashboard/:user_id/activity', 'DashboardController', 'activity');
$container['route']->addRoute('dashboard/:user_id/notifications', 'DashboardController', 'notifications');

// Search routes
$container['route']->addRoute('search', 'SearchController', 'index');
$container['route']->addRoute('search/activity', 'SearchController', 'activity');

// Project routes
$container['route']->addRoute('project/create', 'ProjectController', 'create');
$container['route']->addRoute('project/create/private', 'ProjectController', 'createPrivate');
$container['route']->addRoute('project/store', 'ProjectController', 'store');
$container['route']->addRoute('project/:project_id/edit', 'ProjectController', 'edit');
$container['route']->addRoute('project/:project_id/edit/description', 'ProjectController', 'edit_description');
$container['route']->addRoute('projects', 'ProjectController', 'index');
$container['route']->addRoute('project/:project_id', 'ProjectController', 'show');
$container['route']->addRoute('project/:project_id/settings', 'ProjectSettingsController', 'show');
$container['route']->addRoute('p/:project_id', 'ProjectSettingsController', 'show');
$container['route']->addRoute('project/:project_id/customer-filters', 'CustomFilterController', 'index');
$container['route']->addRoute('project/:project_id/share', 'ProjectSettingsController', 'share');
$container['route']->addRoute('project/:project_id/notifications', 'ProjectSettingsController', 'notifications');
$container['route']->addRoute('project/:project_id/integrations', 'ProjectSettingsController', 'integrations');
$container['route']->addRoute('project/:project_id/duplicate', 'ProjectSettingsController', 'duplicate');
$container['route']->addRoute('project/:project_id/permissions', 'ProjectPermissionController', 'index');
$container['route']->addRoute('project/:project_id/activity', 'ActivityController', 'project');
$container['route']->addRoute('project/:project_id/tags', 'ProjectTagController', 'index');

// ProjectUser routes
$container['route']->addRoute('projects/managers/:user_id', 'ProjectUserOverviewController', 'managers');
$container['route']->addRoute('projects/members/:user_id', 'ProjectUserOverviewController', 'members');
$container['route']->addRoute('projects/tasks/:user_id/opens', 'ProjectUserOverviewController', 'opens');
$container['route']->addRoute('projects/tasks/:user_id/closed', 'ProjectUserOverviewController', 'closed');
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
$container['route']->addRoute('project/:project_id/task/:task_id/activity', 'ActivityController', 'task');
$container['route']->addRoute('project/:project_id/task/:task_id/transitions', 'TaskViewController', 'transitions');
$container['route']->addRoute('project/:project_id/task/:task_id/analytics', 'TaskViewController', 'analytics');
$container['route']->addRoute('project/:project_id/task/:task_id/time-tracking', 'TaskViewController', 'timetracking');

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
$container['route']->addRoute('board/:project_id', 'BoardViewController', 'show');
$container['route']->addRoute('b/:project_id', 'BoardViewController', 'show');
$container['route']->addRoute('public/board/:token', 'BoardViewController', 'readonly');

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

// Profile
$container['route']->addRoute('profile/:user_id', 'ProfileController', 'profile');
$container['route']->addRoute('user/show/:user_id', 'ProfileController', 'show');
$container['route']->addRoute('user/show/:user_id/timesheet', 'ProfileController', 'timesheet');
$container['route']->addRoute('user/show/:user_id/last-logins', 'ProfileController', 'lastLogin');
$container['route']->addRoute('user/show/:user_id/sessions', 'ProfileController', 'sessions');
$container['route']->addRoute('user/:user_id/edit', 'ProfileController', 'edit');
$container['route']->addRoute('user/:user_id/password', 'UserCredentialController', 'changePassword');
$container['route']->addRoute('user/:user_id/share', 'ProfileController', 'share');
$container['route']->addRoute('user/:user_id/notifications', 'ProfileController', 'notifications');
$container['route']->addRoute('user/:user_id/accounts', 'ProfileController', 'external');
$container['route']->addRoute('user/:user_id/integrations', 'ProfileController', 'integrations');
$container['route']->addRoute('user/:user_id/authentication', 'UserCredentialController', 'changeAuthentication');

$container['route']->addRoute('user/:user_id/2fa', 'TwoFactorController', 'index');
$container['route']->addRoute('user/:user_id/avatar', 'AvatarFileController', 'show');
$container['route']->addRoute('user/:user_id/avatar/:size/image', 'AvatarFileController', 'image');

$container['route']->addRoute('user/ajax/status', 'UserAjaxController', 'status');


// Users admin
$container['route']->addRoute('admin/users', 'UserListController', 'show');
$container['route']->addRoute('admin/users/create', 'UserController', 'create');

// Groups admin
$container['route']->addRoute('admin/groups', 'GroupListController', 'index');
$container['route']->addRoute('admin/groups/create', 'GroupController', 'create');
$container['route']->addRoute('admin/groups/edit', 'GroupController', 'edit');
$container['route']->addRoute('admin/group/:group_id/members', 'GroupListController', 'users');

// Config admin
$container['route']->addRoute('admin/settings', 'ConfigController', 'index');
$container['route']->addRoute('admin/settings/application', 'ConfigController', 'application');
$container['route']->addRoute('admin/settings/email', 'ConfigController', 'email');
$container['route']->addRoute('admin/settings/project', 'ConfigController', 'project');
$container['route']->addRoute('admin/settings/project', 'ConfigController', 'project');
$container['route']->addRoute('admin/settings/board', 'ConfigController', 'board');
$container['route']->addRoute('admin/settings/calendar', 'ConfigController', 'calendar');
$container['route']->addRoute('admin/settings/integrations', 'ConfigController', 'integrations');
$container['route']->addRoute('admin/settings/webhook', 'ConfigController', 'webhook');
$container['route']->addRoute('admin/settings/api', 'ConfigController', 'api');
$container['route']->addRoute('admin/settings/help', 'ConfigController', 'help');
$container['route']->addRoute('admin/settings/about', 'ConfigController', 'about');
$container['route']->addRoute('admin/settings/links', 'LinkController', 'index');
$container['route']->addRoute('admin/settings/currencies', 'CurrencyController', 'index');
$container['route']->addRoute('admin/settings/tags', 'TagController', 'index');

// Plugins admin
$container['route']->addRoute('admin/extensions', 'PluginController', 'show');
$container['route']->addRoute('admin/extensions/directory', 'PluginController', 'directory');

// Doc
$container['route']->addRoute('documentation/:file', 'DocumentationController', 'show');
$container['route']->addRoute('documentation', 'DocumentationController', 'show');

// Auth routes
$container['route']->addRoute('login', 'AuthController', 'login');
$container['route']->addRoute('logout', 'AuthController', 'logout');
$container['route']->addRoute('check', 'AuthController', 'check');

// Captcha routes
$container['route']->addRoute('captcha', 'CaptchaController', 'image');

// PasswordReset
$container['route']->addRoute('forgot-password', 'PasswordResetController', 'create');
$container['route']->addRoute('forgot-password/change/:token', 'PasswordResetController', 'change');
