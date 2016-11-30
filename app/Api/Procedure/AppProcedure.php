<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Procedure;

/**
 * App API controller.
 */
class AppProcedure extends BaseProcedure
{
    public function getTimezone()
    {
        return $this->timezoneModel->getCurrentTimezone();
    }

    public function getVersion()
    {
        return APP_VERSION;
    }

    public function getDefaultTaskColor()
    {
        return $this->colorModel->getDefaultColor();
    }

    public function getDefaultTaskColors()
    {
        return $this->colorModel->getDefaultColors();
    }

    public function getColorList()
    {
        return $this->colorModel->getList();
    }

    public function getApplicationRoles()
    {
        return $this->role->getApplicationRoles();
    }

    public function getProjectRoles()
    {
        return $this->role->getProjectRoles();
    }
}
