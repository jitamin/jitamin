<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

/**
 * Project File Controller
 */
class ProjectFileController extends BaseController
{
    /**
     * File upload form
     *
     * @access public
     */
    public function create()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_file/create', array(
            'project' => $project,
            'max_size' => $this->helper->text->phpToBytes(get_upload_max_size()),
        )));
    }

    /**
     * Save uploaded files
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();

        if (! $this->projectFileModel->uploadFiles($project['id'], $this->request->getFileInfo('files'))) {
            $this->flash->failure(t('Unable to upload the file.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectViewController', 'show', array('project_id' => $project['id'])), true);
    }

    /**
     * Remove a file
     *
     * @access public
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

        $this->response->redirect($this->helper->url->to('ProjectViewController', 'show', array('project_id' => $project['id'])));
    }

    /**
     * Confirmation dialog before removing a file
     *
     * @access public
     */
    public function confirm()
    {
        $project = $this->getProject();
        $file = $this->projectFileModel->getById($this->request->getIntegerParam('file_id'));

        $this->response->html($this->template->render('project_file/remove', array(
            'project' => $project,
            'file' => $file,
        )));
    }
}
