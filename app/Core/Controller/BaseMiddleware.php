<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Controller;

use Jitamin\Core\Base;

/**
 * Class BaseMiddleware.
 */
abstract class BaseMiddleware extends Base
{
    /**
     * @var BaseMiddleware
     */
    protected $nextMiddleware = null;

    /**
     * Execute middleware.
     */
    abstract public function execute();

    /**
     * Set next middleware.
     *
     * @param BaseMiddleware $nextMiddleware
     *
     * @return BaseMiddleware
     */
    public function setNextMiddleware(BaseMiddleware $nextMiddleware)
    {
        $this->nextMiddleware = $nextMiddleware;

        return $this;
    }

    /**
     * @return BaseMiddleware
     */
    public function getNextMiddleware()
    {
        return $this->nextMiddleware;
    }

    /**
     * Move to next middleware.
     */
    public function next()
    {
        if ($this->nextMiddleware !== null) {
            if (DEBUG) {
                $this->logger->debug(__METHOD__.' => '.get_class($this->nextMiddleware));
            }

            $this->nextMiddleware->execute();
        }
    }
}
