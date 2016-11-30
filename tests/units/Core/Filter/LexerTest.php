<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Core\Filter\Lexer;

class LexerTest extends Base
{
    public function testTokenizeWithNoDefaultToken()
    {
        $lexer = new Lexer();
        $this->assertSame([], $lexer->tokenize('This is Hiject'));
    }

    public function testTokenizeWithDefaultToken()
    {
        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'myDefaultToken' => ['This is Hiject'],
        ];

        $this->assertSame($expected, $lexer->tokenize('This is Hiject'));
    }

    public function testTokenizeWithCustomToken()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(assignee:)/', 'T_USER');

        $expected = [
            'T_USER' => ['admin'],
        ];

        $this->assertSame($expected, $lexer->tokenize('assignee:admin something else'));
    }

    public function testTokenizeWithCustomTokenAndDefaultToken()
    {
        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');
        $lexer->addToken('/^(assignee:)/', 'T_USER');

        $expected = [
            'T_USER'         => ['admin'],
            'myDefaultToken' => ['something else'],
        ];

        $this->assertSame($expected, $lexer->tokenize('assignee:admin something else'));
    }

    public function testTokenizeWithQuotedString()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(assignee:)/', 'T_USER');

        $expected = [
            'T_USER' => ['Foo Bar'],
        ];

        $this->assertSame($expected, $lexer->tokenize('assignee:"Foo Bar" something else'));
    }

    public function testTokenizeWithNumber()
    {
        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'myDefaultToken' => ['#123'],
        ];

        $this->assertSame($expected, $lexer->tokenize('#123'));
    }

    public function testTokenizeWithStringDate()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['today'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:today something else'));
    }

    public function testTokenizeWithStringDateWithSpaces()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['last month'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:"last month" something else'));
    }

    public function testTokenizeWithStringDateWithSpacesAndOperator()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['<=last month'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:<="last month" something else'));

        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['>=next month'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:>="next month" something else'));

        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['<+2 days'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:<"+2 days" something else'));

        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['<-1 hour'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:<"-1 hour" something else'));
    }

    public function testTokenizeWithStringDateAndOperator()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['<=today'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:<=today something else'));

        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['>now'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:>now something else'));

        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['>=now'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:>=now something else'));
    }

    public function testTokenizeWithIsoDate()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(date:)/', 'T_MY_DATE');

        $expected = [
            'T_MY_DATE' => ['<=2016-01-01'],
        ];

        $this->assertSame($expected, $lexer->tokenize('date:<=2016-01-01 something else'));
    }

    public function testTokenizeWithUtf8Letters()
    {
        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'myDefaultToken' => ['àa éçùe'],
        ];

        $this->assertSame($expected, $lexer->tokenize('àa éçùe'));
    }

    public function testTokenizeWithUtf8Numbers()
    {
        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'myDefaultToken' => ['६Δↈ五一'],
        ];

        $this->assertSame($expected, $lexer->tokenize('६Δↈ五一'));
    }

    public function testTokenizeWithMultipleValues()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(tag:)/', 'T_TAG');

        $expected = [
            'T_TAG' => ['tag 1', 'tag2'],
        ];

        $this->assertSame($expected, $lexer->tokenize('tag:"tag 1" tag:tag2'));
    }

    public function testTokenizeWithDash()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(test:)/', 'T_TEST');

        $expected = [
            'T_TEST' => ['PO-123'],
        ];

        $this->assertSame($expected, $lexer->tokenize('test:PO-123'));

        $lexer = new Lexer();
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'myDefaultToken' => ['PO-123'],
        ];

        $this->assertSame($expected, $lexer->tokenize('PO-123'));
    }

    public function testTokenizeWithUnderscore()
    {
        $lexer = new Lexer();
        $lexer->addToken('/^(test:)/', 'T_TEST');

        $expected = [
            'T_TEST' => ['PO_123'],
        ];

        $this->assertSame($expected, $lexer->tokenize('test:PO_123'));

        $lexer = new Lexer();
        $lexer->addToken('/^(test:)/', 'T_TEST');
        $lexer->setDefaultToken('myDefaultToken');

        $expected = [
            'T_TEST'         => ['ABC-123'],
            'myDefaultToken' => ['PO_123'],
        ];

        $this->assertSame($expected, $lexer->tokenize('test:ABC-123 PO_123'));
    }
}
