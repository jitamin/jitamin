<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project;

use Jitamin\Controller\BaseController;
use Jitamin\Filter\ProjectIdsFilter;
use Jitamin\Filter\ProjectStatusFilter;
use Jitamin\Filter\ProjectTypeFilter;
use Jitamin\Formatter\ProjectGanttFormatter;
use Jitamin\Model\ProjectModel;

/**
 * Class ProjectController.
 */
class ProjectController extends BaseController
{
    /**
     * Project entrypoint.
     */
    public function show()
    {
        $project = $this->getProject();

        list($className, $method) = $this->helper->app->getProjectDefaultView($project['default_view'], true);
        $controllerObject = new $className($this->container);

        return $controllerObject->{$method}();
    }

    /**
     * Show project overview.
     */
    public function overview()
    {
        $project = $this->getProject();
        $this->projectModel->getColumnStats($project);

        $this->response->html($this->helper->layout->app('project/overview', [
            'project'     => $project,
            'title'       => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'users'       => $this->projectUserRoleModel->getAllUsersGroupedByRole($project['id']),
            'roles'       => $this->projectRoleModel->getList($project['id']),
            'events'      => $this->helper->projectActivity->getProjectEvents($project['id'], 10),
            'images'      => $this->projectFileModel->getAllImages($project['id']),
            'files'       => $this->projectFileModel->getAllDocuments($project['id']),
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

                return $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project_id]));
            }

            $this->flash->failure(t('Unable to create your project.'));
        }

        return $this->create($values, $errors);
    }

    /**
     * Star a project (confirmation dialog box).
     */
    public function confirmStar()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project/star', [
            'project' => $project,
            'title'   => t('Star project'),
        ]));
    }

    /**
     * Unstar a project (confirmation dialog box).
     */
    public function confirmUnstar()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project/unstar', [
            'project' => $project,
            'title'   => t('Unstar project'),
        ]));
    }

    /**
     * Star the project.
     */
    public function star()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        if ($this->projectStarModel->addStargazer($project['id'], $this->userSession->getId())) {
            $this->flash->success(t('Project starred successfully.'));
        } else {
            $this->flash->failure(t('Unable to star this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Unstar the project.
     */
    public function unstar()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        if ($this->projectStarModel->removeStargazer($project['id'], $this->userSession->getId())) {
            $this->flash->success(t('Project unstarred successfully.'));
        } else {
            $this->flash->failure(t('Unable to unstar this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Update new project start date and end date.
     */
    public function updateDate()
    {
        $values = $this->request->getJson();

        $result = $this->projectModel->update([
            'id'         => $values['id'],
            'start_date' => $this->dateParser->getIsoDate(strtotime($values['start'])),
            'end_date'   => $this->dateParser->getIsoDate(strtotime($values['end'])),
        ]);

        if (!$result) {
            $this->response->json(['message' => 'Unable to save project'], 400);
        } else {
            $this->response->json(['message' => 'OK'], 201);
        }
    }

    /**
     * Prepare form values.
     *
     * @param string $redirect
     * @param array  $project
     * @param array  $values
     *
     * @return array
     */
    protected function prepareValues($redirect, array $project, array $values)
    {
        if ($redirect === 'edit') {
            if (isset($values['is_private'])) {
                if (!$this->helper->user->hasProjectAccess('Project/ProjectController', 'create', $project['id'])) {
                    unset($values['is_private']);
                }
            } elseif ($project['is_private'] == 1 && !isset($values['is_private'])) {
                if ($this->helper->user->hasProjectAccess('Project/ProjectController', 'create', $project['id'])) {
                    $values += ['is_private' => 0];
                }
            }
        }

        return $values;
    }

    /**
     * Create or duplicate a project.
     *
     * @param array $values
     *
     * @return bool|int
     */
    protected function createOrDuplicate(array $values)
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
    protected function createNewProject(array $values)
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
    protected function duplicateNewProject(array $values)
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
