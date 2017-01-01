<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Admin;

use Jitamin\Controller\BaseController;

/**
 * Admin Controller.
 */
class AdminController extends BaseController
{
    /**
     * Display the help page.
     */
    public function help()
    {
        $this->response->html($this->helper->layout->admin('admin/help', [
            'db_size'    => $this->settingModel->getDatabaseSize(),
            'db_version' => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent' => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title'      => t('Settings').' &raquo; '.t('About'),
        ]));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        $this->response->html($this->helper->layout->admin('admin/about', [
            'db_size'    => $this->settingModel->getDatabaseSize(),
            'db_version' => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent' => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title'      => t('Settings').' &raquo; '.t('About'),
        ]));
    }
}
