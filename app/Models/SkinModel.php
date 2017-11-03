<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;

/**
 * Class Skin.
 */
class SkinModel extends Model
{
    /**
     * Get available skins.
     *
     * @param bool $prepend Prepend a default value
     *
     * @return array
     */
    public function getSkins($prepend = false)
    {
        // Sorted by value
        $skins = [
            'default' => t('Default'),
            'black'   => t('Black'),
            'blue'    => t('Blue'),
            'green'   => t('Green'),
            'purple'  => t('Purple'),
            'red'     => t('Red'),
            'white'   => t('White'),
            'yellow'  => t('Yellow'),
        ];

        if ($prepend) {
            return ['' => t('Use system skin')] + $skins;
        }

        return $skins;
    }

    /**
     * Get current skin.
     *
     * @return string
     */
    public function getCurrentSkin()
    {
        if ($this->userSession->isLogged() && !empty($this->sessionStorage->user['skin'])) {
            return $this->sessionStorage->user['skin'];
        }

        return $this->settingModel->get('application_skin', 'default');
    }

    /**
     * Get available layouts.
     *
     * @param bool $prepend Prepend a default value
     *
     * @return array
     */
    public function getLayouts($prepend = false)
    {
        // Sorted by value
        $layouts = [
            'fluid' => t('Fluid'),
            'fixed' => t('Fixed'),
        ];

        if ($prepend) {
            return ['' => t('Use system layout')] + $layouts;
        }

        return $layouts;
    }

    /**
     * Get current layout.
     *
     * @return string
     */
    public function getCurrentLayout()
    {
        if ($this->userSession->isLogged() && !empty($this->sessionStorage->user['layout'])) {
            return $this->sessionStorage->user['layout'];
        }

        return $this->settingModel->get('application_layout', '');
    }

    /**
     * Get available dashboards.
     *
     * @param bool $prepend Prepend a default value
     *
     * @return array
     */
    public function getDashboards($prepend = false)
    {
        // Sorted by value
        $dashboards = [
            'projects'   => t('My projects'),
            'stars'      => t('Starred projects'),
            'calendar'   => t('My calendar'),
            'activities' => t('My activities'),
            'tasks'      => t('My tasks'),
        ];

        if ($prepend) {
            return ['' => t('Use system dashboard')] + $dashboards;
        }

        return $dashboards;
    }

    /**
     * Get current dashboard.
     *
     * @return string
     */
    public function getCurrentDashboard()
    {
        if ($this->userSession->isLogged() && !empty($this->sessionStorage->user['dashboard'])) {
            return $this->sessionStorage->user['dashboard'];
        }

        return $this->settingModel->get('application_dashboard', '');
    }
}
