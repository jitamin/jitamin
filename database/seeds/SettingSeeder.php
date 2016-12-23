<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Core\Security\Token;
use Phinx\Seed\AbstractSeed;

class SettingSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $data = [
          [
              'option'    => 'application_language',
              'value'     => 'zh_CN',
          ],
          [
            'option' => 'application_date_format',
            'value'  => 'm/d/Y',
          ],

          [
            'option' => 'application_timezone',
            'value'  => 'UTC',
          ],
          [
            'option' => 'application_skin',
            'value'  => 'default',
          ],
          [
              'option'    => 'password_reset',
              'value'     => 1,
          ],
          [
              'option'    => 'cfd_include_closed_tasks',
              'value'     => 1,
          ],
          [
              'option'    => 'default_color',
              'value'     => 'yellow',
          ],
          [
              'option'    => 'subtask_restriction',
              'value'     => 0,
          ],
          [
              'option'    => 'subtask_time_tracking',
              'value'     => 0,
          ],
          [
              'option' => 'board_highlight_period',
              'value'  => defined('RECENT_TASK_PERIOD') ? RECENT_TASK_PERIOD : 48 * 60 * 60,
          ],
          [
              'option'    => 'board_public_refresh_interval',
              'value'     => defined('BOARD_PUBLIC_CHECK_INTERVAL') ? BOARD_PUBLIC_CHECK_INTERVAL : 60,
          ],
          [
              'option'    => 'board_private_refresh_interval',
              'value'     => defined('BOARD_CHECK_INTERVAL') ? BOARD_CHECK_INTERVAL : 10,
          ],
          [
              'option'    => 'board_columns',
              'value'     => '',
          ],
          [
              'option'    => 'webhook_token',
              'value'     => Token::getToken(),
          ],
          [
              'option'    => 'webhook_url',
              'value'     => '',
          ],
          [
              'option'    => 'integration_gravatar',
              'value'     => 0,
          ],
          [
              'option'    => 'api_token',
              'value'     => Token::getToken(),
          ],
          [
              'option'    => 'calendar_user_subtasks_time_tracking',
              'value'     => 0,
          ],
          [
              'option'    => 'calendar_project_tasks',
              'value'     => 'date_started',
          ],
          [
              'option'    => 'calendar_user_tasks',
              'value'     => 'date_started',
          ],
        ];

        $settings = $this->table('settings');
        $settings->insert($data)
              ->save();
    }
}
