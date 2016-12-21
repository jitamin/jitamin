<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Core\Session\FlashMessage;
use Jitamin\Helper\AppHelper;

class AppHelperTest extends Base
{
    public function testJsLang()
    {
        $h = new AppHelper($this->container);
        $this->assertEquals('en', $h->jsLang());
    }

    public function testTimezone()
    {
        $h = new AppHelper($this->container);
        $this->assertEquals('UTC', $h->getTimezone());
    }

    public function testFlashMessage()
    {
        $h = new AppHelper($this->container);
        $f = new FlashMessage($this->container);

        $this->assertEmpty($h->flashMessage());

        $f->success('test & test');
        $this->assertEquals('<div class="alert alert-success alert-dismissible alert-fade-out"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>test &amp; test</div>', $h->flashMessage());
        $this->assertEmpty($h->flashMessage());

        $f->failure('test & test');
        $this->assertEquals('<div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>test &amp; test</div>', $h->flashMessage());
        $this->assertEmpty($h->flashMessage());
    }
}
