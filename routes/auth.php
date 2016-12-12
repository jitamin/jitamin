<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Auth routes
$container['route']->addRoute('login', 'AuthController', 'login');
$container['route']->addRoute('logout', 'AuthController', 'logout');
$container['route']->addRoute('check', 'AuthController', 'check');

// Captcha routes
$container['route']->addRoute('captcha', 'CaptchaController', 'image');

// PasswordReset
$container['route']->addRoute('forgot-password', 'PasswordResetController', 'create');
$container['route']->addRoute('forgot-password/change/:token', 'PasswordResetController', 'change');
