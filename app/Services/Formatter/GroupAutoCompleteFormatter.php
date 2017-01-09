<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Foundation\Filter\FormatterInterface;
use Jitamin\Foundation\Group\GroupProviderInterface;
use PicoDb\Table;

/**
 * Auto-complete formatter for groups.
 */
class GroupAutoCompleteFormatter implements FormatterInterface
{
    /**
     * Groups found.
     *
     * @var GroupProviderInterface[]
     */
    private $groups;

    /**
     * Format groups for the ajax auto-completion.
     *
     * @param GroupProviderInterface[] $groups
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Set query.
     *
     * @param Table $query
     *
     * @return FormatterInterface
     */
    public function withQuery(Table $query)
    {
        return $this;
    }

    /**
     * Format groups for the ajax auto-completion.
     *
     * @return array
     */
    public function format()
    {
        $result = [];

        foreach ($this->groups as $group) {
            $result[] = [
                'id'          => $group->getInternalId(),
                'external_id' => $group->getExternalId(),
                'value'       => $group->getName(),
                'label'       => $group->getName(),
            ];
        }

        return $result;
    }
}
