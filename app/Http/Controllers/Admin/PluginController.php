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

use Jitamin\Foundation\Plugin\Directory;
use Jitamin\Foundation\Plugin\Installer;
use Jitamin\Foundation\Plugin\PluginInstallerException;
use Jitamin\Http\Controllers\Controller;

/**
 * Class PluginController.
 */
class PluginController extends Controller
{
    /**
     * Display the plugin page.
     */
    public function show()
    {
        $this->response->html($this->helper->layout->admin('admin/plugin/show', [
            'plugins'       => $this->pluginLoader->getPlugins(),
            'title'         => t('Installed Plugins'),
            'is_configured' => Installer::isConfigured(),
        ], 'admin/plugin/subside'));
    }

    /**
     * Display list of available plugins.
     */
    public function directory()
    {
        $installedPlugins = [];

        foreach ($this->pluginLoader->getPlugins() as $plugin) {
            $installedPlugins[$plugin->getPluginName()] = $plugin->getPluginVersion();
        }

        $this->response->html($this->helper->layout->admin('admin/plugin/directory', [
            'installed_plugins' => $installedPlugins,
            'available_plugins' => Directory::getInstance($this->container)->getAvailablePlugins(),
            'title'             => t('Plugin Directory'),
            'is_configured'     => Installer::isConfigured(),
        ], 'admin/plugin/subside'));
    }

    /**
     * Install plugin from URL.
     *
     * @throws \Jitamin\Foundation\Controller\AccessForbiddenException
     */
    public function install()
    {
        $pluginArchiveUrl = urldecode($this->request->getStringParam('archive_url'));

        try {
            $installer = new Installer($this->container);
            $installer->install($pluginArchiveUrl);
            $this->flash->success(t('Plugin installed successfully.'));
        } catch (PluginInstallerException $e) {
            $this->flash->failure($e->getMessage());
        }

        $this->response->redirect($this->helper->url->to('Admin/PluginController', 'show'));
    }

    /**
     * Update plugin from URL.
     *
     * @throws \Jitamin\Foundation\Controller\AccessForbiddenException
     */
    public function update()
    {
        $pluginArchiveUrl = urldecode($this->request->getStringParam('archive_url'));

        try {
            $installer = new Installer($this->container);
            $installer->update($pluginArchiveUrl);
            $this->flash->success(t('Plugin updated successfully.'));
        } catch (PluginInstallerException $e) {
            $this->flash->failure($e->getMessage());
        }

        $this->response->redirect($this->helper->url->to('Admin/PluginController', 'show'));
    }

    /**
     * Remove a plugin.
     *
     * @throws \Jitamin\Foundation\Controller\AccessForbiddenException
     */
    public function uninstall()
    {
        $pluginId = $this->request->getStringParam('pluginId');

        if ($this->request->isPost()) {
            try {
                $this->request->checkCSRFToken();
                $installer = new Installer($this->container);
                $installer->uninstall($pluginId);
                $this->flash->success(t('Plugin removed successfully.'));
            } catch (PluginInstallerException $e) {
                $this->flash->failure($e->getMessage());
            }

            return $this->response->redirect($this->helper->url->to('Admin/PluginController', 'show'));
        }

        $plugins = $this->pluginLoader->getPlugins();

        return $this->response->html($this->template->render('admin/plugin/remove', [
            'plugin_id' => $pluginId,
            'plugin'    => $plugins[$pluginId],
        ]));
    }
}
