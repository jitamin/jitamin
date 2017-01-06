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

// Task routes
'project/{project_id}/task/{task_id}' => 'Task/TaskController@show',
't/{task_id}'                        => 'Task/TaskController@show',
'public/task/{task_id}/{token}'       => 'Task/TaskController@readonly',

'task/{project_id}/create'                          => 'Task/TaskController@create',
'task/{project_id}/{column_id}/{swimlane_id}/create'  => 'Task/TaskController@create',
'task/{project_id}/store'                           => 'Task/TaskController@store',
'project/{project_id}/task/{task_id}/start'          => 'Task/TaskController@start',
'project/{project_id}/task/{task_id}/edit'           => 'Task/TaskController@edit',
'project/{project_id}/task/{task_id}/update'         => 'Task/TaskController@update',
'project/{project_id}/task/{task_id}/remove'         => 'Task/TaskSuppressionController@confirm',
'project/{project_id}/task/{task_id}/close'          => 'Task/TaskStatusController@close',
'project/{project_id}/task/{task_id}/screenshot'     => 'Task/TaskPopoverController@screenshot',

'project/{project_id}/task/{task_id}/transitions'    => 'Task/TaskController@transitions',
'project/{project_id}/task/{task_id}/analytics'      => 'Task/TaskController@analytics',
'project/{project_id}/task/{task_id}/time-tracking'  => 'Task/TaskController@timetracking',
'project/{project_id}/task/{task_id}/subtask/create' => 'Task/SubtaskController@create',
'project/{project_id}/task/{task_id}/link/create'    => 'Task/TaskInternalLinkController@create',

'project/{project_id}/task/{task_id}/activities'     => 'ActivityController@task',
'project/{project_id}/task/{task_id}/comment/create' => 'Task/CommentController@create',

'task/create' => 'Task/TaskSimpleController@create',
'task/store'  => 'Task/TaskSimpleController@store',

];
