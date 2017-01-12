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

// Search routes
'search'          => 'SearchController@index',
'search/activity' => 'SearchController@activity',

// Calendar routes
'calendar/{project_id}' => 'CalendarController@show',
'c/{project_id}'        => 'CalendarController@show',

// Feed routes
'feed/project/{token}' => 'FeedController@project',
'feed/user/{token}'    => 'FeedController@user',

// Ical routes
'ical/project/{token}' => 'ICalendarController@project',
'ical/user/{token}'    => 'ICalendarController@user',

// Doc
'help/{file}' => 'DocumentationController@show',
'help'        => 'DocumentationController@show',

];
