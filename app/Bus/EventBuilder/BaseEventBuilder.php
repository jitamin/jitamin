<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\EventBuilder;

use Jitamin\Bus\Event\GenericEvent;
use Jitamin\Foundation\Base;

/**
 * Class BaseEventBuilder.
 */
abstract class BaseEventBuilder extends Base
{
    /**
     * Build event data.
     *
     * @return GenericEvent|null
     */
    abstract public function buildEvent();

    /**
     * Get event title with author.
     *
     * @param string $author
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    abstract public function buildTitleWithAuthor($author, $eventName, array $eventData);

    /**
     * Get event title without author.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    abstract public function buildTitleWithoutAuthor($eventName, array $eventData);
}
