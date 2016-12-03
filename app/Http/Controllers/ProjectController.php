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
 * Class ProjectController.
 */
class ProjectController extends BaseController
{
    /**
     * List of projects.
     */
    public function index()
    {
        if ($this->userSession->isAdmin()) {
            $project_ids = $this->projectModel->getAllIds();
        } else {
            $project_ids = $this->projectPermissionModel->getProjectIds($this->userSession->getId());
        }

        $nb_projects = count($project_ids);

        $paginator = $this->paginator
            ->setUrl('ProjectController', 'index')
            ->setMax(20)
            ->setOrder('name')
            ->setQuery($this->projectModel->getQueryColumnStats($project_ids))
            ->calculate();

        $this->response->html($this->helper->layout->app('project/index', [
            'paginator'   => $paginator,
            'nb_projects' => $nb_projects,
            'title'       => t('Projects').' ('.$nb_projects.')',
        ]));
    }

    /**
     * Display a form to create a new project.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $is_private = isset($values['is_private']) && $values['is_private'] == 1;
        $projects_list = [0 => t('Do not duplicate anything')] + $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        $this->response->html($this->helper->layout->app('project/create', [
            'values'        => $values,
            'errors'        => $errors,
            'is_private'    => $is_private,
            'projects_list' => $projects_list,
            'title'         => $is_private ? t('New private project') : t('New project'),
        ]));
    }

    /**
     * Display a form to create a private project.
     *
     * @param array $values
     * @param array $errors
     */
    public function createPrivate(array $values = [], array $errors = [])
    {
        $values['is_private'] = 1;
        $this->create($values, $errors);
    }

    /**
     * Validate and save a new project.
     */
    public function store()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->projectValidator->validateCreation($values);

        if ($valid) {
            $project_id = $this->createOrDuplicate($values);

            if ($project_id > 0) {
                $this->flash->success(t('Your project have been created successfully.'));

                return $this->response->redirect($this->helper->url->to('ProjectSettingsController', 'show', ['project_id' => $project_id]));
            }

            $this->flash->failure(t('Unable to create your project.'));
        }

        return $this->create($values, $errors);
    }

    /**
     * Create or duplicate a project.
     *
     * @param array $values
     *
     * @return bool|int
     */
    private function createOrDuplicate(array $values)
    {
        if (empty($values['src_project_id'])) {
            return $this->createNewProject($values);
        }

        return $this->duplicateNewProject($values);
    }

    /**
     * Save a new project.
     *
     * @param array $values
     *
     * @return bool|int
     */
    private function createNewProject(array $values)
    {
        $project = [
            'name'       => $values['name'],
            'is_private' => $values['is_private'],
        ];

        return $this->projectModel->create($project, $this->userSession->getId(), true);
    }

    /**
     * Creatte from another project.
     *
     * @param array $values
     *
     * @return bool|int
     */
    private function duplicateNewProject(array $values)
    {
        $selection = [];

        foreach ($this->projectDuplicationModel->getOptionalSelection() as $item) {
            if (isset($values[$item]) && $values[$item] == 1) {
                $selection[] = $item;
            }
        }

        return $this->projectDuplicationModel->duplicate(
            $values['src_project_id'],
            $selection,
            $this->userSession->getId(),
            $values['name'],
            $values['is_private'] == 1
        );
    }
}
