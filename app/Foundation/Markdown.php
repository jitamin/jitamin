<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation;

use Parsedown;
use Pimple\Container;

/**
 * Specific Markdown rules.
 */
class Markdown extends Parsedown
{
    /**
     * Task links generated will use the project token instead.
     *
     * @var bool
     */
    private $isPublicLink = false;

    /**
     * Container.
     *
     * @var Container
     */
    private $container;

    /**
     * Constructor.
     *
     * @param Container $container
     * @param bool      $isPublicLink
     */
    public function __construct(Container $container, $isPublicLink)
    {
        $this->isPublicLink = $isPublicLink;
        $this->container = $container;
        $this->InlineTypes['#'][] = 'TaskLink';
        $this->InlineTypes['@'][] = 'UserLink';
        $this->inlineMarkerList .= '#@';
    }

    /**
     * Handle Task Links.
     *
     * Replace "#123" by a link to the task
     *
     * @param array $Excerpt
     *
     * @return array|null
     */
    protected function inlineTaskLink(array $Excerpt)
    {
        if (preg_match('!#(\d+)!i', $Excerpt['text'], $matches)) {
            $link = $this->buildTaskLink($matches[1]);

            if (!empty($link)) {
                return [
                    'extent'  => strlen($matches[0]),
                    'element' => [
                        'name'       => 'a',
                        'text'       => $matches[0],
                        'attributes' => ['href' => $link],
                    ],
                ];
            }
        }
    }

    /**
     * Handle User Mentions.
     *
     * Replace "@username" by a link to the user
     *
     * @param array $Excerpt
     *
     * @return array|null
     */
    protected function inlineUserLink(array $Excerpt)
    {
        if (!$this->isPublicLink && preg_match('/^@([^\s]+)/', $Excerpt['text'], $matches)) {
            $user_id = $this->container['userModel']->getIdByUsername($matches[1]);

            if (!empty($user_id)) {
                $url = $this->container['helper']->url->href('Profile/ProfileController', 'profile', ['user_id' => $user_id]);

                return [
                    'extent'  => strlen($matches[0]),
                    'element' => [
                        'name'       => 'a',
                        'text'       => $matches[0],
                        'attributes' => ['href' => $url, 'class' => 'user-mention-link'],
                    ],
                ];
            }
        }
    }

    /**
     * Build task link.
     *
     * @param int $task_id
     *
     * @return string
     */
    private function buildTaskLink($task_id)
    {
        if ($this->isPublicLink) {
            $token = $this->container['memoryCache']->proxy($this->container['taskFinderModel'], 'getProjectToken', $task_id);

            if (!empty($token)) {
                return $this->container['helper']->url->href(
                    'Task/TaskController',
                    'readonly',
                    [
                        'token'   => $token,
                        'task_id' => $task_id,
                    ]
                );
            }

            return '';
        }

        return $this->container['helper']->url->href(
            'Task/TaskController',
            'show',
            ['task_id' => $task_id]
        );
    }
}
