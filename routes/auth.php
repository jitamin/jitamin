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
'login' => 'AuthController@login',
'logout' => 'AuthController@logout',
'check' => 'AuthController@check',

// Captcha routes
'captcha' => 'CaptchaController@image',

// PasswordReset
'forgot-password' => 'PasswordResetController@create',
'forgot-password/change/:token' => 'PasswordResetController@change',

];
