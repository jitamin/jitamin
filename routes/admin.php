<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Users admin
$container['route']->addRoute('admin/users', 'UserController', 'index');
$container['route']->addRoute('admin/users/create', 'UserController', 'create');
$container['route']->addRoute('admin/users/create/:remote', 'UserController', 'create');
$container['route']->addRoute('admin/users/import', 'UserImportController', 'show');
$container['route']->addRoute('admin/users/:user_id/authentication', 'UserController', 'changeAuthentication');

// Groups admin
$container['route']->addRoute('admin/groups', 'GroupController', 'index');
$container['route']->addRoute('admin/groups/create', 'GroupController', 'create');
$container['route']->addRoute('admin/groups/:group_id/edit', 'GroupController', 'edit');
$container['route']->addRoute('admin/groups/:group_id/remove', 'GroupController', 'confirm');
$container['route']->addRoute('admin/group/:group_id/members', 'GroupController', 'users');
$container['route']->addRoute('admin/group/:group_id/associate', 'GroupController', 'associate');

// Config admin
$container['route']->addRoute('admin/settings', 'SettingController', 'index');
$container['route']->addRoute('admin/settings/application', 'SettingController', 'application');
$container['route']->addRoute('admin/settings/email', 'SettingController', 'email');
$container['route']->addRoute('admin/settings/project', 'SettingController', 'project');
$container['route']->addRoute('admin/settings/project', 'SettingController', 'project');
$container['route']->addRoute('admin/settings/board', 'SettingController', 'board');
$container['route']->addRoute('admin/settings/calendar', 'SettingController', 'calendar');
$container['route']->addRoute('admin/settings/integrations', 'SettingController', 'integrations');
$container['route']->addRoute('admin/settings/webhook', 'SettingController', 'webhook');
$container['route']->addRoute('admin/settings/api', 'SettingController', 'api');
$container['route']->addRoute('admin/settings/help', 'SettingController', 'help');
$container['route']->addRoute('admin/settings/about', 'SettingController', 'about');
$container['route']->addRoute('admin/settings/links', 'LinkController', 'index');
$container['route']->addRoute('admin/settings/tags', 'TagController', 'index');

// Plugins admin
$container['route']->addRoute('admin/plugins', 'PluginController', 'show');
$container['route']->addRoute('admin/plugins/market', 'PluginController', 'directory');
