<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Project;

use Jitamin\Http\Controllers\Controller;

/**
 * Project File Controller.
 */
class ProjectFileController extends Controller
{
    /**
     * File upload form.
     */
    public function create()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project/attachment/create', [
            'project'  => $project,
            'max_size' => $this->helper->text->phpToBytes(get_upload_max_size()),
        ]));
    }

    /**
     * Save uploaded files.
     */
    public function store()
    {
        $project = $this->getProject();

        if (!$this->projectFileModel->uploadFiles($project['id'], $this->request->getFileInfo('files'))) {
            $this->flash->failure(t('Unable to upload the file.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Remove a file.
     */
    public function remove()
    {
        $project = $this->getProject();
        $file = $this->projectFileModel->getById($this->request->getIntegerParam('file_id'));

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->projectFileModel->remove($file['id'])) {
                $this->flash->success(t('File removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this file.'));
            }

            return $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]));
        }

        return $this->response->html($this->template->render('project/attachment/remove', [
            'project' => $project,
            'file'    => $file,
        ]));
    }
}
