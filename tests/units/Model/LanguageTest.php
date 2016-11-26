<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Model\LanguageModel;

require_once __DIR__.'/../Base.php';

class LanguageTest extends Base
{
    public function testGetCodes()
    {
        $codes = LanguageModel::getCodes();
        $this->assertContains('zh_CN', $codes);
        $this->assertContains('en_US', $codes);
    }

    public function testFindCode()
    {
        $this->assertSame('', LanguageModel::findCode('xx-XX'));
        $this->assertSame('zh_CN', LanguageModel::findCode('zh-CN'));
        $this->assertSame('en_US', LanguageModel::findCode('en-US'));
    }

    public function testGetJsLanguage()
    {
        $languageModel = new LanguageModel($this->container);
        $this->assertEquals('en', $languageModel->getJsLanguageCode());

        $this->container['sessionStorage']->user = ['language' => 'zh_CN'];
        $this->assertEquals('zh-cn', $languageModel->getJsLanguageCode());

        $this->container['sessionStorage']->user = ['language' => 'xx_XX'];
        $this->assertEquals('en', $languageModel->getJsLanguageCode());
    }

    public function testGetCurrentLanguage()
    {
        $languageModel = new LanguageModel($this->container);
        $this->assertEquals('en_US', $languageModel->getCurrentLanguage());

        $this->container['sessionStorage']->user = ['language' => 'zh_CN'];
        $this->assertEquals('zh_CN', $languageModel->getCurrentLanguage());

        $this->container['sessionStorage']->user = ['language' => 'xx_XX'];
        $this->assertEquals('xx_XX', $languageModel->getCurrentLanguage());
    }

    public function testGetLanguages()
    {
        $languageModel = new LanguageModel($this->container);
        $this->assertNotEmpty($languageModel->getLanguages());
        $this->assertArrayHasKey('zh_CN', $languageModel->getLanguages());
        $this->assertContains('中文(简体)', $languageModel->getLanguages());
        $this->assertArrayNotHasKey('', $languageModel->getLanguages());

        $this->assertArrayHasKey('', $languageModel->getLanguages(true));
        $this->assertContains('Application default', $languageModel->getLanguages(true));
    }
}
