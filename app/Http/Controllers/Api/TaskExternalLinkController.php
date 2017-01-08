<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Policy\TaskPolicy;
use Jitamin\Core\ExternalLink\ExternalLinkManager;
use Jitamin\Core\ExternalLink\ExternalLinkProviderNotFound;

/**
 * Task External Link API controller.
 */
class TaskExternalLinkController extends Controller
{
    /**
     * Get link types.
     *
     * @return array
     */
    public function getExternalTaskLinkTypes()
    {
        return $this->externalLinkManager->getTypes();
    }

    /**
     * Get a dictionary of supported dependency types by the provider.
     *
     * @return array
     */
    public function getExternalTaskLinkProviderDependencies($providerName)
    {
        try {
            return $this->externalLinkManager->getProvider($providerName)->getDependencies();
        } catch (ExternalLinkProviderNotFound $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get link.
     *
     * @param int $task_id
     * @param int $link_id
     *
     * @return array
     */
    public function getExternalTaskLinkById($task_id, $link_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getExternalTaskLink', $task_id);

        return $this->taskExternalLinkModel->getById($link_id);
    }

    /**
     * Get all links.
     *
     * @param int $task_id
     *
     * @return array
     */
    public function getAllExternalTaskLinks($task_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getExternalTaskLinks', $task_id);

        return $this->taskExternalLinkModel->getAll($task_id);
    }

    /**
     * Add a new link in the database.
     *
     * @param int    $task_id
     * @param string $url
     * @param string $dependency
     * @param string $type
     * @param strint $title
     *
     * @return bool|int
     */
    public function createExternalTaskLink($task_id, $url, $dependency, $type = ExternalLinkManager::TYPE_AUTO, $title = '')
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'createExternalTaskLink', $task_id);

        try {
            $provider = $this->externalLinkManager
                ->setUserInputText($url)
                ->setUserInputType($type)
                ->find();

            $link = $provider->getLink();

            $values = [
                'task_id'    => $task_id,
                'title'      => $title ?: $link->getTitle(),
                'url'        => $link->getUrl(),
                'link_type'  => $provider->getType(),
                'dependency' => $dependency,
            ];

            list($valid, $errors) = $this->externalLinkValidator->validateCreation($values);

            if (!$valid) {
                $this->logger->error(__METHOD__.': '.var_export($errors));

                return false;
            }

            return $this->taskExternalLinkModel->create($values);
        } catch (ExternalLinkProviderNotFound $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());
        }

        return false;
    }

    /**
     * Modify external link.
     *
     * @param int    $task_id
     * @param int    $link_id
     * @param string $url
     * @param string $dependency
     * @param string $type
     * @param strint $title
     *
     * @return bool
     */
    public function updateExternalTaskLink($task_id, $link_id, $title = null, $url = null, $dependency = null)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'updateExternalTaskLink', $task_id);

        $link = $this->taskExternalLinkModel->getById($link_id);
        $values = $this->filterValues([
            'title'      => $title,
            'url'        => $url,
            'dependency' => $dependency,
        ]);

        $values = array_merge($link, $values);
        list($valid, $errors) = $this->externalLinkValidator->validateModification($values);

        if (!$valid) {
            $this->logger->error(__METHOD__.': '.var_export($errors));

            return false;
        }

        return $this->taskExternalLinkModel->update($values);
    }

    /**
     * Remove a link.
     *
     * @param int $task_id
     * @param int $link_id
     *
     * @return bool
     */
    public function removeExternalTaskLink($task_id, $link_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'removeExternalTaskLink', $task_id);

        return $this->taskExternalLinkModel->remove($link_id);
    }
}
