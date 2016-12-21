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

use Jitamin\ExternalLink\AttachmentLink;

class AttachmentLinkTest extends Base
{
    public function testGetTitleFromUrl()
    {
        $url = 'https://jitamin.net/folder/document.pdf';

        $link = new AttachmentLink($this->container);
        $link->setUrl($url);
        $this->assertEquals($url, $link->getUrl());
        $this->assertEquals('document.pdf', $link->getTitle());
    }
}
