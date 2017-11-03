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

// Profile
'profile/{user_id}'                => 'Profile/ProfileController@show',
'user/{user_id}/edit'              => 'Profile/ProfileController@edit',
'user/{user_id}/preferences'       => 'Profile/ProfileController@preferences',
'user/{user_id}/update/{redirect}' => 'Profile/ProfileController@update',
'user/{user_id}/password'          => 'Profile/ProfileController@changePassword',
'user/{user_id}/share'             => 'Profile/ProfileController@share',
'user/{user_id}/notifications'     => 'Profile/ProfileController@notifications',
'user/{user_id}/accounts'          => 'Profile/ProfileController@external',
'user/{user_id}/integrations'      => 'Profile/ProfileController@integrations',
'user/{user_id}/api'               => 'Profile/ProfileController@api',

'user/history/{user_id}/timesheet'       => 'Profile/HistoryController@timesheet',
'user/history/{user_id}/last-logins'     => 'Profile/HistoryController@lastLogin',
'user/history/{user_id}/sessions'        => 'Profile/HistoryController@sessions',
'user/history/{user_id}/password-resets' => 'Profile/HistoryController@passwordReset',

'user/{user_id}/2fa'                 => 'Profile/TwoFactorController@index',
'user/{user_id}/2fa/check'           => 'Profile/TwoFactorController@check',
'user/{user_id}/2fa/show'            => 'Profile/TwoFactorController@show',
'profile/2fa/code'                   => 'Profile/TwoFactorController@code',
'user/{user_id}/avatar'              => 'Profile/AvatarController@show',
'user/{user_id}/avatar/{size}/image' => 'Profile/AvatarController@image',

'user/ajax/status' => 'Profile/UserAjaxController@status',

];
