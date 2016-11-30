<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Filter;

/**
 * Lexer.
 */
class Lexer
{
    /**
     * Current position.
     *
     * @var int
     */
    private $offset = 0;

    /**
     * Token map.
     *
     * @var array
     */
    private $tokenMap = [
        '/^(\s+)/'                                       => 'T_WHITESPACE',
        '/^([<=>]{0,2}[0-9]{4}-[0-9]{2}-[0-9]{2})/'      => 'T_STRING',
        '/^([<=>]{1,2}\w+)/u'                            => 'T_STRING',
        '/^([<=>]{1,2}".+")/'                            => 'T_STRING',
        '/^("(.+)")/'                                    => 'T_STRING',
        '/^(\S+)/u'                                      => 'T_STRING',
        '/^(#\d+)/'                                      => 'T_STRING',
    ];

    /**
     * Default token.
     *
     * @var string
     */
    private $defaultToken = '';

    /**
     * Add token.
     *
     * @param string $regex
     * @param string $token
     *
     * @return $this
     */
    public function addToken($regex, $token)
    {
        $this->tokenMap = [$regex => $token] + $this->tokenMap;

        return $this;
    }

    /**
     * Set default token.
     *
     * @param string $token
     *
     * @return $this
     */
    public function setDefaultToken($token)
    {
        $this->defaultToken = $token;

        return $this;
    }

    /**
     * Tokenize input string.
     *
     * @param string $input
     *
     * @return array
     */
    public function tokenize($input)
    {
        $tokens = [];
        $this->offset = 0;
        $input_length = mb_strlen($input, 'UTF-8');

        while ($this->offset < $input_length) {
            $result = $this->match(mb_substr($input, $this->offset, $input_length, 'UTF-8'));

            if ($result === false) {
                return [];
            }

            $tokens[] = $result;
        }

        return $this->map($tokens);
    }

    /**
     * Find a token that match and move the offset.
     *
     * @param string $string
     *
     * @return array|bool
     */
    protected function match($string)
    {
        foreach ($this->tokenMap as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                $this->offset += mb_strlen($matches[1], 'UTF-8');

                return [
                    'match' => str_replace('"', '', $matches[1]),
                    'token' => $name,
                ];
            }
        }

        return false;
    }

    /**
     * Build map of tokens and matches.
     *
     * @param array $tokens
     *
     * @return array
     */
    protected function map(array $tokens)
    {
        $map = [];
        $leftOver = '';

        while (false !== ($token = current($tokens))) {
            if ($token['token'] === 'T_STRING' || $token['token'] === 'T_WHITESPACE') {
                $leftOver .= $token['match'];
            } else {
                $next = next($tokens);

                if ($next !== false && $next['token'] === 'T_STRING') {
                    $map[$token['token']][] = $next['match'];
                }
            }

            next($tokens);
        }

        $leftOver = trim($leftOver);

        if ($this->defaultToken !== '' && $leftOver !== '') {
            $map[$this->defaultToken] = [$leftOver];
        }

        return $map;
    }
}
