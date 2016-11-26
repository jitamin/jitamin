<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Bootstrap Subscriber
 */
class BootstrapSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners
     *
     * @static
     * @access public
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'app.bootstrap' => 'execute',
        ];
    }

    /**
     * Execute
     *
     * @access public
     */
    public function execute()
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->languageModel->loadCurrentLanguage();
        $this->timezoneModel->setCurrentTimezone();
        $this->actionManager->attachEvents();

        if ($this->userSession->isLogged()) {
            $this->sessionStorage->hasSubtaskInProgress = $this->subtaskStatusModel->hasSubtaskInProgress($this->userSession->getId());
        }
    }

    /**
     * Destruct of the subscriber
     *
     * @access public
     */
    public function __destruct()
    {
        if (DEBUG) {
            foreach ($this->db->getLogMessages() as $message) {
                $this->logger->debug('SQL: ' . $message);
            }

            $this->logger->debug('APP: nb_queries={nb}', ['nb' => $this->db->getStatementHandler()->getNbQueries()]);
            $this->logger->debug('APP: rendering_time={time}', ['time' => microtime(true) - $this->request->getStartTime()]);
            $this->logger->debug('APP: memory_usage='.$this->helper->text->bytes(memory_get_usage()));
            $this->logger->debug('APP: uri='.$this->request->getUri());
            $this->logger->debug('###############################################');
        }
    }
}
