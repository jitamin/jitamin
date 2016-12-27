<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

/**
 * App API controller.
 */
class AppProcedure extends BaseProcedure
{
    /**
     * Get current timezone.
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezoneModel->getCurrentTimezone();
    }

    /**
     * Get current app version.
     *
     * @return string
     */
    public function getVersion()
    {
        return APP_VERSION;
    }

    /**
     * Get the default color.
     *
     * @return string
     */
    public function getDefaultTaskColor()
    {
        return $this->colorModel->getDefaultColor();
    }

    /**
     * Get the default colors.
     *
     * @return array
     */
    public function getDefaultTaskColors()
    {
        return $this->colorModel->getDefaultColors();
    }

    /**
     * Get available colors.
     *
     * @return array
     */
    public function getColorList()
    {
        return $this->colorModel->getList();
    }

    /**
     * Get application roles.
     *
     * @return array
     */
    public function getApplicationRoles()
    {
        return $this->role->getApplicationRoles();
    }

    /**
     * Get project roles.
     *
     * @return array
     */
    public function getProjectRoles()
    {
        return $this->role->getProjectRoles();
    }
}
