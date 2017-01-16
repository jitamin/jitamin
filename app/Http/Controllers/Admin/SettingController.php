<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Admin;

use Jitamin\Http\Controllers\Controller;

/**
 * Setting Controller.
 */
class SettingController extends Controller
{
    /**
     * Display the application settings page.
     */
    public function index()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/application', [
            'mail_transports'  => $this->emailClient->getAvailableTransports(),
            'title'            => t('Settings').' &raquo; '.t('Application settings'),
        ]));
    }

    /**
     * Display the theme settings page.
     */
    public function theme()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/theme', [
            'skins'            => $this->skinModel->getSkins(),
            'layouts'          => $this->skinModel->getLayouts(),
            'dashboards'       => $this->skinModel->getDashboards(),
            'title'            => t('Settings').' &raquo; '.t('Theme settings'),
        ]));
    }

    /**
     * Display the localization settings page.
     */
    public function localization()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/localization', [
            'languages'        => $this->languageModel->getLanguages(),
            'timezones'        => $this->timezoneModel->getTimezones(),
            'date_formats'     => $this->dateParser->getAvailableFormats($this->dateParser->getDateFormats()),
            'datetime_formats' => $this->dateParser->getAvailableFormats($this->dateParser->getDateTimeFormats()),
            'time_formats'     => $this->dateParser->getAvailableFormats($this->dateParser->getTimeFormats()),
            'title'            => t('Settings').' &raquo; '.t('Localization settings'),
        ]));
    }

    /**
     * Display the email settings page.
     */
    public function email()
    {
        $values = $this->settingModel->getAll();

        if (empty($values['mail_transport'])) {
            $values['mail_transport'] = MAIL_TRANSPORT;
        }

        $this->response->html($this->helper->layout->admin('admin/setting/email', [
            'values'          => $values,
            'mail_transports' => $this->emailClient->getAvailableTransports(),
            'title'           => t('Settings').' &raquo; '.t('Email settings'),
        ]));
    }

    /**
     * Display the project settings page.
     */
    public function project()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/project', [
            'colors'          => $this->colorModel->getList(),
            'project_views'   => $this->projectModel->getViews(),
            'default_columns' => implode(', ', $this->boardModel->getDefaultColumns()),
            'title'           => t('Settings').' &raquo; '.t('Project settings'),
        ]));
    }

    /**
     * Display the board settings page.
     */
    public function board()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/board', [
            'title' => t('Settings').' &raquo; '.t('Board settings'),
        ]));
    }

    /**
     * Display the calendar settings page.
     */
    public function calendar()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/calendar', [
            'title' => t('Settings').' &raquo; '.t('Calendar settings'),
        ]));
    }

    /**
     * Display the integration settings page.
     */
    public function integrations()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/integrations', [
            'title' => t('Settings').' &raquo; '.t('Integrations'),
        ]));
    }

    /**
     * Display the webhook settings page.
     */
    public function webhook()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/webhook', [
            'title' => t('Settings').' &raquo; '.t('Webhook settings'),
        ]));
    }

    /**
     * Display the api settings page.
     */
    public function api()
    {
        $this->response->html($this->helper->layout->admin('admin/setting/api', [
            'title' => t('Settings').' &raquo; '.t('API'),
        ]));
    }

    /**
     * Save settings.
     */
    public function store()
    {
        $values = $this->request->getValues();
        $redirect = $this->request->getStringParam('redirect', 'index');

        switch ($redirect) {
            case 'index':
                $values += ['password_reset' => 0];
                break;
            case 'project':
                $values += [
                    'subtask_restriction'      => 0,
                    'subtask_time_tracking'    => 0,
                    'cfd_include_closed_tasks' => 0,
                    'disable_private_project'  => 0,
                ];
                break;
            case 'integrations':
                $values += ['integration_gravatar' => 0];
                break;
            case 'calendar':
                $values += ['calendar_user_subtasks_time_tracking' => 0];
                break;
        }

        if ($this->settingModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('Admin/SettingController', $redirect));
    }

    /**
     * Download the Sqlite database.
     */
    public function downloadDb()
    {
        $this->response->withFileDownload('db.sqlite.gz');
        $this->response->binary($this->settingModel->downloadDatabase());
    }

    /**
     * Optimize the Sqlite database.
     */
    public function optimizeDb()
    {
        $this->settingModel->optimizeDatabase();
        $this->flash->success(t('Database optimization done.'));
        $this->response->redirect($this->helper->url->to('Admin/SettingController', 'index'));
    }

    /**
     * Regenerate webhook token.
     */
    public function token()
    {
        $type = $this->request->getStringParam('type');

        $this->settingModel->regenerateToken($type.'_token');

        $this->flash->success(t('Token regenerated.'));
        $this->response->redirect($this->helper->url->to('Admin/SettingController', $type));
    }
}
