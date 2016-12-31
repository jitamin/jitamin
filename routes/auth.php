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

// Auth routes
'login'  => 'Auth/AuthController@login',
'logout' => 'Auth/AuthController@logout',
'check'  => 'Auth/AuthController@check',

// Captcha routes
'captcha' => 'CaptchaController@image',

// PasswordReset
'forgot-password'               => 'Auth/PasswordResetController@create',
'forgot-password/change/:token' => 'Auth/PasswordResetController@change',
'forgot-password/store'         => 'Auth/PasswordResetController@store',

];
