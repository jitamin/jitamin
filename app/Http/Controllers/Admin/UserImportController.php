<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Admin;

use Jitamin\Controller\Controller;
use Jitamin\Foundation\Csv;

/**
 * User Import controller.
 */
class UserImportController extends Controller
{
    /**
     * Upload the file and ask settings.
     *
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = [], array $errors = [])
    {
        $this->response->html($this->template->render('admin/user/import', [
            'values'     => $values,
            'errors'     => $errors,
            'max_size'   => get_upload_max_size(),
            'delimiters' => Csv::getDelimiters(),
            'enclosures' => Csv::getEnclosures(),
        ]));
    }

    /**
     * Submit form.
     */
    public function store()
    {
        $values = $this->request->getValues();
        $filename = $this->request->getFilePath('file');

        if (!file_exists($filename)) {
            $this->flash->failure(t('Unable to read your file'));
        } else {
            $this->importFile($values, $filename);
        }

        $this->response->redirect($this->helper->url->to('Admin/UserController', 'index'));
    }

    /**
     * Generate template.
     */
    public function template()
    {
        $this->response->withFileDownload('users.csv');
        $this->response->csv([$this->userImport->getColumnMapping()]);
    }

    /**
     * Process file.
     *
     * @param array $values
     * @param       $filename
     */
    protected function importFile(array $values, $filename)
    {
        $csv = new Csv($values['delimiter'], $values['enclosure']);
        $csv->setColumnMapping($this->userImport->getColumnMapping());
        $csv->read($filename, [$this->userImport, 'import']);

        if ($this->userImport->counter > 0) {
            $this->flash->success(t('%d user(s) have been imported successfully.', $this->userImport->counter));
        } else {
            $this->flash->failure(t('Nothing have been imported!'));
        }
    }
}
