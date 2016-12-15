<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Database\Model;

/**
 * Class Timezone.
 */
class TimezoneModel extends Model
{
    /**
     * Get available timezones.
     *
     * @param bool $prepend Prepend a default value
     *
     * @return array
     */
    public function getTimezones($prepend = false)
    {
        $timezones = timezone_identifiers_list();
        $listing = array_combine(array_values($timezones), $timezones);

        if ($prepend) {
            return ['' => t('Use system timezone')] + $listing;
        }

        return $listing;
    }

    /**
     * Get current timezone.
     *
     * @return string
     */
    public function getCurrentTimezone()
    {
        if ($this->userSession->isLogged() && !empty($this->sessionStorage->user['timezone'])) {
            return $this->sessionStorage->user['timezone'];
        }

        return $this->settingModel->get('application_timezone', 'UTC');
    }

    /**
     * Set timezone.
     */
    public function setCurrentTimezone()
    {
        date_default_timezone_set($this->getCurrentTimezone());
    }
}
