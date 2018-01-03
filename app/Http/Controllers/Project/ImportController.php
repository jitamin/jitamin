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

use Jitamin\Foundation\Csv;
use Jitamin\Http\Controllers\Controller;

/**
 * Import controller.
 */
class ImportController extends Controller
{
    /**
     * Upload the file and ask settings.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Foundation\Exceptions\PageNotFoundException
     */
    public function show(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('task/import/show', [
            'project'    => $project,
            'values'     => $values,
            'errors'     => $errors,
            'max_size'   => get_upload_max_size(),
            'delimiters' => Csv::getDelimiters(),
            'enclosures' => Csv::getEnclosures(),
            'title'      => t('Import tasks from CSV file'),
        ], 'task/import/subside'));
    }

    /**
     * Process CSV file.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        $filename = $this->request->getFilePath('file');

        if (!file_exists($filename)) {
            $this->show($values, ['file' => [t('Unable to read your file')]]);
        } else {
            $this->taskImport->projectId = $project['id'];

            $csv = new Csv($values['delimiter'], $values['enclosure']);
            $csv->setColumnMapping($this->taskImport->getColumnMapping());
            $csv->read($filename, [$this->taskImport, 'import']);

            if ($this->taskImport->counter > 0) {
                $this->flash->success(t('%d task(s) have been imported successfully.', $this->taskImport->counter));
            } else {
                $this->flash->failure(t('Nothing have been imported!'));
            }

            $this->response->redirect($this->helper->url->to('Project/ImportController', 'show', ['project_id' => $project['id']]));
        }
    }

    /**
     * Generate template.
     */
    public function template()
    {
        $this->response->withFileDownload('tasks.csv');
        $this->response->csv([$this->taskImport->getColumnMapping()]);
    }
}
