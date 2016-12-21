<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Profile
$container['route']->addRoute('profile/:user_id', 'ProfileController', 'profile');
$container['route']->addRoute('user/show/:user_id', 'ProfileController', 'show');
$container['route']->addRoute('user/show/:user_id/timesheet', 'ProfileController', 'timesheet');
$container['route']->addRoute('user/show/:user_id/last-logins', 'ProfileController', 'lastLogin');
$container['route']->addRoute('user/show/:user_id/sessions', 'ProfileController', 'sessions');
$container['route']->addRoute('user/show/:user_id/password-resets', 'ProfileController', 'passwordReset');
$container['route']->addRoute('user/:user_id/edit', 'ProfileController', 'edit');
$container['route']->addRoute('user/:user_id/password', 'ProfileController', 'changePassword');
$container['route']->addRoute('user/:user_id/share', 'ProfileController', 'share');
$container['route']->addRoute('user/:user_id/notifications', 'ProfileController', 'notifications');
$container['route']->addRoute('user/:user_id/accounts', 'ProfileController', 'external');
$container['route']->addRoute('user/:user_id/integrations', 'ProfileController', 'integrations');

$container['route']->addRoute('user/:user_id/2fa', 'TwoFactorController', 'index');
$container['route']->addRoute('user/:user_id/avatar', 'AvatarFileController', 'show');
$container['route']->addRoute('user/:user_id/avatar/:size/image', 'AvatarFileController', 'image');

$container['route']->addRoute('user/ajax/status', 'UserAjaxController', 'status');
