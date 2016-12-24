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
// Users admin
'admin/users' => 'UserController@index',
'admin/users/create' => 'UserController@create',
'admin/users/create/:remote' => 'UserController@create',
'admin/users/import' => 'UserImportController@show',
'admin/users/:user_id/authentication' => 'UserController@changeAuthentication',

// Groups admin
'admin/groups' => 'GroupController@index',
'admin/groups/create' => 'GroupController@create',
'admin/groups/:group_id/edit' => 'GroupController@edit',
'admin/groups/:group_id/remove' => 'GroupController@confirm',
'admin/group/:group_id/members' => 'GroupController@users',
'admin/group/:group_id/associate' => 'GroupController@associate',

// Config admin
'admin/settings' => 'SettingController@index',
'admin/settings/application' => 'SettingController@application',
'admin/settings/email' => 'SettingController@email',
'admin/settings/project' => 'SettingController@project',
'admin/settings/project' => 'SettingController@project',
'admin/settings/board' => 'SettingController@board',
'admin/settings/calendar' => 'SettingController@calendar',
'admin/settings/integrations' => 'SettingController@integrations',
'admin/settings/webhook' => 'SettingController@webhook',
'admin/settings/api' => 'SettingController@api',
'admin/settings/help' => 'SettingController@help',
'admin/settings/about' => 'SettingController@about',
'admin/settings/links' => 'LinkController@index',
'admin/settings/tags' => 'TagController@index',

// Plugins admin
'admin/plugins' => 'PluginController@show',
'admin/plugins/market' => 'PluginController@directory',
];
