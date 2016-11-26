<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Decorator\MetadataCacheDecorator;

require_once __DIR__.'/../Base.php';

class MetadataCacheDecoratorTest extends Base
{
    protected $cachePrefix = 'cache_prefix';
    protected $entityId = 123;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cacheMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $metadataModelMock;

    /**
     * @var MetadataCacheDecorator
     */
    protected $metadataCacheDecorator;

    public function setUp()
    {
        parent::setUp();

        $this->cacheMock = $this
            ->getMockBuilder('\Hiject\Core\Cache\MemoryCache')
            ->setMethods([
                'set',
                'get',
            ])
            ->getMock();

        $this->metadataModelMock = $this
            ->getMockBuilder('\Hiject\Model\UserMetadataModel')
            ->setConstructorArgs([$this->container])
            ->setMethods([
                'getAll',
                'save',
            ])
            ->getMock()
        ;

        $this->metadataCacheDecorator = new MetadataCacheDecorator(
            $this->cacheMock,
            $this->metadataModelMock,
            $this->cachePrefix,
            $this->entityId
        );
    }

    public function testSet()
    {
        $this->cacheMock
            ->expects($this->once())
            ->method('set');

        $this->metadataModelMock
            ->expects($this->at(0))
            ->method('save');

        $this->metadataModelMock
            ->expects($this->at(1))
            ->method('getAll')
            ->with($this->entityId)
        ;

        $this->metadataCacheDecorator->set('key', 'value');
    }

    public function testGetWithCache()
    {
        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with($this->cachePrefix.$this->entityId)
            ->will($this->returnValue(['key' => 'foobar']))
        ;

        $this->assertEquals('foobar', $this->metadataCacheDecorator->get('key', 'default'));
    }

    public function testGetWithCacheAndDefaultValue()
    {
        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with($this->cachePrefix.$this->entityId)
            ->will($this->returnValue(['key1' => 'foobar']))
        ;

        $this->assertEquals('default', $this->metadataCacheDecorator->get('key', 'default'));
    }

    public function testGetWithoutCache()
    {
        $this->cacheMock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->cachePrefix.$this->entityId)
            ->will($this->returnValue(null))
        ;

        $this->cacheMock
            ->expects($this->at(1))
            ->method('set')
            ->with(
                $this->cachePrefix.$this->entityId,
                ['key' => 'something']
            )
        ;

        $this->metadataModelMock
            ->expects($this->once())
            ->method('getAll')
            ->with($this->entityId)
            ->will($this->returnValue(['key' => 'something']))
        ;

        $this->assertEquals('something', $this->metadataCacheDecorator->get('key', 'default'));
    }
}
