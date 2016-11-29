<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

/**
 * Config Controller
 */
class ConfigController extends BaseController
{
    /**
     * Display the application settings page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->helper->layout->config('config/application', [
            'mail_transports' => $this->emailClient->getAvailableTransports(),
            'skins' => $this->skinModel->getSkins(),
            'languages' => $this->languageModel->getLanguages(),
            'timezones' => $this->timezoneModel->getTimezones(),
            'date_formats' => $this->dateParser->getAvailableFormats($this->dateParser->getDateFormats()),
            'datetime_formats' => $this->dateParser->getAvailableFormats($this->dateParser->getDateTimeFormats()),
            'time_formats' => $this->dateParser->getAvailableFormats($this->dateParser->getTimeFormats()),
            'title' => t('Settings').' &raquo; '.t('Application settings'),
        ]));
    }

    /**
     * Display the email settings page
     *
     * @access public
     */
    public function email()
    {
        $values = $this->configModel->getAll();

        if (empty($values['mail_transport'])) {
            $values['mail_transport'] = MAIL_TRANSPORT;
        }

        $this->response->html($this->helper->layout->config('config/email', [
            'values' => $values,
            'mail_transports' => $this->emailClient->getAvailableTransports(),
            'title' => t('Settings').' &raquo; '.t('Email settings'),
        ]));
    }

    /**
     * Display the project settings page
     *
     * @access public
     */
    public function project()
    {
        $this->response->html($this->helper->layout->config('config/project', [
            'colors' => $this->colorModel->getList(),
            'default_columns' => implode(', ', $this->boardModel->getDefaultColumns()),
            'title' => t('Settings').' &raquo; '.t('Project settings'),
        ]));
    }

    /**
     * Display the board settings page
     *
     * @access public
     */
    public function board()
    {
        $this->response->html($this->helper->layout->config('config/board', [
            'title' => t('Settings').' &raquo; '.t('Board settings'),
        ]));
    }

    /**
     * Display the calendar settings page
     *
     * @access public
     */
    public function calendar()
    {
        $this->response->html($this->helper->layout->config('config/calendar', [
            'title' => t('Settings').' &raquo; '.t('Calendar settings'),
        ]));
    }

    /**
     * Display the integration settings page
     *
     * @access public
     */
    public function integrations()
    {
        $this->response->html($this->helper->layout->config('config/integrations', [
            'title' => t('Settings').' &raquo; '.t('Integrations'),
        ]));
    }

    /**
     * Display the webhook settings page
     *
     * @access public
     */
    public function webhook()
    {
        $this->response->html($this->helper->layout->config('config/webhook', [
            'title' => t('Settings').' &raquo; '.t('Webhook settings'),
        ]));
    }

    /**
     * Display the api settings page
     *
     * @access public
     */
    public function api()
    {
        $this->response->html($this->helper->layout->config('config/api', [
            'title' => t('Settings').' &raquo; '.t('API'),
        ]));
    }

    /**
     * Display the help page
     *
     * @access public
     */
    public function help()
    {
        $this->response->html($this->helper->layout->config('config/help', [
            'db_size' => $this->configModel->getDatabaseSize(),
            'db_version' => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent' => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title' => t('Settings').' &raquo; '.t('About'),
        ]));
    }

    /**
     * Display the about page
     *
     * @access public
     */
    public function about()
    {
        $this->response->html($this->helper->layout->config('config/about', [
            'db_size' => $this->configModel->getDatabaseSize(),
            'db_version' => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent' => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title' => t('Settings').' &raquo; '.t('About'),
        ]));
    }

    /**
     * Save settings
     *
     */
    public function save()
    {
        $values =  $this->request->getValues();
        $redirect = $this->request->getStringParam('redirect', 'index');

        switch ($redirect) {
            case 'index':
                $values += ['password_reset' => 0];
                break;
            case 'project':
                $values += [
                    'subtask_restriction' => 0,
                    'subtask_time_tracking' => 0,
                    'cfd_include_closed_tasks' => 0,
                    'disable_private_project' => 0,
                ];
                break;
            case 'integrations':
                $values += ['integration_gravatar' => 0];
                break;
            case 'calendar':
                $values += ['calendar_user_subtasks_time_tracking' => 0];
                break;
        }

        if ($this->configModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigController', $redirect));
    }

    /**
     * Download the Sqlite database
     *
     * @access public
     */
    public function downloadDb()
    {
        $this->checkCSRFParam();
        $this->response->withFileDownload('db.sqlite.gz');
        $this->response->binary($this->configModel->downloadDatabase());
    }

    /**
     * Optimize the Sqlite database
     *
     * @access public
     */
    public function optimizeDb()
    {
        $this->checkCSRFParam();
        $this->configModel->optimizeDatabase();
        $this->flash->success(t('Database optimization done.'));
        $this->response->redirect($this->helper->url->to('ConfigController', 'index'));
    }

    /**
     * Regenerate webhook token
     *
     * @access public
     */
    public function token()
    {
        $type = $this->request->getStringParam('type');

        $this->checkCSRFParam();
        $this->configModel->regenerateToken($type.'_token');

        $this->flash->success(t('Token regenerated.'));
        $this->response->redirect($this->helper->url->to('ConfigController', $type));
    }
}
