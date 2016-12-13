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

use Hiject\Core\ObjectStorage\ObjectStorageException;

/**
 * File Viewer Controller.
 */
class FileViewerController extends BaseController
{
    /**
     * Get file content from object storage.
     *
     * @param array $file
     *
     * @return string
     */
    private function getFileContent(array $file)
    {
        $content = '';

        try {
            if ($file['is_image'] == 0) {
                $content = $this->objectStorage->get($file['path']);
            }
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
        }

        return $content;
    }

    /**
     * Show file content in a popover.
     */
    public function show()
    {
        $file = $this->getFile();
        $type = $this->helper->file->getPreviewType($file['name']);
        $params = ['file_id' => $file['id'], 'project_id' => $this->request->getIntegerParam('project_id')];

        if ($file['model'] === 'taskFileModel') {
            $params['task_id'] = $file['task_id'];
        }

        $this->response->html($this->template->render('file_viewer/show', [
            'file'    => $file,
            'params'  => $params,
            'type'    => $type,
            'content' => $this->getFileContent($file),
        ]));
    }

    /**
     * Display image.
     */
    public function image()
    {
        $file = $this->getFile();
        $etag = md5($file['path']);
        $this->response->withContentType($this->helper->file->getImageMimeType($file['name']));
        $this->response->withCache(5 * 86400, $etag);

        if ($this->request->getHeader('If-None-Match') === '"'.$etag.'"') {
            $this->response->status(304);
        } else {
            try {
                $this->response->send();
                $this->objectStorage->output($file['path']);
            } catch (ObjectStorageException $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * Display browser mime file.
     */
    public function browser()
    {
        $file = $this->getFile();
        $etag = md5($file['path']);
        $this->response->withContentType($this->helper->file->getBrowserViewType($file['name']));
        $this->response->withCache(5 * 86400, $etag);

        if ($this->request->getHeader('If-None-Match') === '"'.$etag.'"') {
            $this->response->status(304);
        } else {
            try {
                $this->response->send();
                $this->objectStorage->output($file['path']);
            } catch (ObjectStorageException $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * Display image thumbnail.
     */
    public function thumbnail()
    {
        $file = $this->getFile();
        $model = $file['model'];
        $filename = $this->$model->getThumbnailPath($file['path']);
        $etag = md5($filename);

        $this->response->withCache(5 * 86400, $etag);
        $this->response->withContentType('image/jpeg');

        if ($this->request->getHeader('If-None-Match') === '"'.$etag.'"') {
            $this->response->status(304);
        } else {
            $this->response->send();

            try {
                $this->objectStorage->output($filename);
            } catch (ObjectStorageException $e) {
                $this->logger->error($e->getMessage());

                // Try to generate thumbnail on the fly for images uploaded before Hiject < 1.0.19
                $data = $this->objectStorage->get($file['path']);
                $this->$model->generateThumbnailFromData($file['path'], $data);
                $this->objectStorage->output($this->$model->getThumbnailPath($file['path']));
            }
        }
    }

    /**
     * File download.
     */
    public function download()
    {
        try {
            $file = $this->getFile();
            $this->response->withFileDownload($file['name']);
            $this->response->send();
            $this->objectStorage->output($file['path']);
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
