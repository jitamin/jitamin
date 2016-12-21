<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

use DateTime;
use Jitamin\Core\Controller\AccessForbiddenException;
use PicoFeed\Syndication\AtomFeedBuilder;
use PicoFeed\Syndication\AtomItemBuilder;
use PicoFeed\Syndication\FeedBuilder;

/**
 * Atom/RSS Feed controller.
 */
class FeedController extends BaseController
{
    /**
     * RSS feed for a user.
     */
    public function user()
    {
        $token = $this->request->getStringParam('token');
        $user = $this->userModel->getByToken($token);

        // Token verification
        if (empty($user)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        $events = $this->helper->projectActivity->getProjectsEvents($this->projectPermissionModel->getActiveProjectIds($user['id']));

        $feedBuilder = AtomFeedBuilder::create()
            ->withTitle(e('Project activities for %s', $this->helper->user->getFullname($user)))
            ->withFeedUrl($this->helper->url->to('FeedController', 'user', ['token' => $user['token']], '', true))
            ->withSiteUrl($this->helper->url->base())
            ->withDate(new DateTime());

        $this->response->xml($this->buildFeedItems($events, $feedBuilder)->build());
    }

    /**
     * RSS feed for a project.
     */
    public function project()
    {
        $token = $this->request->getStringParam('token');
        $project = $this->projectModel->getByToken($token);

        if (empty($project)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        $events = $this->helper->projectActivity->getProjectEvents($project['id']);

        $feedBuilder = AtomFeedBuilder::create()
            ->withTitle(e('%s\'s activity', $project['name']))
            ->withFeedUrl($this->helper->url->to('FeedController', 'project', ['token' => $project['token']], '', true))
            ->withSiteUrl($this->helper->url->base())
            ->withDate(new DateTime());

        $this->response->xml($this->buildFeedItems($events, $feedBuilder)->build());
    }

    /**
     * Build feed items.
     *
     * @param array       $events
     * @param FeedBuilder $feedBuilder
     *
     * @return FeedBuilder
     */
    protected function buildFeedItems(array $events, FeedBuilder $feedBuilder)
    {
        foreach ($events as $event) {
            $itemDate = new DateTime();
            $itemDate->setTimestamp($event['date_creation']);

            $itemUrl = $this->helper->url->to('TaskViewController', 'show', ['task_id' => $event['task_id']], '', true);

            $feedBuilder
                ->withItem(AtomItemBuilder::create($feedBuilder)
                    ->withTitle($event['event_title'])
                    ->withUrl($itemUrl.'#event-'.$event['id'])
                    ->withAuthor($event['author'])
                    ->withPublishedDate($itemDate)
                    ->withUpdatedDate($itemDate)
                    ->withContent($event['event_content'])
                );
        }

        return $feedBuilder;
    }
}
