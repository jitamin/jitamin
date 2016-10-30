<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Plugin;

use ZipArchive;
use Hiject\Core\Tool;

/**
 * Class Installer
 */
class Installer extends \Hiject\Core\Base
{
    /**
     * Return true if Hiject is configured to install plugins
     *
     * @static
     * @access public
     * @return bool
     */
    public static function isConfigured()
    {
        return PLUGIN_INSTALLER && is_writable(PLUGINS_DIR) && extension_loaded('zip');
    }

    /**
     * Install a plugin
     *
     * @access public
     * @param  string $archiveUrl
     * @throws PluginInstallerException
     */
    public function install($archiveUrl)
    {
        $zip = $this->downloadPluginArchive($archiveUrl);

        if (! $zip->extractTo(PLUGINS_DIR)) {
            $this->cleanupArchive($zip);
            throw new PluginInstallerException(t('Unable to extract plugin archive.'));
        }

        $this->cleanupArchive($zip);
    }

    /**
     * Uninstall a plugin
     *
     * @access public
     * @param  string $pluginId
     * @throws PluginInstallerException
     */
    public function uninstall($pluginId)
    {
        $pluginFolder = PLUGINS_DIR.DIRECTORY_SEPARATOR.basename($pluginId);

        if (! file_exists($pluginFolder)) {
            throw new PluginInstallerException(t('Plugin not found.'));
        }

        if (! is_writable($pluginFolder)) {
            throw new PluginInstallerException(e('You don\'t have the permission to remove this plugin.'));
        }

        Tool::removeAllFiles($pluginFolder);
    }

    /**
     * Update a plugin
     *
     * @access public
     * @param  string $archiveUrl
     * @throws PluginInstallerException
     */
    public function update($archiveUrl)
    {
        $zip = $this->downloadPluginArchive($archiveUrl);

        $firstEntry = $zip->statIndex(0);
        $this->uninstall($firstEntry['name']);

        if (! $zip->extractTo(PLUGINS_DIR)) {
            $this->cleanupArchive($zip);
            throw new PluginInstallerException(t('Unable to extract plugin archive.'));
        }

        $this->cleanupArchive($zip);
    }

    /**
     * Download archive from URL
     *
     * @access protected
     * @param  string $archiveUrl
     * @return ZipArchive
     * @throws PluginInstallerException
     */
    protected function downloadPluginArchive($archiveUrl)
    {
        $zip = new ZipArchive();
        $archiveData = $this->httpClient->get($archiveUrl);
        $archiveFile = tempnam(sys_get_temp_dir(), 'kb_plugin');

        if (empty($archiveData)) {
            unlink($archiveFile);
            throw new PluginInstallerException(t('Unable to download plugin archive.'));
        }

        if (file_put_contents($archiveFile, $archiveData) === false) {
            unlink($archiveFile);
            throw new PluginInstallerException(t('Unable to write temporary file for plugin.'));
        }

        if ($zip->open($archiveFile) !== true) {
            unlink($archiveFile);
            throw new PluginInstallerException(t('Unable to open plugin archive.'));
        }

        if ($zip->numFiles === 0) {
            unlink($archiveFile);
            throw new PluginInstallerException(t('There is no file in the plugin archive.'));
        }

        return $zip;
    }

    /**
     * Remove archive file
     *
     * @access protected
     * @param ZipArchive $zip
     */
    protected function cleanupArchive(ZipArchive $zip)
    {
        unlink($zip->filename);
        $zip->close();
    }
}
