<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

// Manage routes
'project/:project_id/settings'             => 'Manage/ProjectSettingsController@edit',
'project/:project_id/update/:redirect'     => 'Manage/ProjectSettingsController@update',
'project/:project_id/edit/description'     => 'Manage/ProjectSettingsController@edit_description',
'project/:project_id/share'                => 'Manage/ProjectSettingsController@share',
'project/:project_id/notifications'        => 'Manage/ProjectSettingsController@notifications',
'project/:project_id/integrations'         => 'Manage/ProjectSettingsController@integrations',
'project/:project_id/duplicate'            => 'Manage/ProjectSettingsController@duplicate',
'project/:project_id/permissions'          => 'Manage/ProjectPermissionController@index',
'project/:project_id/tags'                 => 'Manage/ProjectTagController@index',
'project/:project_id/disable'              => 'Manage/ProjectStatusController@confirmDisable',
'project/:project_id/remove'               => 'Manage/ProjectStatusController@confirmRemove',

'manage/projects'                  => 'Manage/ProjectController@index',
'projects/:order/:direction/:page' => 'Manage/ProjectController@index',
'projects/managers/:user_id'       => 'Manage/ProjectUserOverviewController@managers',
'projects/members/:user_id'        => 'Manage/ProjectUserOverviewController@members',
'projects/tasks_opened/:user_id'   => 'Manage/ProjectUserOverviewController@opens',
'projects/tasks_closed/:user_id'   => 'Manage/ProjectUserOverviewController@closed',
'projects/managers'                => 'Manage/ProjectUserOverviewController@managers',
'manage/projects/gantt'            => 'Manage/ProjectController@gantt',

];
