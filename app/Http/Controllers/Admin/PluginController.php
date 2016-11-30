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

use Hiject\Core\Plugin\Directory;
use Hiject\Core\Plugin\Installer;
use Hiject\Core\Plugin\PluginInstallerException;

/**
 * Class PluginController.
 */
class PluginController extends BaseController
{
    /**
     * Display the plugin page.
     */
    public function show()
    {
        $this->response->html($this->helper->layout->plugin('plugin/show', [
            'plugins'       => $this->pluginLoader->getPlugins(),
            'title'         => t('Installed Plugins'),
            'is_configured' => Installer::isConfigured(),
        ]));
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

        $this->response->html($this->helper->layout->plugin('plugin/directory', [
            'installed_plugins' => $installedPlugins,
            'available_plugins' => Directory::getInstance($this->container)->getAvailablePlugins(),
            'title'             => t('Plugin Directory'),
            'is_configured'     => Installer::isConfigured(),
        ]));
    }

    /**
     * Install plugin from URL.
     *
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     */
    public function install()
    {
        $this->checkCSRFParam();
        $pluginArchiveUrl = urldecode($this->request->getStringParam('archive_url'));

        try {
            $installer = new Installer($this->container);
            $installer->install($pluginArchiveUrl);
            $this->flash->success(t('Plugin installed successfully.'));
        } catch (PluginInstallerException $e) {
            $this->flash->failure($e->getMessage());
        }

        $this->response->redirect($this->helper->url->to('PluginController', 'show'));
    }

    /**
     * Update plugin from URL.
     *
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     */
    public function update()
    {
        $this->checkCSRFParam();
        $pluginArchiveUrl = urldecode($this->request->getStringParam('archive_url'));

        try {
            $installer = new Installer($this->container);
            $installer->update($pluginArchiveUrl);
            $this->flash->success(t('Plugin updated successfully.'));
        } catch (PluginInstallerException $e) {
            $this->flash->failure($e->getMessage());
        }

        $this->response->redirect($this->helper->url->to('PluginController', 'show'));
    }

    /**
     * Confirmation before to remove the plugin.
     */
    public function confirm()
    {
        $pluginId = $this->request->getStringParam('pluginId');
        $plugins = $this->pluginLoader->getPlugins();

        $this->response->html($this->template->render('plugin/remove', [
            'plugin_id' => $pluginId,
            'plugin'    => $plugins[$pluginId],
        ]));
    }

    /**
     * Remove a plugin.
     *
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     */
    public function uninstall()
    {
        $this->checkCSRFParam();
        $pluginId = $this->request->getStringParam('pluginId');

        try {
            $installer = new Installer($this->container);
            $installer->uninstall($pluginId);
            $this->flash->success(t('Plugin removed successfully.'));
        } catch (PluginInstallerException $e) {
            $this->flash->failure($e->getMessage());
        }

        $this->response->redirect($this->helper->url->to('PluginController', 'show'));
    }
}
