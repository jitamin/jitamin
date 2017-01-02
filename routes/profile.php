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
'profile/:user_id'                   => 'Profile/ProfileController@profile',
'user/show/:user_id'                 => 'Profile/ProfileController@show',
'user/show/:user_id/timesheet'       => 'Profile/ProfileController@timesheet',
'user/show/:user_id/last-logins'     => 'Profile/ProfileController@lastLogin',
'user/show/:user_id/sessions'        => 'Profile/ProfileController@sessions',
'user/show/:user_id/password-resets' => 'Profile/ProfileController@passwordReset',
'user/:user_id/edit'                 => 'Profile/ProfileController@edit',
'user/:user_id/preferences'          => 'Profile/ProfileController@preferences',
'user/:user_id/update/:redirect'     => 'Profile/ProfileController@update',
'user/:user_id/password'             => 'Profile/ProfileController@changePassword',
'user/:user_id/share'                => 'Profile/ProfileController@share',
'user/:user_id/notifications'        => 'Profile/ProfileController@notifications',
'user/:user_id/accounts'             => 'Profile/ProfileController@external',
'user/:user_id/integrations'         => 'Profile/ProfileController@integrations',
'user/:user_id/api'                  => 'Profile/ProfileController@api',

'user/:user_id/2fa'                => 'Profile/TwoFactorController@index',
'user/:user_id/avatar'             => 'Profile/AvatarController@show',
'user/:user_id/avatar/:size/image' => 'Profile/AvatarController@image',

'user/ajax/status' => 'Profile/UserAjaxController@status',

];
