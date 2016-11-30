<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Base extends PHPUnit_Extensions_Selenium2TestCase
{
    public function setUp()
    {
        $this->setHost(SELENIUM_HOST);
        $this->setPort((int) SELENIUM_PORT);
        $this->setBrowserUrl(HIJECT_APP_URL);
        $this->setBrowser(DEFAULT_BROWSER);
    }

    public function tearDown()
    {
        $this->stop();
    }
}
