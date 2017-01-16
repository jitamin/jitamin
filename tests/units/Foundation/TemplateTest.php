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

use Jitamin\Foundation\Template;

class TemplateTest extends Base
{
    public function testGetTemplateFile()
    {
        $template = new Template($this->container['helper']);

        $this->assertStringEndsWith(
            implode(DIRECTORY_SEPARATOR, [JITAMIN_DIR, 'resources/views', 'a', 'b.php']),
            $template->getTemplateFile('a'.DIRECTORY_SEPARATOR.'b')
        );

        $this->assertStringEndsWith(
            implode(DIRECTORY_SEPARATOR, [JITAMIN_DIR, 'resources/views', 'a', 'b.php']),
            $template->getTemplateFile('jitamin:a'.DIRECTORY_SEPARATOR.'b')
        );
    }

    public function testGetPluginTemplateFile()
    {
        $template = new Template($this->container['helper']);
        $this->assertStringEndsWith(
            implode(DIRECTORY_SEPARATOR, [PLUGINS_DIR, 'Myplugin', 'resources/views', 'a', 'b.php']),
            $template->getTemplateFile('myplugin:a'.DIRECTORY_SEPARATOR.'b')
        );
    }

    public function testGetOverridedTemplateFile()
    {
        $template = new Template($this->container['helper']);
        $template->setTemplateOverride('a'.DIRECTORY_SEPARATOR.'b', 'myplugin:c');

        $this->assertStringEndsWith(
            implode(DIRECTORY_SEPARATOR, [PLUGINS_DIR, 'Myplugin', 'resources/views', 'c.php']),
            $template->getTemplateFile('a'.DIRECTORY_SEPARATOR.'b')
        );

        $this->assertStringEndsWith(
            implode(DIRECTORY_SEPARATOR, [JITAMIN_DIR, 'resources/views', 'd.php']),
            $template->getTemplateFile('d')
        );
    }
}
