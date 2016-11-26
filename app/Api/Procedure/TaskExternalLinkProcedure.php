<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Procedure;

use Hiject\Api\Authorization\TaskAuthorization;
use Hiject\Core\ExternalLink\ExternalLinkManager;
use Hiject\Core\ExternalLink\ExternalLinkProviderNotFound;

/**
 * Task External Link API controller
 */
class TaskExternalLinkProcedure extends BaseProcedure
{
    public function getExternalTaskLinkTypes()
    {
        return $this->externalLinkManager->getTypes();
    }

    public function getExternalTaskLinkProviderDependencies($providerName)
    {
        try {
            return $this->externalLinkManager->getProvider($providerName)->getDependencies();
        } catch (ExternalLinkProviderNotFound $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());
            return false;
        }
    }

    public function getExternalTaskLinkById($task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getExternalTaskLink', $task_id);
        return $this->taskExternalLinkModel->getById($link_id);
    }

    public function getAllExternalTaskLinks($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getExternalTaskLinks', $task_id);
        return $this->taskExternalLinkModel->getAll($task_id);
    }

    public function createExternalTaskLink($task_id, $url, $dependency, $type = ExternalLinkManager::TYPE_AUTO, $title = '')
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'createExternalTaskLink', $task_id);

        try {
            $provider = $this->externalLinkManager
                ->setUserInputText($url)
                ->setUserInputType($type)
                ->find();

            $link = $provider->getLink();

            $values = [
                'task_id' => $task_id,
                'title' => $title ?: $link->getTitle(),
                'url' => $link->getUrl(),
                'link_type' => $provider->getType(),
                'dependency' => $dependency,
            ];

            list($valid, $errors) = $this->externalLinkValidator->validateCreation($values);

            if (! $valid) {
                $this->logger->error(__METHOD__.': '.var_export($errors));
                return false;
            }

            return $this->taskExternalLinkModel->create($values);
        } catch (ExternalLinkProviderNotFound $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());
        }

        return false;
    }

    public function updateExternalTaskLink($task_id, $link_id, $title = null, $url = null, $dependency = null)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateExternalTaskLink', $task_id);

        $link = $this->taskExternalLinkModel->getById($link_id);
        $values = $this->filterValues([
            'title' => $title,
            'url' => $url,
            'dependency' => $dependency,
        ]);

        $values = array_merge($link, $values);
        list($valid, $errors) = $this->externalLinkValidator->validateModification($values);

        if (! $valid) {
            $this->logger->error(__METHOD__.': '.var_export($errors));
            return false;
        }

        return $this->taskExternalLinkModel->update($values);
    }

    public function removeExternalTaskLink($task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeExternalTaskLink', $task_id);
        return $this->taskExternalLinkModel->remove($link_id);
    }
}
