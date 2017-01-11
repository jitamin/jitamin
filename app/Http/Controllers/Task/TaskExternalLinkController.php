<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Task;

use Jitamin\Http\Controllers\Controller;
use Jitamin\Foundation\Exceptions\PageNotFoundException;
use Jitamin\Foundation\ExternalLink\ExternalLinkProviderNotFound;

/**
 * Task External Link Controller.
 */
class TaskExternalLinkController extends Controller
{
    /**
     * First creation form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Foundation\Exceptions\PageNotFoundException
     * @throws \Jitamin\Foundation\Exceptions\AccessForbiddenException
     */
    public function find(array $values = [], array $errors = [])
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task/external_link/find', [
            'values' => $values,
            'errors' => $errors,
            'task'   => $task,
            'types'  => $this->externalLinkManager->getTypes(),
        ]));
    }

    /**
     * Second creation form.
     */
    public function create()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        try {
            $provider = $this->externalLinkManager->setUserInput($values)->find();
            $link = $provider->getLink();

            $this->response->html($this->template->render('task/external_link/create', [
                'values' => [
                    'title'     => $link->getTitle(),
                    'url'       => $link->getUrl(),
                    'link_type' => $provider->getType(),
                ],
                'dependencies' => $provider->getDependencies(),
                'errors'       => [],
                'task'         => $task,
            ]));
        } catch (ExternalLinkProviderNotFound $e) {
            $errors = ['text' => [t('Unable to fetch link information.')]];
            $this->find($values, $errors);
        }
    }

    /**
     * Save link.
     */
    public function store()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->externalLinkValidator->validateCreation($values);

        if ($valid && $this->taskExternalLinkModel->create($values) !== false) {
            $this->flash->success(t('Link added successfully.'));

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
        }

        return $this->edit($values, $errors);
    }

    /**
     * Edit form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws ExternalLinkProviderNotFound
     * @throws \Jitamin\Foundation\Exceptions\PageNotFoundException
     * @throws \Jitamin\Foundation\Exceptions\AccessForbiddenException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $task = $this->getTask();
        $link_id = $this->request->getIntegerParam('link_id');

        if ($link_id > 0) {
            $values = $this->taskExternalLinkModel->getById($link_id);
        }

        if (empty($values)) {
            throw new PageNotFoundException();
        }

        $provider = $this->externalLinkManager->getProvider($values['link_type']);

        $this->response->html($this->template->render('task/external_link/edit', [
            'values'       => $values,
            'errors'       => $errors,
            'task'         => $task,
            'dependencies' => $provider->getDependencies(),
        ]));
    }

    /**
     * Update link.
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->externalLinkValidator->validateModification($values);

        if ($valid && $this->taskExternalLinkModel->update($values)) {
            $this->flash->success(t('Link updated successfully.'));

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
        }

        return $this->edit($values, $errors);
    }

    /**
     * Remove a link.
     */
    public function remove()
    {
        $task = $this->getTask();
        $link_id = $this->request->getIntegerParam('link_id');
        $link = $this->taskExternalLinkModel->getById($link_id);

        if (empty($link)) {
            throw new PageNotFoundException();
        }

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->taskExternalLinkModel->remove($link_id)) {
                $this->flash->success(t('Link removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this link.'));
            }

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]));
        }

        return $this->response->html($this->template->render('task/external_link/remove', [
            'link' => $link,
            'task' => $task,
        ]));
    }
}
