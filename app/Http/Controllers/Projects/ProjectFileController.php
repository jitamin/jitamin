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

/**
 * Project File Controller.
 */
class ProjectFileController extends BaseController
{
    /**
     * File upload form.
     */
    public function create()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_file/create', [
            'project'  => $project,
            'max_size' => $this->helper->text->phpToBytes(get_upload_max_size()),
        ]));
    }

    /**
     * Save uploaded files.
     */
    public function save()
    {
        $project = $this->getProject();

        if (!$this->projectFileModel->uploadFiles($project['id'], $this->request->getFileInfo('files'))) {
            $this->flash->failure(t('Unable to upload the file.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Remove a file.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProject();
        $file = $this->projectFileModel->getById($this->request->getIntegerParam('file_id'));

        if ($this->projectFileModel->remove($file['id'])) {
            $this->flash->success(t('File removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this file.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectController', 'show', ['project_id' => $project['id']]));
    }

    /**
     * Confirmation dialog before removing a file.
     */
    public function confirm()
    {
        $project = $this->getProject();
        $file = $this->projectFileModel->getById($this->request->getIntegerParam('file_id'));

        $this->response->html($this->template->render('project_file/remove', [
            'project' => $project,
            'file'    => $file,
        ]));
    }
}
