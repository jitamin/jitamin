<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\ExternalLink;

use Hiject\Core\Base;

/**
 * Base Link Provider
 */
abstract class BaseLinkProvider extends Base
{
    /**
     * User input
     *
     * @access protected
     * @var string
     */
    protected $userInput = '';

    /**
     * Set text entered by the user
     *
     * @access public
     * @param  string $input
     */
    public function setUserTextInput($input)
    {
        $this->userInput = trim($input);
    }
}
