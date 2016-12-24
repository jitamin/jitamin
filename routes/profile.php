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
// Profile
'profile/:user_id'                   => 'ProfileController@profile',
'user/show/:user_id'                 => 'ProfileController@show',
'user/show/:user_id/timesheet'       => 'ProfileController@timesheet',
'user/show/:user_id/last-logins'     => 'ProfileController@lastLogin',
'user/show/:user_id/sessions'        => 'ProfileController@sessions',
'user/show/:user_id/password-resets' => 'ProfileController@passwordReset',
'user/:user_id/edit'                 => 'ProfileController@edit',
'user/:user_id/password'             => 'ProfileController@changePassword',
'user/:user_id/share'                => 'ProfileController@share',
'user/:user_id/notifications'        => 'ProfileController@notifications',
'user/:user_id/accounts'             => 'ProfileController@external',
'user/:user_id/integrations'         => 'ProfileController@integrations',

'user/:user_id/2fa'                => 'TwoFactorController@index',
'user/:user_id/avatar'             => 'AvatarFileController@show',
'user/:user_id/avatar/:size/image' => 'AvatarFileController@image',

'user/ajax/status' => 'UserAjaxController@status',

];
