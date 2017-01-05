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
     * Display the default page.
     */
    public function index()
    {
        $is_outdated = false;
        $current_version = APP_VERSION;
        $latest_version = APP_VERSION;
        if ($this->userSession->isAdmin()) {
            $latest_tag = str_replace(['V', 'v'], '', $this->updateManager->latest());
            $is_outdated = version_compare($latest_tag, APP_VERSION, '>');
            $current_version = APP_VERSION;
            $latest_version = $latest_tag;
        }

        $this->response->html($this->helper->layout->admin('admin/index', [
            'is_outdated'      => $is_outdated,
            'current_version'  => $current_version,
            'latest_version'   => $latest_version,
            'db_size'          => $this->settingModel->getDatabaseSize(),
            'db_version'       => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent'       => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title'            => t('Admin').' &raquo; '.t('Overview'),
        ], ''));
    }
}
