<?php

$header = <<<EOF
This file is part of Jitamin.

Copyright (C) 2016 Jitamin Team

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

$finder = Symfony\Component\Finder\Finder::create()
    ->files()
    ->in('app')
    ->in('bootstrap')
    ->in('config')
    ->in('database')
    ->in('tests')
    ->in('routes')
    ->in('public')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$fixers = [
    'header_comment',
];

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers($fixers)
    ->finder($finder)
    ->setUsingCache(true);