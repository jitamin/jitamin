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

use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectNotificationTypeModel;

class ProjectNotificationTypeTest extends Base
{
    public function testGetTypes()
    {
        $nt = new ProjectNotificationTypeModel($this->container);
        $this->assertEmpty($nt->getTypes());

        $nt->setType('foo', 'Foo', 'Something1');
        $nt->setType('bar', 'Bar', 'Something2');
        $nt->setType('baz', 'Baz', 'Something3', true);

        $this->assertEquals(['bar' => 'Bar', 'foo' => 'Foo'], $nt->getTypes());
        $this->assertEquals(['baz'], $nt->getHiddenTypes());
    }

    public function testGetSelectedTypes()
    {
        $nt = new ProjectNotificationTypeModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest']));

        // No type defined
        $this->assertEmpty($nt->getSelectedTypes(1));

        // Hidden type
        $nt->setType('baz', 'Baz', 'Something3', true);
        $this->assertEmpty($nt->getSelectedTypes(1));

        // User defined types but not registered
        $this->assertTrue($nt->saveSelectedTypes(1, ['foo', 'bar']));
        $this->assertEmpty($nt->getSelectedTypes(1));

        // User defined types and registered
        $nt->setType('bar', 'Bar', 'Something4');
        $nt->setType('foo', 'Foo', 'Something3');
        $this->assertEquals(['bar', 'foo'], $nt->getSelectedTypes(1));
    }
}
