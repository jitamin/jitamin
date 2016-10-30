<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Formatter;

use Hiject\Core\Filter\FormatterInterface;
use Hiject\Core\Group\GroupProviderInterface;
use PicoDb\Table;

/**
 * Auto-complete formatter for groups
 */
class GroupAutoCompleteFormatter implements FormatterInterface
{
    /**
     * Groups found
     *
     * @access private
     * @var GroupProviderInterface[]
     */
    private $groups;

    /**
     * Format groups for the ajax auto-completion
     *
     * @access public
     * @param  GroupProviderInterface[] $groups
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Set query
     *
     * @access public
     * @param  Table $query
     * @return FormatterInterface
     */
    public function withQuery(Table $query)
    {
        return $this;
    }

    /**
     * Format groups for the ajax auto-completion
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $result = array();

        foreach ($this->groups as $group) {
            $result[] = array(
                'id' => $group->getInternalId(),
                'external_id' => $group->getExternalId(),
                'value' => $group->getName(),
                'label' => $group->getName(),
            );
        }

        return $result;
    }
}
