<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Model\TimezoneModel;

class TimezoneTest extends Base
{
    public function testGetTimezones()
    {
        $timezoneModel = new TimezoneModel($this->container);
        $this->assertNotEmpty($timezoneModel->getTimezones());
        $this->assertArrayHasKey('Europe/Paris', $timezoneModel->getTimezones());
        $this->assertContains('Europe/Paris', $timezoneModel->getTimezones());
        $this->assertArrayNotHasKey('', $timezoneModel->getTimezones());

        $this->assertArrayHasKey('', $timezoneModel->getTimezones(true));
        $this->assertContains('Use system timezone', $timezoneModel->getTimezones(true));
    }

    public function testGetCurrentTimezone()
    {
        $timezoneModel = new TimezoneModel($this->container);
        $this->assertEquals('UTC', $timezoneModel->getCurrentTimezone());

        $this->container['sessionStorage']->user = ['timezone' => 'Europe/Paris'];
        $this->assertEquals('Europe/Paris', $timezoneModel->getCurrentTimezone());

        $this->container['sessionStorage']->user = ['timezone' => 'Something'];
        $this->assertEquals('Something', $timezoneModel->getCurrentTimezone());
    }
}
